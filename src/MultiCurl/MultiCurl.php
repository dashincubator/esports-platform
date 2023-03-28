<?php declare(strict_types=1);

namespace System\MultiCurl;

use Contracts\Http\{Factory, ResponseCollection};
use Contracts\MultiCurl\MultiCurl as Contract;
use Contracts\MultiCurl\Parser\{Json, Xml};
use Exception;

class MultiCurl implements Contract
{

    private $factory;

    private $handlers = [];

    private $parser;


    public function __construct(Factory $factory, Json $json, Xml $xml)
    {
        $this->factory = $factory;
        $this->parser = compact('json', 'xml');
    }


    private function buildCollection(array $headers, array $status) : ResponseCollection
    {
        $collection = $this->factory->createResponseCollection();

        foreach ($this->handlers as $key => $ch) {
            $content  = curl_multi_getcontent($ch);
            $response = $this->factory->createResponse('', ($headers[$key] ?? []), ($status[$key] ?? 200));

            switch($type = $response->getHeaders()->get('Content-Type', '')) {
                case (mb_strpos($type, 'application/json') !== false):
                    $content = $this->parser['json']->toJson($content);
                    break;

                case (mb_strpos($type, 'text/xml') !== false):
                    $content = $this->parser['xml']->toJson($content);
                    break;
            }

            $response->setContent($content);

            $collection->set($key, $response);
        }

        $this->clear();

        return $collection;
    }


    public function clear() : void
    {
        $this->handlers = [];
    }


    public function execute() : ResponseCollection
    {
        $headers = [];
        $master  = curl_multi_init();
        $status  = [];

        foreach ($this->handlers as $key => $ch) {
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headers, $key, &$status) {
                $split = explode(':', $header, 2);

                if (count($split) === 2) {
                    $headers[$key][strtolower(trim($split[0]))][] = trim($split[1]);
                }

                $status[$key] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                return mb_strlen($header);
            });

            curl_multi_add_handle($master, $ch);
        }

        do {
            curl_multi_exec($master, $running);
        } while($running > 0);

        $collection = $this->buildCollection($headers, $status);

        curl_multi_close($master);

        return $collection;
    }


    public function set(string $key, string $url, array $options = []) : void
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_RETURNTRANSFER => true
        ] + $options);

        $this->handlers[$key] = $ch;
    }
}
