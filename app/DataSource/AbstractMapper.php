<?php

namespace App\DataSource;

use Closure;
use Contracts\Container\Container;
use Contracts\Jobs\Queue;
use Contracts\QueryBuilder\QueryBuilder;
use Contracts\Redis\Redis as Cache;
use Exception;

abstract class AbstractMapper
{

    // IoC Container
    private $container;

    // Job Queue
    private $queue;

    // Insert Flag
    private $onDuplicateKeyUpdate = false;


    // Cache Instance
    protected $cache;

    // Entities Classname
    protected $entities;

    // Entity Classname
    protected $entity;

    // QueryBuilder
    protected $qb;

    // Record Classname
    protected $record;

    // Table Name
    protected $table;


    public function __construct(Cache $cache, Container $container, QueryBuilder $qb, Queue $queue)
    {
        $this->cache = $cache;
        $this->container = $container;
        $this->qb = $qb;
        $this->queue = $queue;

        if (is_null($this->entities) || is_null($this->entity)) {
            throw new Exception("Entities Or Entity Property Not Defined In '" . get_called_class() . "'");
        }

        if (is_null($this->record)) {
            throw new Exception("Record Property Not Defined In '" . get_called_class() . "'");
        }

        if (!is_null($this->table)) {
            $this->cache->setPrefix("DataSource:Mapper:{$this->table}");
            $this->qb->table($this->table);
        }
    }


    public function create(array $fill = []) : AbstractEntity
    {
        $entity = $this->row();
        $entity->fill($fill);

        return $entity;
    }


    public function delete(AbstractEntity ...$entities) : int
    {
        if (!$entities) {
            return 0;
        }

        $delete = [];
        $primaryField = $entities[0]->getPrimaryField();

        foreach ($entities as $entity) {
            $entity->deleting();

            $delete[] = $entity->{'get' . ucFirst($primaryField)}();

        }

        $result = $this->qb->delete()
            ->where("{$primaryField} IN {$this->qb->placeholders($delete)}", [$delete])
            ->execute();

        $entity->deleted($result);

        if (method_exists($this, 'deleted')) {
            $this->deleted(...$entities);
        }

        $insert = [];

        foreach ($entities as $entity) {
            $insert[] = [
                'createdAt' => time(),
                'data' => json_encode($entity->getStorageValues()),
                'name' => $this->table
            ];
        }

        if (count($insert)) {
            $this->qb->insert()
                ->table('DeleteHistory')
                ->values($insert)
                ->execute();
        }

        return $result;
    }


    public function exists($input) : bool
    {
        $input = (array) $input;

        return $this->qb->select()
            ->where("{$this->row()->getPrimaryField()} IN {$this->qb->placeholders($input)}", [$input])
            ->count() === count($input);
    }


    // If Seperating Jobs -> "{$this->table}:Jobs"
    public function getActiveJobKey() : string
    {
        return 'Jobs';
    }


    // If Seperating Jobs -> "{$this->table}:DelayedJobs"
    public function getDelayedJobKey() : string
    {
        return 'DelayedJobs';
    }


    public function getIdentifiers() : array
    {
        $identifiers = $this->row()->getFields();
        $identifiers[] = $this->table;

        return $identifiers;
    }


    public function insert(AbstractEntity ...$entities) : int
    {
        if (!$entities) {
            return 0;
        }

        $insert = [];

        foreach ($entities as $entity) {
            if ($entity->isEmpty()) {
                throw new Exception("Insert Failed: Mapper '" . get_called_class() . "' Received Empty Entity!");
            }

            $entity->inserting();

            $insert[] = $entity->getStorageValues();
        }

        $query = $this->qb->insert()
            ->values($insert);

        if ($this->onDuplicateKeyUpdate) {
            $query->onDuplicateKeyUpdate();
        }

        $result = $query->execute();

        // If An Insert Fails While In Transaction Trigger Rollback
        if ($this->qb->inTransaction() && !$result) {
            throw new Exception("Insert Failed: " . json_encode($insert));
        }

        foreach ($entities as $entity) {
            $entity->inserted(count($entities) === 1 ? $result : 0);
        }

        if (method_exists($this, 'inserted')) {
            $this->inserted(...$entities);
        }

        return $result;
    }


    public function replace(AbstractEntity ...$entities) : int
    {
        $this->onDuplicateKeyUpdate = true;

        $result = $this->insert(...$entities);

        $this->onDuplicateKeyUpdate = false;

        return $result;
    }


    public function retrieveJob() : ?Job
    {
        return $this->queue->next($this->getActiveJobKey(), $this->getDelayedJobKey());
    }


    protected function row(array $values = []) : AbstractEntity
    {
        $row = $this->container->resolve($this->record);

        foreach ($values as $key => $value) {
            $row->set($key, $value);
        }

        return $this->container->resolve($this->entity, [$row]);
    }


    protected function rows(array $rows = []) : AbstractEntities
    {
        $entities = [];

        foreach ($rows as $row) {
            $entities[] = $this->row($row);
        }

        return $this->container->resolve($this->entities, $entities);
    }


    protected function scheduleJob(string $classname, string $method, array $parameters = [], int $seconds = 0) : void
    {
        $this->queue->add(($seconds ? $this->getDelayedJobKey() : $this->getActiveJobKey()), $classname, $method, $parameters, $seconds);
    }


    protected function scheduleLockedJob(string $classname, string $method, array $parameters = [], int $seconds = 0) : void
    {
        $this->queue->add(($seconds ? $this->getDelayedJobKey() : $this->getActiveJobKey()), $classname, $method, $parameters, $seconds, true);
    }


    public function transaction(Closure $callback)
    {
        return $this->qb->transaction($callback);
    }


    public function update(AbstractEntity ...$entities) : int
    {
        if (!$entities) {
            return 0;
        }

        $primaryField = $entities[0]->getPrimaryField();
        $total = 0;

        foreach ($entities as $entity) {
            if ($entity->isEmpty()) {
                throw new Exception("Update Failed: Mapper '" . get_called_class() . "' Received Empty Entity!");
            }
            elseif (!count($entity->getModified())) {
                continue;
            }

            $entity->updating();

            $result = $this->qb->update()
                ->values($entity->getStorageValues())
                ->where("{$primaryField} = ?", [$entity->{'get' . ucFirst($primaryField)}()])
                ->execute();

            $entity->updated($result);

            $total += $result;
        }

        if (method_exists($this, 'updated')) {
            $this->updated(...$entities);
        }

        return $total;
    }
}
