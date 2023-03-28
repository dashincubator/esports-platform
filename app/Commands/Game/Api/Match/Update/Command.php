<?php

namespace App\Commands\Game\Api\Match\Update;

use App\DataSource\Game\Api\Match\Mapper;
use App\Services\Api\Managers;
use App\Commands\AbstractCommand;

class Command extends AbstractCommand
{

    private $limit = 50;

    private $limitPerJob = 5000;

    private $managers;

    private $mapper;

    private $page = 1;


    public function __construct(Mapper $mapper, Managers $managers)
    {
        $this->managers = $managers;
        $this->mapper = $mapper;
    }


    private function persist(array $delete, array $update) : void
    {
        foreach ($update as $api => $entity) {
            $replace = $this->managers->fetch($api, ...$entity);

            // Delete Invalid Usernames To Prevent Further Fetching/Issues
            $this->mapper->deleteByApiAndUsernames($api, ...$this->managers->getInvalidAccountUsernames($api));

            if (!count($replace)) {
                continue;
            }

            foreach ($replace as $index => $fill) {
                $replace[$index] = $this->mapper->create($fill);
            }

            $this->mapper->replace(...$replace);
        }

        // Delete Expired After Update
        $this->mapper->delete(...$delete);
    }


    protected function run() : bool
    {
        // Add Cool Down
        // - Jobs Can Be Locked But We Can't Guarantee A Cool Down In Between
        sleep(30);

        while (!($entities = $this->mapper->findExpired($this->limit, $this->page))->isEmpty()) {
            $delete = [];
            $update = [];

            // Split Into Groups By Api
            foreach ($entities as $entity) {
                $update[$entity->getApi()][] = $entity;

                if ($entity->isExpired()) {
                    $delete[] = $entity;
                }
            }

            $this->persist($delete, $update);

            if (($this->limit * $this->page) >= $this->limitPerJob) {
                break;
            }

            $this->page++;
        }

        return $this->booleanResult();
    }
}
