<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Extension;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class ExtensionLoaderTest extends MultiGenerationTestCase
{
    public function setUp()
    {
        $this->_closure = Phake::mock('Icecave\Pasta\AST\Func\Closure');
        $this->_arguments = array($this->_closure, 1, 2, 3);
        $this->_loader = new ExtensionLoader($this->_arguments);
    }

    public function testLoad()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExtensionFixtures\TestExtension';

        $this->assertFalse($this->_loader->isLoaded($className));

        $extension = $this->_loader->load($className);

        $this->assertTrue($this->_loader->isLoaded($className));
        $this->assertInstanceOf($className, $extension);
        $this->assertSame($this->_arguments, $extension->args);

        // Repeat call gives same instance ...
        $this->assertSame($extension, $this->_loader->load($className));

        // Unless forced reload ...
        $newExtension = $this->_loader->load($className, true);
        $this->assertInstanceOf($className, $newExtension);
        $this->assertNotSame($extension, $newExtension);
    }

    public function testLoadFailure()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExtensionFixtures\NotExistantClass';
        $this->setExpectedException(__NAMESPACE__.'\Exception\InvalidExtensionException', "The extension type '".$className."' does not exist, or does not implement ExtensionInterface.");
        $this->_loader->load($className);
    }

    public function testLoadFailureDoesNotImplementInterface()
    {
        $this->setExpectedException(__NAMESPACE__.'\Exception\InvalidExtensionException', "The extension type '".__CLASS__."' does not exist, or does not implement ExtensionInterface.");
        $this->_loader->load(__CLASS__);
    }

    public function testUnload()
    {
        $className = 'Eloquent\Typhoon\TestFixture\ExtensionFixtures\TestExtension';
        $extension = $this->_loader->load($className);
        $this->assertTrue($this->_loader->isLoaded($className));
        $this->_loader->unload($className);
        $this->assertFalse($this->_loader->isLoaded($className));
    }
}
