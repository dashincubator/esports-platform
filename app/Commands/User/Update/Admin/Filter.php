<?php

namespace App\Commands\User\Update\Admin;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'adminPosition' => [
                'int' => $this->templates->invalid('admin position'),
                'required' => $this->templates->required('admin position')
            ],
            'editor' => [
                'int' => $this->templates->invalid('editor'),
                'required' => $this->templates->required('editor')
            ],
            'users' => [
                'array' => $this->templates->invalid('users list'),
                'required' => $this->templates->required('users list')
            ],
            'users.*' => [
                'int' => $this->templates->invalid('users list'),
                'required' => $this->templates->required('users list')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Admin updated successfully!';
    }


    public function writeCannotModifyOwnAccountMessage()
    {
        $this->error("Admin cannot modify their own position");
    }
}
