<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ParameterListMerge\Exception;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue\MissingConstructorCall;
use Eloquent\Typhoon\CodeAnalysis\Issue\MethodIssue\MissingMethodCall;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class ParameterListMergeExceptionTest extends MultiGenerationTestCase
{
    protected function methodDefinitionFixture($name)
    {
        $methodDefinition = Phake::mock('Eloquent\Typhoon\ClassMapper\MethodDefinition');
        Phake::when($methodDefinition)
            ->name(Phake::anyParameters())
            ->thenReturn($name)
        ;

        return $methodDefinition;
    }

    public function testExceptionClassRelated()
    {
        $issue = new MissingConstructorCall(
            new ClassDefinition(ClassName::fromString('\foo'))
        );
        $previous = Phake::mock('Exception');
        $exception = new ParameterListMergeException(
            $issue,
            $previous
        );

        $this->assertSame(
            'Error in class \foo: Incorrect or missing constructor initialization.',
            $exception->getMessage()
        );
        $this->assertSame($issue, $exception->issue());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionMethodRelated()
    {
        $issue = new MissingMethodCall(
            new ClassDefinition(ClassName::fromString('\foo')),
            $this->methodDefinitionFixture('bar')
        );
        $previous = Phake::mock('Exception');
        $exception = new ParameterListMergeException(
            $issue,
            $previous
        );

        $this->assertSame(
            'Error in method \foo::bar(): Incorrect or missing type check call.',
            $exception->getMessage()
        );
        $this->assertSame($issue, $exception->issue());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
