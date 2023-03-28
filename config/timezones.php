<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Timezone List Supported By Application
 *
 *  'United State' => Used For Select Field Group Options Element
 *  'America/Indiana/Indianapolis' => Timezone Identifier For 'date_default_timezone_set'
 *  'Eastern' => User Scan-able Text
 *
 */

$list = [
    'United States' => [
        "America/Indiana/Indianapolis" => "Eastern",
        "America/Chicago" => "Central",
        "America/Denver" => "Mountain",
        "America/Los_Angeles" => "Pacific",
        "America/Yakutat" => "Alaska",
        "Pacific/Honolulu" => "Hawaii-Aleutians",
        "Pacific/Pago_Pago" => "Samoa",
        "Pacific/Guam" => "Chamorro"
    ],
    "Canada" => [
        "America/St_Johns" => "Newfoundland",
        "America/Halifax" => "Atlantic",
        "America/Creston" => "Mountain",
        "America/Dawson" => "Pacific"
    ],
    "Europe" => [
        "America/Scoresbysund" => "Azores",
        "Europe/Jersey" => "Irish",
        "Europe/London" => "Greenwhich Mean",
        "Europe/Berlin" => "Central European",
        "Europe/Budapest" => "Western European",
        "Europe/Helsinki" => "Eastern European",
        "Europe/Volgograd" => "Further Eastern European",
        "Europe/Moscow" => "Moscow"
    ]
];

$identifiers = array_keys(array_merge_recursive(...array_values($list)));

return compact('identifiers', 'list');
