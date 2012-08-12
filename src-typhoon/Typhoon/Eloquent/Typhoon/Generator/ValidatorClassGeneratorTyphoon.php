<?php
namespace Typhoon\Eloquent\Typhoon\Generator;

class ValidatorClassGeneratorTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Eloquent\Typhoon\Parser\ParameterListParser;
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'parser' at index ".$index.".");
                }
            };
            $check($arguments[0], 0);
        }

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Eloquent\Typhoon\Compiler\ParameterListCompiler;
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'compiler' at index ".$index.".");
                }
            };
            $check($arguments[1], 1);
        }
    }

    public function parser(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function compiler(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function generate(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'classDefinition'.");
        } elseif ($argumentCount > 3) {
            throw new \InvalidArgumentException("Unexpected argument at index 4.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'classDefinition' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return is_string($value);
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'namespaceName' at index ".$index.".");
                }
            };
            $check($arguments[1], 1);
        }

        if ($argumentCount > 2) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return is_string($value);
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'className' at index ".$index.".");
                }
            };
            $check($arguments[2], 2);
        }
    }

    public function methods(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'classDefinition'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'classDefinition' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function generateMethod(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'method'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'classDefinition'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \ReflectionMethod;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'method' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'classDefinition' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }

    public function parameterList(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'method'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'classDefinition'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \ReflectionMethod;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'method' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'classDefinition' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }

    public function classNameResolver(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'classDefinition'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'classDefinition' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function reflectionResolver(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'classDefinition'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\ClassMapper\ClassDefinition;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'classDefinition' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function indent(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'content'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'content' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return is_integer($value);
                };
                if (!$check($argument)) {
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'depth' at index ".$index.".");
                }
            };
            $check($arguments[1], 1);
        }
    }
}