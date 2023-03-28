<?php declare(strict_types=1);

namespace Contracts\MultiCurl;

interface Parser
{

    /**
     *  @param string $content CURL Response
     *  @return string Json
     */
    public function toJson(string $content) : string;
}
