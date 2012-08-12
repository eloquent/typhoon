<?php
namespace Typhoon\Eloquent\Typhoon\ClassMapper;

class ClassDefinitionTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'className'.");
        } elseif ($argumentCount > 3) {
            throw new \InvalidArgumentException("Unexpected argument at index 4.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'className' at index ".$index.".");
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
                    $primaryCheck = function($value) {
                        return is_array($value);
                    };
                    if (!$primaryCheck($value)) {
                        return false;
                    }

                    $keyCheck = function($value) {
                        return is_string($value);
                    };
                    $valueCheck = function($value) {
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
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'usedClasses' at index ".$index.".");
                }
            };
            $check($arguments[2], 2);
        }
    }

    public function className(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function canonicalClassName(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function namespaceName(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function usedClasses(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function classNameResolver(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }
}