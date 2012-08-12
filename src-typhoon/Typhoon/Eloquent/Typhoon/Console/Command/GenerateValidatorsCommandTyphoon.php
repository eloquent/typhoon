<?php
namespace Typhoon\Eloquent\Typhoon\Console\Command;

class GenerateValidatorsCommandTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 3) {
            throw new \InvalidArgumentException("Unexpected argument at index 4.");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'generator' at index ".$index.".");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'deploymentManager' at index ".$index.".");
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'isolator' at index ".$index.".");
                }
            };
            $check($arguments[2], 2);
        }
    }

    public function generator(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function deploymentManager(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function configure(array $arguments)
    {
        $argumentCount = count($arguments);

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return true;
                };
                if (!$check($argument)) {
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'undefined' at index ".$index.".");
                }
            };
            for ($i = 0; $i < $argumentCount; $i ++) {
                $check($arguments[$i], $i);
            }
        }
    }

    public function execute(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'input'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'output'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Symfony\Component\Console\Input\InputInterface;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'input' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Symfony\Component\Console\Output\OutputInterface;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'output' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }
}
