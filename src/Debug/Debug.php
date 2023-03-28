<?php declare(strict_types=1);

namespace System\Debug;

use Contracts\Debug\{Debug as Contract, Logger, Renderer};
use ErrorException;
use Exception;
use ParseError;
use Throwable;
use TypeError;

class Debug implements Contract
{

    private const FATAL_ERRORS = [
        E_COMPILE_ERROR,
        E_CORE_ERROR,
        E_ERROR,
        E_NOTICE,
        E_PARSE,
        E_RECOVERABLE_ERROR,
        E_WARNING
    ];


    private $logger;

    private $renderer;


    public function __construct(Logger $logger, Renderer $renderer)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
    }


    public function handleError(int $level, string $message, string $file = '', int $line = 0) : void
    {
        if (error_reporting() && $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }


    public function handleException(Throwable $e) : void
    {
        if ($e instanceof ParseError) {
            $severity = E_PARSE;
        }
        elseif ($e instanceof TypeError) {
            $severity = E_RECOVERABLE_ERROR;
        }
        else {
            $severity = E_ERROR;
        }

        if (!$e instanceof Exception) {
            $e = new ErrorException($e->getMessage(), $e->getCode(), $severity, $e->getFile(), $e->getLine(), $e->getPrevious());
        }

        $this->logger->log($e);

        if (method_exists($e, 'getSeverity') && !in_array($e->getSeverity(), self::FATAL_ERRORS)) {
            return;
        }

        $this->renderer->render($e);
    }


    public function handleShutdown() : void
    {
        $e = error_get_last();

        if (is_null($e)) {
            return;
        }

        $this->handleException(new ErrorException($e['message'], 0, $e['type'], $e['file'], $e['line']));
    }


    public function register() : void
    {
        // Report All Errors
        error_reporting(-1);

        // Handlers Will Deal With Logging/Rendering
        ini_set('display_errors', 'Off');

        // Register Handlers
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }
}
