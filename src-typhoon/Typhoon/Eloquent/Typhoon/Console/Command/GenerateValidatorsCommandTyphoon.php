<?php

/*
 * This file was generated by [Typhoon](https://github.com/eloquent/typhoon).
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the
 * [LICENSE](https://raw.github.com/eloquent/typhoon/master/LICENSE)
 * file that is distributed with Typhoon.
 */

namespace Typhoon\Eloquent\Typhoon\Console\Command;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;

class GenerateValidatorsCommandTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 3) {
            throw new UnexpectedArgumentException(3, $arguments[3]);
        }

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Eloquent\Typhoon\Generator\ProjectValidatorGenerator;
                    };
                    if ($check($value)) {
                        return true;
                    }

                    $check = function($value) {
                        return $value === null;
                    };
                    if ($check($value)) {
                        return true;
                    }

                    return false;
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('generator', $index, $argument, 'Eloquent\\Typhoon\\Generator\\ProjectValidatorGenerator|null');
                }
            };
            $check($arguments[0], 0);
        }

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Eloquent\Typhoon\Deployment\DeploymentManager;
                    };
                    if ($check($value)) {
                        return true;
                    }

                    $check = function($value) {
                        return $value === null;
                    };
                    if ($check($value)) {
                        return true;
                    }

                    return false;
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('deploymentManager', $index, $argument, 'Eloquent\\Typhoon\\Deployment\\DeploymentManager|null');
                }
            };
            $check($arguments[1], 1);
        }

        if ($argumentCount > 2) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Icecave\Isolator\Isolator;
                    };
                    if ($check($value)) {
                        return true;
                    }

                    $check = function($value) {
                        return $value === null;
                    };
                    if ($check($value)) {
                        return true;
                    }

                    return false;
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('isolator', $index, $argument, 'Icecave\\Isolator\\Isolator|null');
                }
            };
            $check($arguments[2], 2);
        }
    }

    public function generator(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function deploymentManager(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function configure(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function execute(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new MissingArgumentException('input', 0, 'Symfony\\Component\\Console\\Input\\InputInterface');
            }
            throw new MissingArgumentException('output', 1, 'Symfony\\Component\\Console\\Output\\OutputInterface');
        } elseif ($argumentCount > 2) {
            throw new UnexpectedArgumentException(2, $arguments[2]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Symfony\Component\Console\Input\InputInterface;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('input', $index, $argument, 'Symfony\\Component\\Console\\Input\\InputInterface');
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Symfony\Component\Console\Output\OutputInterface;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('output', $index, $argument, 'Symfony\\Component\\Console\\Output\\OutputInterface');
            }
        };
        $check($arguments[1], 1);
    }
}
