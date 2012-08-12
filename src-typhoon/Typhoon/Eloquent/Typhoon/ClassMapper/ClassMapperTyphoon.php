<?php
namespace Typhoon\Eloquent\Typhoon\ClassMapper;

class ClassMapperTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return $value instanceof \Icecave\Isolator\Isolator;
                };
                if (!$check($argument)) {
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'isolator' at index ".$index.".");
                }
            };
            $check($arguments[0], 0);
        }
    }

    public function classesByDirectory(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'directoryPath'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'directoryPath' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function classesByFile(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'filePath'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'filePath' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function classesBySource(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'source'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'source' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function parseNamespaceName(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'tokens'.");
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
                    $check = function($value) {
                        return is_string($value);
                    };
                    if ($check($value)) {
                        return true;
                    }

                    $check = function($value) {
                        return is_array($value);
                    };
                    if ($check($value)) {
                        return true;
                    }

                    return false;
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
                throw new \InvalidArgumentException("Unexpected argument for parameter 'tokens' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function parseUsedClass(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'tokens'.");
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
                    $check = function($value) {
                        return is_string($value);
                    };
                    if ($check($value)) {
                        return true;
                    }

                    $check = function($value) {
                        return is_array($value);
                    };
                    if ($check($value)) {
                        return true;
                    }

                    return false;
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
                throw new \InvalidArgumentException("Unexpected argument for parameter 'tokens' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function parseClassName(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'tokens'.");
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
                    $check = function($value) {
                        return is_string($value);
                    };
                    if ($check($value)) {
                        return true;
                    }

                    $check = function($value) {
                        return is_array($value);
                    };
                    if ($check($value)) {
                        return true;
                    }

                    return false;
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
                throw new \InvalidArgumentException("Unexpected argument for parameter 'tokens' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function sourceTokens(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'source'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'source' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function fileIterator(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'directoryPath'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'directoryPath' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }
}
