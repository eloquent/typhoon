<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\TestFixture\GeneratorExamples;

use Iterator;
use stdClass;

class TypicalClass
{
    public function __construct()
    {
    }

    public function __toString()
    {
    }

    protected function undocumentedMethod()
    {
    }

    /**
     * @param boolean $boolean
     * @param float $float
     * @param integer $integer
     * @param mixed $mixed
     * @param numeric $numeric
     * @param string $string
     */
    private function simpleTypes(
        $boolean,
        $float,
        $integer,
        $mixed,
        $numeric,
        $string
    ) {
    }

    /**
     * @param object $foo
     * @param stdClass $bar
     * @param array<stdClass> $baz
     */
    private function objectType($foo, stdClass $bar, array $baz)
    {
    }

    /**
     * @param resource $foo
     * @param resource {ofType: stream} $bar
     */
    private function resourceType($foo, $bar)
    {
    }

    /**
     * @param stream $foo
     * @param stream {readable: true} $bar
     * @param stream {readable: false} $baz
     * @param stream {writable: true} $qux
     * @param stream {writable: false} $doom
     * @param stream {readable: true, writable: true} $splat
     * @param stream {readable: false, writable: true} $ping
     */
    private function streamType(
        $foo,
        $bar,
        $baz,
        $qux,
        $doom,
        $splat,
        $ping
    ) {
    }

    /**
     * @param stringable $foo
     */
    private function stringableType($foo)
    {
    }

    /**
     * @param array<callable> $foo
     */
    private function callableType(array $foo)
    {
    }

    /**
     * @param array<null> $foo
     */
    private function nullType(array $foo)
    {
    }

    /**
     * @param stdClass+Iterator $foo
     * @param object+stringable $bar
     * @param stringable+object $baz
     * @param mixed+mixed $qux
     */
    private function andType(Iterator $foo, $bar, $baz, $qux)
    {
    }

    /**
     * @param integer|string $foo
     * @param integer|stringable $bar
     * @param stringable|integer $baz
     * @param mixed|mixed $qux
     */
    private function orType($foo, $bar, $baz, $qux)
    {
    }

    /**
     * @param tuple<integer,string> $foo
     * @param tuple<integer,stringable,stringable> $bar
     */
    private function tupleType(array $foo, array $bar)
    {
    }

    /**
     * @param array $foo
     * @param array<array> $bar
     * @param array<integer,string> $baz
     * @param array<integer,stringable> $qux
     * @param array<stringable,integer> $doom
     */
    private function traversableArray(
        array $foo,
        array $bar,
        array $baz,
        array $qux,
        array $doom
    ) {
    }

    /**
     * @param Iterator<string> $foo
     * @param Iterator<integer,string> $bar
     * @param Iterator<integer,stringable> $baz
     * @param Iterator<stringable,integer> $qux
     */
    private function traversableObject(
        Iterator $foo,
        Iterator $bar,
        Iterator $baz,
        Iterator $qux
    ) {
    }

    /**
     * @param mixed<string> $foo
     * @param mixed<integer,string> $bar
     * @param mixed<integer,stringable> $baz
     * @param mixed<stringable,integer> $qux
     */
    private function traversableMixed($foo, $bar, $baz, $qux)
    {
    }
}
