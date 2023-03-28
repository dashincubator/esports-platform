<?php

namespace App\Commands\Ladder\Match\Cancel;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\Match\{Entity as MatchEntity, Mapper as MatchMapper};
use App\DataSource\Ladder\Match\Report\Mapper as MatchReportMapper;
use App\DataSource\Ladder\Team\Member\Mapper as MemberMapper;
use App\DataSource\User\Bank\Transaction\Mapper as TransactionMapper;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(
        Filter $filter,
        MatchMapper $match,
        MatchReportMapper $report,
        MemberMapper $member,
        TransactionMapper $transaction
    ) {
        $this->filter = $filter;
        $this->mapper = compact('match', 'member', 'report', 'transaction');
    }


    protected function run(int $id, int $user) : bool
    {
        $match = $this->mapper['match']->findById($id);

        if ($match->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif (!$match->inMatchFinder() || $match->isUpcoming()) {
            $this->filter->writeActiveMatchMessage();
        }
        else {
            $member = $this->mapper['member']->findByTeamAndUser($match->getTeam(), $user);

            if ($member->isEmpty()) {
                $this->filter->writeUnknownErrorMessage();
            }
            elseif (!$member->managesMatches()) {
                $this->filter->writeUnauthorizedMessage();
            }
        }

        if (!$this->filter->hasErrors()) {
            $this->mapper['match']->transaction(function() use ($match) {
                $this->mapper['match']->delete($match);
                $this->mapper['report']->delete(
                    ...$this->mapper['report']->findByMatch($match->getId())
                );

                if ($match->isWager()) {
                    $this->mapper['transaction']->scheduleLadderMatchRefundJob([
                        'ladderMatch' => $match->getLadder()
                    ]);
                }
            });
        }

        return $this->booleanResult($match->isWager());
    }
}
