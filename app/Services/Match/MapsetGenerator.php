<?php

namespace App\Services\Match;

class MapsetGenerator
{

    public function generate(int $bestOf, array $buckets) : array
    {
        $i = 0;
        $keys = array_keys($buckets);
        $mapset = [];
        $total = array_sum(array_map('count', $buckets));

        shuffle($keys);

        while(count($mapset) < $bestOf) {
            if (!isset($keys[$i])) {
                $i = 0;
            }

            $mapset[] = [
                ($key = $keys[$i]),
                $buckets[$key][array_rand($buckets[$key])]
            ];

            if ($total > $bestOf || $total > count($mapset)) {
                $mapset = array_unique($mapset, SORT_REGULAR);
            }

            $i++;
        }

        shuffle($mapset);

        return $mapset;
    }
}
