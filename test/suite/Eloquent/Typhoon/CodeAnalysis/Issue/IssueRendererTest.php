<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Icecave\Pasta\AST\Type\AccessModifier;
use Phake;

class IssueRendererTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_renderer = new IssueRenderer;

        $this->_classDefinition = new ClassDefinition(
            ClassName::fromString('\foo')
        );
        $this->_methodDefinition = new MethodDefinition(
            'bar',
            false,
            false,
            AccessModifier::PUBLIC_(),
            111,
            'baz'
        );
    }

    public function testVisitInadmissibleMethodCall()
    {
        $issue = new InadmissibleMethodCall(
            $this->_classDefinition,
            $this->_methodDefinition
        );

        $this->assertSame(
            'Type check call should not be present in method bar().',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitMissingConstructorCall()
    {
        $issue = new MissingConstructorCall(
            $this->_classDefinition
        );

        $this->assertSame(
            'Incorrect or missing constructor initialization.',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitMissingMethodCall()
    {
        $issue = new MissingMethodCall(
            $this->_classDefinition,
            $this->_methodDefinition
        );

        $this->assertSame(
            'Incorrect or missing type check call in method bar().',
            $issue->accept($this->_renderer)
        );
    }

    public function testVisitMissingProperty()
    {
        $issue = new MissingProperty(
            $this->_classDefinition
        );

        $this->assertSame(
            'Incorrect or missing property definition.',
            $issue->accept($this->_renderer)
        );
    }
}
