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

use Eloquent\Typhoon\Configuration\ConfigurationLoader;
use Symfony\Component\Console\Application as SymfonyApplication;
use Typhoon\Typhoon;

class Application extends SymfonyApplication
{
    /**
     * @param ConfigurationLoader|null $configurationLoader
     */
    public function __construct(
        ConfigurationLoader $configurationLoader = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $configurationLoader) {
            $configurationLoader = new ConfigurationLoader;
        }

        $this->configurationLoader = $configurationLoader;

        parent::__construct('Typhoon', 'DEV');

        $this->add(new Command\GenerateValidatorsCommand);
    }

    /**
     * @return ConfigurationLoader
     */
    public function configurationLoader()
    {
        $this->typhoon->configurationLoader(func_get_args());

        return $this->configurationLoader;
    }

    private $configurationLoader;
    private $typhoon;
}
