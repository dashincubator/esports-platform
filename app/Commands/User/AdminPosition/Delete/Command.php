<?php

namespace App\Commands\User\AdminPosition\Delete;

use App\Commands\AbstractCommand;
use App\Commands\User\Update\Admin\Command as UpdateCommand;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Admin\Position\Mapper as PositionMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, PositionMapper $position, UpdateCommand $update, UserMapper $user)
    {
        $this->command = compact('update');
        $this->filter = $filter;
        $this->mapper = compact('position', 'user');
    }


    protected function run(int $editor, ?int $id, ?int $organization) : bool
    {
        if ($id) {
            $position = $this->mapper['position']->findById($id);

            if ($position->isEmpty()) {
                $this->filter->writeUnknownErrorMessage();
            }
            else {
                $delete = [$position];
                $positions = [$id];
            }
        }
        elseif ($organization) {
            $positions = $this->mapper['position']->findByOrganization($organization);

            if ($positions->isEmpty()) {
                return $this->booleanResult();
            }
            else {
                $delete = iterator_to_array($positions);
                $positions = $positions->column('id');
            }
        }
        else {
            throw new Exception("Cannot Delete Admin Position Without ID And Organization");
        }

        if (!$this->filter->hasErrors()) {
            $this->delegate($this->command['update'], [
                'editor' => $editor,
                'adminPosition' => 0,
                'users' => $this->mapper['user']->findIdsByAdminPositions(...$positions)
            ]);

            $this->filter->writeSuccessMessage();
            $this->mapper['position']->delete(...$delete);
        }

        return $this->booleanResult();
    }
}
