<?php

echo json_encode([
    'error' => [
        'code' => $data['status'],
        'message' => 'The server was unable to complete your request. If the problem persists please contact site administrators.'
    ]
]);
