<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Step 4: Dispatch
 *
 */

$configuration = require __DIR__ . '/../../config/bootstrap/jobs.php';
$configuration['jobs']['dispatch'] = array_merge(
    ($configuration['jobs'][$interval] ?? []),
    ($configuration['jobs']['@always'] ?? [])
);

(require __DIR__ . '/../start.php')($configuration)
    ->dispatch($configuration['stages']);
