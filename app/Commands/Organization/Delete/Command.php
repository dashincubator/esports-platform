<?php

namespace App\Commands\Organization\Delete;

use App\Commands\AbstractCommand;
use App\Commands\User\AdminPosition\Delete\Command as DeleteAdminPositionsCommand;
use App\Commands\Ladder\Delete\Command as DeleteLadderCommand;
use App\Commands\User\AdminPosition\Delete\Command as DeleteCommand;
use App\Commands\User\Update\Admin\Command as UpdateCommand;
use App\DataSource\Organization\Mapper as OrganizationMapper;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Admin\Position\Mapper as PositionMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(
        DeleteAdminPositionsCommand $positions,
        DeleteLadderCommand $ladder,
        Filter $filter,
        OrganizationMapper $organization,
        PositionMapper $position,
        UpdateCommand $update,
        UserMapper $user
    ) {
        $this->command = compact('ladder', 'positions', 'update');
        $this->filter = $filter;
        $this->mapper = compact('organization', 'position', 'user');
    }


    protected function run(int $id) : bool
    {
        $organization = $this->mapper['organization']->findById($id);

        if ($organization->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $positions = $this->mapper['position']->findByOrganization($organization->getId());
            $users = $this->mapper['user']->findIdsByAdminPositions(...$positions->column('id'));

            // Delete All Admin Positions Associated With Organization
            $this->delegate($this->command['positions'], [
                'editor' => 0,
                'organization' => $organization->getId()
            ]);

            // Update User Admin Position
            if ($users) {
                $this->delegate($this->command['update'], [
                    'editor' => 0,
                    'adminPosition' => 0,
                    'users' => $users
                ]);
            }

            $this->command['ladder']->execute([
                'organization' => $this->delegate($this->command['ladder'])
            ]);
        }

        if (!$this->filter->hasErrors()) {
            $this->filter->writeSuccessMessage();
            $this->mapper['organization']->delete($organization);
        }

        return $this->booleanResult();
    }
}
