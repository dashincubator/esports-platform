<?php declare(strict_types=1);

namespace Contracts\QueryBuilder\Query;

interface Query
{

    /**
     *  @return array Query Parameters
     */
    public function getParameters() : array;


    /**
     *  @return string Query SQL String
     */
    public function getSql() : string;
}
