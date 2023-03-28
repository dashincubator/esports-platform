<?php

return [
    'sidebar' => [
        'matchfinder' => [
            [
                'title' => "About our Rating System",
                'html' => "
                    <p>
                        Our rating system is based on the Glicko2 skill based rating system.
                        Ratings are determined based on your team's performance against other rated teams.
                    </p>
                    <p>
                        If your team beats a team ranked significantly higher, you will earn more points.
                        If your team beats a team ranked significantly lower, you will earn less points.
                    </p>
                "
            ],
            [
                'title' => "How do team matches affect my individual rating?",
                'html' => "
                    <p>
                        Your individual rating for this leaderboard will change after singles, doubles, and team matches.
                        For doubles and team matches, we take your individual rating and match that up against
                        an average of all individual ratings on the opposing team.
                    </p>
                    <p>
                        If you beat a team average ranked significantly higher, you will earn more points.
                        If you beat a team average ranked significantly lower, you will earn less points.
                    </p>
                "
            ],
            [
                'title' => "Ladder Rankings",
                'html' => "
                    <p>
                        These rankings are specific for this ladder. Only match results for this ladder will determine your team's rating on this leaderboard.
                    </p>
                "
            ],
            [
                'title' => "Why doesn't my team have a ranking?",
                'html' => "
                    <p>
                        You must play <b class='--text-primary'>{$config->get('game.rating.period')} matches</b> before a base rating is assigned.
                    </p>
                    <p>
                        These <b class='--text-primary'>{$config->get('game.rating.period')} matches</b> will determine your baseline skill rating. We like to call these matches your rank placement matches.
                    </p>
                "
            ]
        ],
        'warzone' => [
            [
                'title' => "How do Warzone ladders work?",
                'html' => "
                    <p>
                        Our warzone ladders track your performance in public lobbies.
                        When a ladder starts we pull a snapshot of your current wins, kills, or any other relevant metric from Call of Duty's API.
                    </p>
                    <p>
                        Every few minutes, we make another call to that API and update the leaderboard.
                        When the event ends, staff will spend some time verifying the final leaderboard and checking for any cheaters before awarding prizes when applicable.
                    </p>
                    <p>
                        Our staff will ask all winners to verify ownership of their in game accounts in order to receive their prizes.
                    </p>
                "
            ]/*,
            [
                'title' => "Why isn't cross play supported?",
                'html' => "
                    <p>
                        Warzone currently has a problem with players on PC using aim bots.
                        Once this issue is addressed we will begin supporting cross play Warzone.
                    </p>
                    <p>
                        If you are participating in this event we STRONGLY RECOMMEND turning off cross-play matchmaking to avoid
                        running into a PC player using any kind of in game hack.
                    </p>
                "
            ]*/
        ],
        'warzone-race' => [
            [
                'title' => "How do 'Race To' Warzone ladders work?",
                'html' => "
                    <p>
                        Our 'Race to' events track your performance in public warzone lobbies, much like our regular warzone ladders - see 'How do
                        Warzone ladders work'?
                    </p>
                    <p>
                        The only difference - in the race to events, the first player to hit the winning threshold wins the entire prizepool and the
                        event is finished. There is only 1 winner here. If you aren't first, you're last.
                    </p>
                "
            ],
            [
                'title' => "How do Warzone ladders work?",
                'html' => "
                    <p>
                        Our warzone ladders track your performance in public lobbies.
                        When a ladder starts we pull a snapshot of your current wins, kills, or any other relevant metric from Call of Duty's API.
                    </p>
                    <p>
                        Every few minutes, we make another call to that API and update the leaderboard.
                        When the event ends, staff will spend some time verifying the final leaderboard and checking for any cheaters before awarding prizes when applicable.
                    </p>
                    <p>
                        Our staff will ask all winners to verify ownership of their in game accounts in order to receive their prizes.
                    </p>
                "
            ]/*,
            [
                'title' => "Why isn't cross play supported?",
                'html' => "
                    <p>
                        Warzone currently has a problem with players on PC using aim bots.
                        Once this issue is addressed we will begin supporting cross play Warzone.
                    </p>
                    <p>
                        If you are participating in this event we STRONGLY RECOMMEND turning off cross-play matchmaking to avoid
                        running into a PC player using any kind of in game hack.
                    </p>
                "
            ]*/
        ]
    ]
];
