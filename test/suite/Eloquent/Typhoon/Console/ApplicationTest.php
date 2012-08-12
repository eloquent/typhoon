<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Console;

use PHPUnit_Framework_TestCase;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $application = new Application;

        $this->assertSame('Typhoon', $application->getName());
        $this->assertSame('DEV', $application->getVersion());
        $this->assertInstanceOf(
            __NAMESPACE__.'\Command\GenerateValidatorsCommand',
            $application->get('generate:validators')
        );
    }
}
