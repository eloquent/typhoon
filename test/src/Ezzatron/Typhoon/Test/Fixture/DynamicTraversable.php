<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Test\Fixture;

use Ezzatron\Typhoon\Type\Dynamic\DynamicType;
use Ezzatron\Typhoon\Type\Traversable\TraversableType;

interface DynamicTraversable extends DynamicType, TraversableType {}