<?php

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  Game Account Keys
     *
     */

    'accounts' => [
        'activision' => 'Activision ID',
        'playstation' => 'PSN ID',
        'xbox' => 'Xbox Gamertag'
    ],

    /**
     *--------------------------------------------------------------------------
     *
     *   Match Settings
     *
     *  'interval' => Schedule Matches At The Next x Minute Interval,
     *  'report' => [
     *      'timeout' => x Minutes Must Pass Before A Team Can Submit A Report
     *  ]
     *
     */

    'match' => [
        'interval' => 10,
        'report' => [
            'timeout' => 15
        ]
    ],

    /**
     *--------------------------------------------------------------------------
     *
     *  Platform View Modifiers
     *
     */

    'platform' => [
        'view' => [
            'cross-console' => 'Cross Console',
            'cross-play' => 'Cross Play',
            'playstation' => 'Playstation',
            'xbox' => 'Xbox',
        ]
    ],

    /**
     *--------------------------------------------------------------------------
     *
     *  Arena Rating Settings
     *
     *  'period' => 15 Matches Required To Receive A Rank
     *
     */

    'rating' => [
        'period' => 5
    ],

    /**
     *--------------------------------------------------------------------------
     *
     *  Game View Modifiers
     *
     */

    'view' => [
        'cod' => 'Call of Duty'
    ]
];
