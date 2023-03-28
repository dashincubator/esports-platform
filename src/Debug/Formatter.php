<?php declare(strict_types=1);

namespace System\Debug;

use Contracts\Debug\Formatter as Contract;
use Throwable;

class Formatter implements Contract
{

    public function format(Throwable $e) : array
    {
        $formatted = [];

        do {
            $formatted[] = [
                'class'    => get_class($e),
                'code'     => $e->getCode(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
                'message'  => $e->getMessage(),
                'severity' => (method_exists($e, 'getSeverity') ? $e->getSeverity() : 0),
                'time'     => time(),
                'trace'    => array_filter(array_map(function($trace) {
                    if (array_key_exists('line', $trace)) {
                        return [
                            'file' => $trace['file'],
                            'line' => $trace['line']
                        ];
                    }

                    return [];
                }, $e->getTrace()))
            ];
        } while($e = $e->getPrevious());

        return $formatted;
    }
}
