<?php

namespace App\Commands\Upload\Image;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getRules(array $data = []) : array
    {
        return [
            'default' => [
                'required' => $this->templates->required('default name'),
                'string' => $this->templates->string('default name')
            ],
            'file' => [
                'required' => $this->templates->required('file')
            ],
            'name' => [
                'required' => $this->templates->required('name')
            ]
        ];
    }


    public function writeUploadFailedMessage()
    {
        $this->error("Image upload failed, please try again!");
    }
}
