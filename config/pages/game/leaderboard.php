<?php

return [
    'sidebar' => [
        [
            'title' => "About our Rating System",
            'html' => "
                <p>
                    Our rating system is based on the Glicko2 skill based rating system.
                    Ratings are determined based on your performance against other rated players.
                </p>
                <p>
                    If you beat a player ranked significantly higher, you will earn more points.
                    If you beat a player ranked significantly lower, you will earn less points.
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
            'title' => "Why don't I have a ranking?",
            'html' => "
                <p>
                    You must play <b class='--text-primary'>{$config->get('game.rating.period')} matches</b> before a base rating is assigned.
                </p>
                <p>
                    These <b class='--text-primary'>{$config->get('game.rating.period')} matches</b> will determine your baseline skill rating.  We like to call these matches your rank placement matches.
                </p>
            "
        ]
    ]
];
