<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Pass Value Through Series Of Stages ( Callables Or Object Methods )
 *
 */

namespace Contracts\Pipeline;

use Closure;

interface Pipeline
{

    /**
     *  Execute Pipeline
     *
     *  @return mixed Value Returned By Pipeline
     *  @throws Exception Thrown If Value Could Not Be Sent Through The Pipeline
     */
    public function execute();


    /**
     *  Define Value To Send Through The Pipeline
     *
     *  @param mixed $intput The Value Being Sent Through Pipeline
     *  @return Pipeline For Method Chaining
     */
    public function send($input) : Pipeline;


    /**
     *  Define Value To Send Through The Pipeline
     *
     *  @param callable $callback The Callbak To Run After The Pipeline. The
     *  Callable Must Accept The Result Of The Pipeline As A Parameter.
     *  @return Pipeline For Method Chaining
     */
    public function then(Closure $callback) : Pipeline;


    /**
     *  Set List Of Stages In The Pipeline
     *
     *  @param Closure|array $stages The List Of Stages In The Pipeline
     *  @param string|null $method Sets The Method Name To Call If Stages Are A
     *  List Of Object Or Class Names
     *  @return Pipeline For Method Chaining
     */
    public function through(array $stages, ?string $method = null) : Pipeline;
}
