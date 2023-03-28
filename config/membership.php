<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Membership Purchase Options
 *
 */

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  Purchase Options
     *
     *  'price' => In USD ( Site Currency )
     *
     */

    'options' => [
        [
            'days' => 31,
            'price' => 10,
            'svg' => 'calendar-one',
            'text' => '1 Month'
        ],
        [
            'days' => 183,
            'price' => 57,
            'svg' => 'calendar-six',
            'text' => '6 Months'
        ],
        [
            'days' => 365,
            'price' => 108,
            'svg' => 'calendar-twelve',
            'text' => '1 Year'
        ]
    ],

    'payout' => [
        'options' => [
            0 => 'Do Not Add Membership',
            7 => '1 Week',
            31 => '1 Month',
            93 => '3 Months'
        ]
    ]
];
