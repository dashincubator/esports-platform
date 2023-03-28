<?php

namespace App\Http\Actions\Web\Ladder\Leaderboard;

use App\DataSource\Ladder\Team\Mapper;
use App\Http\Actions\AbstractAction;
use Contracts\Configuration\Configuration;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private const PER_PAGE = 25;


    private $config;

    private $mapper;


    public function __construct(Configuration $config, Mapper $mapper, Responder $responder)
    {
        $this->config = $config;
        $this->mapper = $mapper;
        $this->responder = $responder;
    }


    // TODO: Delete Tab Routes In Favor Of This + Dynamic HTML Snippets
    public function handle(Request $request, string $platform, string $game, string $ladder, int $page = 1) : Response
    {
        $ladder = $request->getAttributes()->get('ladder');

        $limit = self::PER_PAGE;
        $total = $this->mapper->countLeaderboardByScores($ladder->getId());
        $pages = ceil($total / $limit);

        if ($page > $pages) {
            $page = 1;
        }

        $faq = $this->buildFAQ($ladder);
        $info = $this->buildInfo($ladder);
        $rules = $this->buildRules($ladder);
        $teams = $this->mapper->leaderboardByScores($ladder->getId(), $limit, $page);

        return $this->responder->handle(compact('faq', 'info', 'ladder', 'limit', 'page', 'pages', 'rules', 'total', 'teams'));
    }


    private function buildFAQ($ladder) : array
    {
        $faq = [];

        foreach ($this->config->get('pages.faq.categories') as $section) {
            if (!in_array(strtolower($section['title']), [
                'ladders', 'leagues', 'rating system', 'roster lock',
                'support center', 'team management'
            ])) {
                continue;
            }

            $faq[] = $section;
        }

        return $faq;
    }


    private function buildInfo($ladder) : array
    {
        return array_merge($ladder->getInfo(), $this->config->get('pages.ladder.prizes'));
    }


    private function buildRules($ladder) : array
    {
        return [
            [
                'title' => 'Event Rules',
                'sections' => [
                    [
                        'title' => 'Event Rules',
                        'sections' => $ladder->getRules()
                    ]
                ]
            ],
            [
                'title' => 'General Rules',
                'sections' => $this->config->get('pages.ladder.rules')
            ]
        ];
    }
}
