<?php declare(strict_types=1);

namespace System\Debug;

use Contracts\Debug\{Renderer as Contract, Formatter};
use Throwable;

class Renderer implements Contract
{

    private const STATUS_CODE = 500;


    private $debug;

    private $formatter;

    private $paths = [];


    public function __construct(
        Formatter $formatter,
        bool $debug,
        string $developmentHtml,
        string $developmentJson,
        string $productionHtml,
        string $productionJson
    ) {
        $this->debug = $debug;
        $this->formatter = $formatter;
        $this->paths = [
            'development' => [
                'html' => $developmentHtml,
                'json' => $developmentJson
            ],
            'production' => [
                'html' => $productionHtml,
                'json' => $productionJson
            ]
        ];
    }


    private function buildResponseContent($e, int $status) : string
    {
        return DebugRendererRequireFile(
            $this->paths[$this->debug ? 'development' : 'production'][$this->getContentType()],
            [
                'errors' => $this->formatter->format($e),
                'status' => self::STATUS_CODE
            ]
        );
    }


    private function getContentType() : string
    {
        $type = $_SERVER['CONTENT_TYPE'] ?? '';

        if ($type === 'application/json') {
            return 'json';
        }

        return 'html';
    }


    public function render(Throwable $e) : void
    {
        $content = $this->buildResponseContent($e, self::STATUS_CODE);
        $headers = [];

        if (!headers_sent()) {
            header('HTTP/1.1 ' . self::STATUS_CODE, true, self::STATUS_CODE);

            switch ($this->getContentType()) {
                case 'json':
                    $headers['Content-Type'] = 'application/json';
                    break;
                default:
                    $headers['Content-Type'] = 'text/html';
            }

            foreach ($headers as $name => $values) {
                $values = (array) $values;

                foreach ($values as $value) {
                    header("{$name}:{$value}", false, self::STATUS_CODE);
                }
            }

            echo $content;

            flush();
        }

        exit(1);
    }
}


/**
 *  Isolate Scope Inside Function
 *
 *  @param string $path File Path
 *  @param array $data Extracted For File Contents
 *  @return string HTML Contents
 */
function DebugRendererRequireFile(string $path, array $data = []) : string
{
    // Erase Existing Buffer Contents
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    ob_start();

    require $path;

    return ob_get_clean();
}
