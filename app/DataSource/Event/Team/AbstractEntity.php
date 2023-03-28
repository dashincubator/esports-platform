<?php

namespace App\DataSource\Event\Team;

use App\DataSource\AbstractEntity as AbstractParent;
use Contracts\Slug\Generator;

abstract class AbstractEntity extends AbstractParent
{

    protected $fillable = [
        'avatar',
        'banner',
        'bio',
        'name'
    ];

    protected $slug;


    public function __construct(AbstractRecord $record, Generator $slug)
    {
        parent::__construct($record);

        $this->slug = $slug;
    }


    public function createdBy(int $createdBy) : void
    {
        $this->set('createdBy', $createdBy);
    }


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function lock() : void
    {
        $this->set('locked', true);
        $this->set('lockedAt', time());
    }


    public function isLocked() : bool
    {
        return $this->get('locked');
    }


    protected function setName(string $name) : string
    {
        $this->set('slug', $name);

        return $name;
    }


    protected function setSlug(string $slugify) : string
    {
        return $this->slug->generate($slugify);
    }


    public function toArray() : array
    {
        $data = parent::toArray();
        $data['isLocked'] = $this->isLocked();

        return $data;
    }
}
