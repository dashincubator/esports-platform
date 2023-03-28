<?php

namespace App\View\Extensions;

class Html
{

    public function attributes(array $attributes) : array
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            $html .= "{$key}='{$value}' ";
        }

        return compact('html');
    }


    public function classes(array $classes) : string
    {
        return trim(implode(' ', array_unique($classes)));
    }


    public function directives(array $directives) : array
    {
        $html = '';

        foreach ($directives as $key => $value) {
            $html .= "data-{$key}='{$value}' ";
        }

        return compact('html');
    }


    public function escape(string $html) : string
    {
        return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
