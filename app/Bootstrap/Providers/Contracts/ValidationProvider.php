<?php

namespace App\Bootstrap\Providers\Contracts;

use Contracts\Validation\Rules;
use App\Bootstrap\Providers\AbstractProvider;
use System\Validation\Rules\{
    Arr, Between, Boolean, Email, Exists, In, Integer, Max, Min, Numeric,
    Required, Str, Timestamp, Timezone
};

class ValidationProvider extends AbstractProvider
{

    private $rules = [
        'array' => Arr::class,
        'between' => Between::class,
        'bool' => Boolean::class,
        'email' => Email::class,
        'exists' => Exists::class,
        'in' => In::class,
        'int' => Integer::class,
        'max' => Max::class,
        'min' => Min::class,
        'numeric' => Numeric::class,
        'required' => Required::class,
        'string' => Str::class,
        'timestamp' => Timestamp::class,
        'timezone' => Timezone::class
    ];


    public function register() : void
    {
        $this->registerRulesBinding();
    }


    private function registerRulesBinding() : void
    {
        $concrete = $this->container->getAlias(Rules::class);

        $this->container->singleton(Rules::class, function() use ($concrete) {
            $rules = $this->container->resolve($concrete);

            foreach ($this->rules as $name => $classname) {
                $rules->set($name, $this->container->resolve($classname));
            }

            return $rules;
        });
    }
}
