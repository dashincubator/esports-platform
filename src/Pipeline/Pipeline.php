<?php declare(strict_types=1);

namespace System\Pipeline;

use Closure;
use Contracts\Pipeline\Pipeline as Contract;
use Exception;

class Pipeline implements Contract
{

    // Final Callback To Execute At The End
    private $callback;

    // Value To Send Through The Pipeline
    private $input;

    // Method To Call When Pipeline Stages Are Not Closures
    private $method;

    // List Of Stages To Send '$input' Through
    private $stages = [];


    private function createStage() : Closure
    {
        return function ($stages, $stage) {
            return function ($input) use ($stages, $stage) {
                if ($stage instanceof Closure) {
                    return $stage($input, $stages);
                }
                else {
                    if (is_null($this->method)) {
                        throw new Exception('Pipeline Method Parameter Must Not Be Null');
                    }

                    if (!method_exists($stage, $this->method)) {
                        throw new Exception(get_class($stage) . "::{$this->method} Does Not Exist");
                    }

                    return $stage->{$this->method}($input, $stages);
                }
            };
        };
    }


    public function execute()
    {
        return call_user_func(
            array_reduce(
                array_reverse($this->stages),
                $this->createStage(),
                function ($input) {
                    if (is_null($this->callback)) {
                        return $input;
                    }

                    return ($this->callback)($input);
                }
            ),
            $this->input
        );
    }


    public function send($input) : Contract
    {
        $this->input = $input;

        return $this;
    }


    public function then(Closure $callback) : Contract
    {
        $this->callback = $callback;

        return $this;
    }


    public function through(array $stages, ?string $method = null) : Contract
    {
        $this->method = $method;
        $this->stages = $stages;

        return $this;
    }
}
