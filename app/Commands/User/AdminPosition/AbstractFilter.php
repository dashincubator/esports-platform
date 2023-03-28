<?php

namespace App\Commands\User\AdminPosition;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getRules(array $data = []) : array
    {
        $permissions = $data['permissions'] ?? [];

        return [
            'games' => [
                'array' => $this->templates->invalid('games list'),
                'required' => $this->templates->required('games list')
            ],
            'games.*' => [
                'int' => $this->templates->invalid('games list')
            ],
            'name' => [
                'required' => $this->templates->required('name'),
                'string' => $this->templates->string('name')
            ],
            'permissions' => [
                'array' => $this->templates->invalid('permissions list'),
                'min:1' => $this->templates->min('permissions list', $permissions, 1),
                'required' => $this->templates->required('permissions list')
            ],
            'permissions.*' => [
                'required' => $this->templates->required('permissions list'),
                'string' => $this->templates->string('permissions list')
            ]
        ];
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return "Admin position {$action} successfully!";
    }
}
