<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Step 4: Dispatch
 *
 */

$configuration = require __DIR__ . '/../../config/bootstrap/http.php';

(require __DIR__ . '/../start.php')($configuration)
    ->dispatch($configuration['stages'])
    ->send();
