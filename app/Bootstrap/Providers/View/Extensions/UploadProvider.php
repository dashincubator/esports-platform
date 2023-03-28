<?php

namespace App\Bootstrap\Providers\View\Extensions;

use App\Bootstrap\Providers\AbstractProvider;
use App\View\Extensions\Upload;

class UploadProvider extends AbstractProvider
{

    private function flatten(array $paths, string $prepend = '') : array
    {
        $flatten = [];

        foreach ($paths as $key => $value) {
            if ($value && is_array($value)) {
                $flatten = array_merge($flatten, $this->flatten($value, "{$prepend}{$key}."));
            }
            else {
                $flatten["{$prepend}{$key}"] = $value;
            }
        }

        return $flatten;
    }


    public function register() : void
    {
        $this->container->bind(Upload::class, null, [
            $this->flatten($this->config->get('paths.uploads'))
        ]);
    }
}
