<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Console;

use Eloquent\Typhoon\Configuration\ConfigurationReader;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    /**
     * @param ConfigurationReader|null $configurationReader
     */
    public function __construct(
        ConfigurationReader $configurationReader = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $configurationReader) {
            $configurationReader = new ConfigurationReader;
        }

        $this->configurationReader = $configurationReader;

        parent::__construct('Typhoon', '0.9.0');

        $this->add(new Command\GenerateCommand);
        $this->add(new Command\CheckCommand);
    }

    /**
     * @return ConfigurationReader
     */
    public function configurationReader()
    {
        $this->typeCheck->configurationReader(func_get_args());

        return $this->configurationReader;
    }

    private $configurationReader;
    private $typeCheck;
}
