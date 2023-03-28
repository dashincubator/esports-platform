<?php

namespace App\Http\Responders;

use Contracts\Http\{Factory, Response};
use Contracts\Support\Arrayable;
use Exception;

class Json
{

    private $factory;


    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }


    public function render(array $data, int $status = 200) : Response
    {
        $data = json_encode($this->toArray($data));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Responder Failed To Encode Content: '" . json_last_error() . "'");
        }

        return $this->factory->createResponse($data, ['Content-Type' => 'application/json'], $status);
    }


    public function render404(array $data = []) : Response
    {
        return $this->render($data, 404);
    }


    protected function toArray(array $data) : array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->toArray($value);
            }
            elseif ($value instanceof Arrayable) {
                $value = $value->toArray();
            }

            $data[$key] = $value;
        }

        return $data;
    }
}
