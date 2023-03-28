<?php

namespace App\Commands;

use Exception;

abstract class AbstractCommand
{

    protected $filter;


    protected function booleanResult(...$args)
    {
        if (is_null($this->filter)) {
            return true;
        }

        $result = !$this->filter->hasErrors();

        // Operation Was Successful
        if ($result) {
            $this->filter->writeSuccessMessage(...$args);
        }

        return $result;
    }


    protected function delegate(AbstractCommand $command, array $input = [])
    {
        $response = $command->execute($input);

        if ($response->hasErrors()) {
            $this->filter->error($response->getErrorMessages());
        }

        return $response->getResult();
    }


    public function execute(array $input = []) : Response
    {
        if (!is_null($this->filter)) {
            $input = $this->extractFields($input);

            // Failed Input Validation Requirements
            if (!$this->filter->isValid($input)) {
                return new Response($this->filter->getErrorMessages(), $input, false);
            }
        }

        // Sorts Alphabetically
        ksort($input);

        // Execute Before Adding To Response
        // - Filter Messages Are Set Within 'run()'
        $result = $this->run(...array_values($input));

        return new Response(
            (!is_null($this->filter) ? $this->filter->getErrorMessages() : []),
            $input,
            $result,
            (!is_null($this->filter) ? $this->filter->getSuccessMessages() : [])
        );
    }


    private function extractFields(array $input) : array
    {
        $fields = [];
        $keys = $this->filter->getFields();

        foreach ($keys as $key) {
            $fields[$key] = array_key_exists($key, $input) ? $input[$key] : null;
        }

        return $fields;
    }
}
