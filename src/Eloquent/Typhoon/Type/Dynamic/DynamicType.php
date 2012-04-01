<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Dynamic;

use Eloquent\Typhoon\Attribute\AttributeHolder;
use Eloquent\Typhoon\Type\NamedType;

interface DynamicType extends NamedType, AttributeHolder {}
