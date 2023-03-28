<?php
    $layout('@layouts/ladder');

    $prizes = [];

    if ($data['ladder.entryFee'] && $data['ladder.entryFeePrizes'] && ($data['ladder.totalLockedTeams'] > 0 || $data['ladder.prizePool'] > 0)) {
        $replace = [];
        $total = $data['ladder.prizePool'] + ($data['ladder.totalLockedMembers'] * $data['ladder.entryFee']);

        foreach ($data['ladder.entryFeePrizes'] as $key => $decimal) {
            $replace['{' . $key . '}'] = '$' . number_format($total * $decimal);
        }
    }
    else {
        $replace = [];

        foreach ($data['ladder.entryFeePrizes'] as $key => $decimal) {
            $replace['{' . $key . '}'] = '';
        }
    }

    foreach ($data['ladder.prizes'] as $place => $items) {
        $prizes[$place] = [];

        foreach ($items as $item) {
            $prizes[$place][] = str_replace(array_keys($replace), array_values($replace), $item);
        }
    }

    if (!count($prizes)) {
        $prizes = $data['ladder.prizes'];
    }

    echo $include('@components/event/prizes', [
        'prizes' => $prizes,
        'subtitle' => $data['ladder.prizesAdjusted']
            ? 'Final prize list will be adjusted based on the total number of ranked teams'
            : 'Prizes are guaranteed once 2 teams receive ranks'
    ]);
?>
