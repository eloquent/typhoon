<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis\Issue;

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;

class IssueSeverityTest extends MultiGenerationTestCase
{
    public function testEnumeration()
    {
        $this->assertSame(array(
            'ERROR' => IssueSeverity::ERROR(),
            'WARNING' => IssueSeverity::WARNING(),
        ), IssueSeverity::multitonInstances());
    }
}
