<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Test\Fixture;

use Eloquent\Typhoon\Type\Dynamic\DynamicType;
use Eloquent\Typhoon\Type\SubTyped\SubTypedType;

interface DynamicSubTypedType extends DynamicType, SubTypedType {}
