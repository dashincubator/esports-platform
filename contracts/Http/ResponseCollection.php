<?php declare(strict_types=1);

namespace Contracts\Http;

use Contracts\Support\Arrayable;
use Countable;
use IteratorAggregate;

interface ResponseCollection extends Arrayable, Countable, IteratorAggregate
{

    /**
     *  @param string $key
     *  @return Response     
     */
    public function get(string $key) : Response;


    /**
     *  @param string $key
     *  @return bool True If Exists, Otherwise False
     */
    public function has(string $key) : bool;


    /**
     *  Add Response To Collection
     *
     *  @param Response $response
     *  @return self
     */
    public function set(string $key, Response $response) : ResponseCollection;
}
