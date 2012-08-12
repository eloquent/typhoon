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

use Symfony\Component\Console\Application as SymfonyApplication;
use Typhoon\Typhoon;

class Application extends SymfonyApplication
{
    public function __construct()
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        parent::__construct('Typhoon', 'DEV');

        $this->add(new Command\GenerateValidatorsCommand);
    }

    private $typhoon;
}
