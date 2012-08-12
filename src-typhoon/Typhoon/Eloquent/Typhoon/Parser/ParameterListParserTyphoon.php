<?php
namespace Typhoon\Eloquent\Typhoon\Parser;

class ParameterListParserTyphoon
{
    public function validateConstructor(array $arguments)
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

    public function typhaxParser(array $arguments)
    {
        if (count($arguments) > 0) {
            throw new \InvalidArgumentException("Unexpected argument at index 1.");
        }
    }

    public function parseBlockComment(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'blockComment'.");
        } elseif ($argumentCount > 2) {
            throw new \InvalidArgumentException("Unexpected argument at index 3.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'blockComment' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);

        if ($argumentCount > 1) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return $value instanceof \Eloquent\Blox\DocumentationBlockParser;
                };
                if (!$check($argument)) {
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'documentationParser' at index ".$index.".");
                }
            };
            $check($arguments[1], 1);
        }
    }

    public function visitDocumentationBlock(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'documentationBlock'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Blox\AST\DocumentationBlock;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'documentationBlock' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function visitDocumentationTag(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'documentationTag'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
        }

        $check = function($argument, $index) {
            $check = function($value) {
                return $value instanceof \Eloquent\Blox\AST\DocumentationTag;
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'documentationTag' at index ".$index.".");
            }
        };
        $check($arguments[0], 0);
    }

    public function parseType(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'content'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'position'.");
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

        $check = function($argument, $index) {
            $check = function($value) {
                return is_integer($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'position' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }

    public function parseName(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'content'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'position'.");
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

        $check = function($argument, $index) {
            $check = function($value) {
                return is_integer($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'position' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }

    public function parseDescription(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'content'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'position'.");
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

        $check = function($argument, $index) {
            $check = function($value) {
                return is_integer($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'position' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);
    }

    public function parseOptional(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 1) {
            throw new \InvalidArgumentException("Missing argument for parameter 'content'.");
        } elseif ($argumentCount > 1) {
            throw new \InvalidArgumentException("Unexpected argument at index 2.");
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
    }

    public function parseContent(array $arguments)
    {
        $argumentCount = count($arguments);
        if ($argumentCount < 5) {
            if ($argumentCount < 1) {
                throw new \InvalidArgumentException("Missing argument for parameter 'content'.");
            }
            if ($argumentCount < 2) {
                throw new \InvalidArgumentException("Missing argument for parameter 'position'.");
            }
            if ($argumentCount < 3) {
                throw new \InvalidArgumentException("Missing argument for parameter 'pattern'.");
            }
            if ($argumentCount < 4) {
                throw new \InvalidArgumentException("Missing argument for parameter 'optional'.");
            }
            throw new \InvalidArgumentException("Missing argument for parameter 'type'.");
        } elseif ($argumentCount > 5) {
            throw new \InvalidArgumentException("Unexpected argument at index 6.");
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

        $check = function($argument, $index) {
            $check = function($value) {
                return is_integer($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'position' at index ".$index.".");
            }
        };
        $check($arguments[1], 1);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'pattern' at index ".$index.".");
            }
        };
        $check($arguments[2], 2);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_bool($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'optional' at index ".$index.".");
            }
        };
        $check($arguments[3], 3);

        $check = function($argument, $index) {
            $check = function($value) {
                return is_string($value);
            };
            if (!$check($argument)) {
                throw new \InvalidArgumentException("Unexpected argument for parameter 'type' at index ".$index.".");
            }
        };
        $check($arguments[4], 4);
    }
}
