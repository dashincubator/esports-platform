<?php

namespace App\Services\Team;

use App\DataSource\Game\Mapper as GameMapper;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Account\Mapper as AccountMapper;
use App\DataSource\User\Rank\Mapper as RankMapper;
use App\DataSource\Event\Team\Member\AbstractEntities as AbstractMembersEntities;

class RosterUI
{

    private $mapper;


    public function __construct(AccountMapper $account, GameMapper $game, RankMapper $rank, UserMapper $user)
    {
        $this->mapper = compact('account', 'game', 'rank', 'user');
    }


    public function build(AbstractMembersEntities $members, int $game, array $messages) : array
    {
        $game  = $this->mapper['game']->findById($game);
        $ranks = $this->mapper['rank']->findByGameAndUsers($game->getId(), ...$members->column('user'));
        $users = $this->mapper['user']->findByIds(...$members->column('user'));

        $accounts = $this->mapper['account']->findByNameAndUsers($game->getAccount(), ...$members->column('user'));
        $roster = [];

        foreach ($members as $member) {
            $data = $member->toArray();
            $data['account'] = $accounts->findByUser($member->getUser())->getFirstValue();
            $data['eligible'] = true;
            $data['rank'] = $ranks->get('user', $member->getUser());
            $data['user'] = $users->get('id', $member->getUser());

            foreach ($messages as $message) {
                if (mb_strpos($message, $data['user']->getUsername()) !== false) {
                    $data['eligible'] = false;
                    break;
                }
            }

            $roster[] = $data;
        }

        return $roster;
    }
}
