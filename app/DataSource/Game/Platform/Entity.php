<?php

namespace App\DataSource\Game\Platform;

use App\DataSource\AbstractEntity;
use Contracts\Slug\Generator;

class Entity extends AbstractEntity
{

    private $slug;


    protected $fillable = [
        'account', 'name', 'slug', 'view'
    ];


    public function __construct(Generator $slug, Record $record)
    {
        parent::__construct($record);

        $this->slug = $slug;
    }


    protected function setName(string $name) : string
    {
        $this->set('slug', $name);

        return $name;
    }


    protected function setSlug(string $slugify) : string
    {
        if (!$slugify) {
            return $this->get('slug');
        }

        return $this->slug->generate($slugify);
    }
}
