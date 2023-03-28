<?php declare(strict_types=1);

namespace Contracts\Upload;

interface Image
{

    /**
     *  @param File $file
     *  @param string $name Desired File Name
     *  @return string Filename If Successful Otherwise Null
     */
    public function upload(File $file, string $name) : ?string;
}
