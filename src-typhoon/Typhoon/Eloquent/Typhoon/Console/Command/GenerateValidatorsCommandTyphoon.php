<?php
namespace Typhoon\Eloquent\Typhoon\Console\Command;

class GenerateValidatorsCommandTyphoon
{
    public function __construct(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function configure(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
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

    public function includeLoaders(array $arguments)
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

    public function generateValidators(array $arguments)
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

    public function buildClassMap(array $arguments)
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

    public function PSRPath(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'namespaceName'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'className'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'namespaceName' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'className' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }
}
