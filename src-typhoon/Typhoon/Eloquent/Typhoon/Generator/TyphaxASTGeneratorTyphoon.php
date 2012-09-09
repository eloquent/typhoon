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

namespace Typhoon\Eloquent\Typhoon\Generator;

use Typhoon\Exception\MissingArgumentException;
use Typhoon\Exception\UnexpectedArgumentException;
use Typhoon\Exception\UnexpectedArgumentValueException;
use Typhoon\Validator;

class TyphaxASTGeneratorTyphoon extends Validator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return true;
                };
                if (!$check($argument)) {
                    throw new UnexpectedArgumentValueException('valueIdentifier', $index, $argument, 'mixed');
                }
            };
            $check($arguments[0], 0);
        }
    }

    public function visitAndType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitArrayType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitBooleanType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitCallableType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitFloatType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitIntegerType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitMixedType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitNullType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitNumericType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitObjectType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitOrType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitResourceType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitStreamType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitStringType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitStringableType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitTraversableType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function visitTupleType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new MissingArgumentException('type', 0, 'mixed');
        } elseif ($argumentCount > 1) {
            throw new UnexpectedArgumentException(1, $arguments[1]);
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return true;
            };
            if (!$check($argument)) {
                throw new UnexpectedArgumentValueException('type', $index, $argument, 'mixed');
            }
        };
        $check($arguments[0], 0);
    }

    public function valueExpression(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new UnexpectedArgumentException(0, $arguments[0]);
        }
    }
}