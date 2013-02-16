<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Successful;

use Typhoon\TypeCheck as Baz;

class ComplexSetup
{
    public static function qux(
        array $array = array('one', 'two', array('three'), static::FOUR),
        SomeClass $class = null
    ) {
        Baz :: get ( __CLASS__ ) -> qux
        (
            func_get_args ( )
        );
    }

    public function __construct(
        array $array = array('one', 'two', array('three'), static::FOUR),
        SomeClass $class = null
    ) {
        $this -> bar
        =
        Baz :: get ( __CLASS__
            ,
            func_get_args ( )
        );
    }

    public function foo(
        array $array = array('one', 'two', array('three'), static::FOUR),
        SomeClass $class = null
    ) {
        $this -> bar
        ->
        foo
        (
            func_get_args ( )
        );
    }

    public function __doom(
        array $array = array('one', 'two', array('three'), static::FOUR),
        SomeClass $class = null
    ) {
        $this -> bar
        ->
        validateDoom
        (
            func_get_args ( )
        );
    }

    abstract public function splat(
        array $array = array('one', 'two', array('three'), static::FOUR),
        SomeClass $class = null
    );

    private $bar;
}
