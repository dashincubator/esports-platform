<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Auto Escaping Proxy Service - Pass Method Calls To Object Provided In Constructor
 *  Wrap Returned Value In Data Component
 *
 *  Value = Bool, Int, etc. Do Nothing Just Return;
 *  Value = Object Check If Instance Matches 'Data::class' If Yes Return, Otherwise Throw Exception;
 *  Value = Array Wrap In 'Data::class' And Return;
 *  Value = String Wrap In 'Data::class' And Return Escaped String Value;
 *
 */

namespace Contracts\View\Extensions;

interface Extension
{

    /**
     *  @param string $method
     *  @param array $args
     *  @return mixed
     */
    public function __call(string $method, array $args);


    /**
     *  Uses 'func_get_args()' To Accept Variable Arguments
     *  @return mixed
     */
    public function __invoke();
}
