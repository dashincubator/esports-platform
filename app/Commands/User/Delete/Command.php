<?php

namespace App\Commands\User\Delete;

use App\Commands\AbstractCommand;
use App\Commands\User\Bank\Delete\Command as DeleteBankCommand;
use App\Commands\User\ForgotPassword\Delete\Command as DeleteForgotPasswordCommand;
use App\Commands\User\Rank\Delete\Command as DeleteRankCommand;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\Ladder\Team\Member\Mapper as LadderTeamMemberMapper;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(
        DeleteBankCommand $bank,
        DeleteForgotPasswordCommand $fpw,
        DeleteRankCommand $rank,
        Filter $filter,
        LadderTeamMemberMapper $ladder,
        UserMapper $user
    ) {
        $this->command = compact('bank', 'fpw', 'rank');
        $this->filter = $filter;
        $this->mapper = compact('ladder', 'user');
    }


    private function deleteBank(int $user) : void
    {
        $this->delegate($this->command['bank'], compact('user'));
    }


    private function deleteForgotPassword(int $user) : void
    {
        $this->delegate($this->command['fpw'], compact('user'));
    }


    private function deleteRanks(int $user) : void
    {
        $this->delegate($this->command['rank'], compact('user'));
    }


    protected function run(int $id) : bool
    {
        $user = $this->mapper['user']->findById($id);

        if ($user->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif ($this->mapper['ladder']->onAnyTeam($user->getId())) {
            $this->writeLeaveAllTeamsMessage();
        }

        if (!$this->filter->hasErrors()){
            $this->mapper['user']->delete($user);

            // Cascade
            $this->deleteBank($user->getId());
            $this->deleteForgotPassword($user->getId());
            $this->deleteRank($user->getId());
        }

        return $this->booleanResult();
    }
}
