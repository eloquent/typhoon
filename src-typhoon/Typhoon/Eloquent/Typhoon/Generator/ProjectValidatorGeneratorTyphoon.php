<?php
namespace Typhoon\Eloquent\Typhoon\Generator;

class ProjectValidatorGeneratorTyphoon
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
                        return $value instanceof \Eloquent\Typhoon\ClassMapper\ClassMapper;
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'classMapper' at index ".$index.".");
                }
            };
            $check($arguments[0], 0);
        }

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Eloquent\Typhoon\Generator\ValidatorClassGenerator;
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'classGenerator' at index ".$index.".");
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

    public function classMapper(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function classGenerator(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function generate(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'outputPath'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'classPaths'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'outputPath' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                $primaryCheck = function($value) {
                    return is_array($value);
                };
                if (!$primaryCheck($value)) {
                    return false;
                }

                $keyCheck = function($value) {
                    return true;
                };
                $valueCheck = function($value) {
                    return is_string($value);
                };
                foreach ($value as $key => $subValue) {
                    if (!$keyCheck($key)) {
                        return false;
                    }
                    if (!$valueCheck($subValue)) {
                        return false;
                    }
                }

                return true;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'classPaths' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }

    public function buildClassMap(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'classPaths'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                $primaryCheck = function($value) {
                    return is_array($value);
                };
                if (!$primaryCheck($value)) {
                    return false;
                }

                $keyCheck = function($value) {
                    return true;
                };
                $valueCheck = function($value) {
                    return is_string($value);
                };
                foreach ($value as $key => $subValue) {
                    if (!$keyCheck($key)) {
                        return false;
                    }
                    if (!$valueCheck($subValue)) {
                        return false;
                    }
                }

                return true;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'classPaths' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
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
