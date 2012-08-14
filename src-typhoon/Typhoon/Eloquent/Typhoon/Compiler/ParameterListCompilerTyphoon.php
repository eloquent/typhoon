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

namespace Typhoon\Eloquent\Typhoon\Compiler;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;

class ParameterListCompilerTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 2) {
            throw new UnexpectedArgumentException(2, $arguments[2]);
        }

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Eloquent\Typhoon\Compiler\TyphaxCompiler;
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
                    throw new UnexpectedArgumentValueException('typhaxCompiler', $index, $argument, 'Eloquent\\Typhoon\\Compiler\\TyphaxCompiler|null');
                }
            };
            $check($arguments[0], 0);
        }

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    $check = function($value) {
                        return $value instanceof \Eloquent\Typhax\Renderer\TypeRenderer;
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
                    throw new UnexpectedArgumentValueException('typeRenderer', $index, $argument, 'Eloquent\\Typhax\\Renderer\\TypeRenderer|null');
                }
            };
            $check($arguments[1], 1);
        }
    }

    public function typhaxCompiler(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function typeRenderer(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }

    public function visitParameter(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('parameter', 0, 'Eloquent\\Typhoon\\Parameter\\Parameter');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\Parameter;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('parameter', $index, $argument, 'Eloquent\\Typhoon\\Parameter\\Parameter');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitParameterList(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('parameterList', 0, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Typhoon\Parameter\ParameterList;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('parameterList', $index, $argument, 'Eloquent\\Typhoon\\Parameter\\ParameterList');
            }
        };
        $check($arguments[0], 0);
    }

    public function createCallback(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new MissingArgumentException('parameters', 0, 'string');
            }
            throw new MissingArgumentException('content', 1, 'string');
        } elseif ($argumentCount > 2) {
            throw new UnexpectedArgumentException(2, $arguments[2]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('parameters', $index, $argument, 'string');
            }
        };
        $check($arguments[0], 0);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('content', $index, $argument, 'string');
            }
        };
        $check($arguments[1], 1);
    }

    public function indent(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('content', 0, 'string');
        } elseif ($argumentCount > 2) {
            throw new UnexpectedArgumentException(2, $arguments[2]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('content', $index, $argument, 'string');
            }
        };
        $check($arguments[0], 0);

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return is_integer($value);
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('depth', $index, $argument, 'integer');
                }
            };
            $check($arguments[1], 1);
        }
    }
}
