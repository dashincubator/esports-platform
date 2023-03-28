<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  IoC Container
 *
 */

namespace Contracts\Container;

use Closure;

interface Container
{

    /**
     *  Add Contextual Binding To Container
     *
     *  @param string $concrete Class To Provide Contextual Binding To
     *  @param string $abstract Parameter To Look For
     *  @param closure|object|string Value Provided To Instantiated $concrete Class
     */
    public function addContextualBinding(string $concrete, string $abstract, $implementation) : void;


    /**
     *  Bind Closure ( Factory ), Object ( Class Instance ), Or Concrete Class
     *  Name Against '$abstract' Alias
     *
     *  @param string $abstract Alias/Key To Bind Concrete Value
     *  @param mixed $concrete Factory, Class Instance, Or Concrete Class
     *  @param array $parameters List Of Parameters To Inject Into Concrete Class On Resolution
     *  @param bool $resolveAsSingleton Should Binding Be Resolved As Singleton
     */
    public function bind(string $abstract, $concrete = null, array $parameters = [], bool $resolveAsSingleton = false) : void;


    /**
     *  Return Alias Of $abstract Class If Exists, Otherwise Return $abstract
     *
     *  @param string $abstract
     *  @return string
     */
    public function getAlias(string $abstract) : string;


    /**
     *  Does Container Have '$abstract' Binding?
     *
     *  @param string $abstract @see '$this->bind()' Parameter Comment
     *  @return bool True If Binding Exists, Otherwise False
     */
    public function has(string $abstract) : bool;


    /**
     *  Resolve Binding Associated With '$abstract' Key. If '$abstract' Does Not
     *  Exist Try Resolving '$abstract' If Class Exists.
     *
     *  - Method Splits Objects/Primitives Provided By $with
     *  - If Objects Are Provided They Are Given Priority In Contextual Binding Process
     *  - Primitives Are Still Passed In Array Index Order Of $with
     *
     *  @param string $abstract @see '$this->bind()' Parameter Comment
     *  @param array $with List Of Parameters To Inject Into Binding On Resolution
     *  @return mixed Value Returned From Resolved Binding
     *  @throws Exception If There Is No Binding/Class To Resolve
     */
    public function resolve(string $abstract, array $with = []);


    /**
     *  Bind Concrete Value As Singleton; Default Container Uses '$this->bind()'
     *  With '$resolveAsSingleton' Set To True
     *
     *  @see '$this->bind()' Comments
     */
    public function singleton(string $abstract, $concrete = null, array $parameters = []) : void;


    /**
     *  Unbind Each '$abstract' Key Provided By '$abstracts'
     *
     *  @param array|string $abstracts Key(s) To Unbind
     */
    public function unbind($abstracts) : void;


    /**
     *  Begins Chain Of Contextual Binding Using ContextualBindingBuilder::class
     *
     *  @param string $abstracts
     *  @return ContextualBindingBuilder::class
     */
    public function when(string ...$abstracts) : ContextualBindingBuilder;
}
