<?php
namespace Eloquent\Typhoon\TypeCheck\Validator\Eloquent\Typhoon\ClassMapper;

class ClassMapperTypeCheck extends \Eloquent\Typhoon\TypeCheck\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
    }

    public function classesByPath(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('path', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'path',
                0,
                $arguments[0],
                'string'
            );
        }
    }

    public function classesByPaths(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('paths', 0, 'array<string>');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!\is_string($subValue)) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[0])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'paths',
                0,
                $arguments[0],
                'array<string>'
            );
        }
    }

    public function classesByDirectory(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('directoryPath', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'directoryPath',
                0,
                $arguments[0],
                'string'
            );
        }
    }

    public function classesByFile(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('filePath', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'filePath',
                0,
                $arguments[0],
                'string'
            );
        }
    }

    public function classesBySource(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                0,
                $arguments[0],
                'string'
            );
        }
    }

    public function classBySource(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 2) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('className', 0, 'Eloquent\\Cosmos\\ClassName');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 1, 'string');
        } elseif ($argumentCount > 2) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(2, $arguments[2]);
        }
        $value = $arguments[1];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                1,
                $arguments[1],
                'string'
            );
        }
    }

    public function parseNamespaceName(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('tokens', 0, 'array<string|array>');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!(\is_string($subValue) || \is_array($subValue))) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[0])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'tokens',
                0,
                $arguments[0],
                'array<string|array>'
            );
        }
    }

    public function parseUsedClass(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('tokens', 0, 'array<string|array>');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!(\is_string($subValue) || \is_array($subValue))) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[0])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'tokens',
                0,
                $arguments[0],
                'array<string|array>'
            );
        }
    }

    public function parseClassDefinition(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 3) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('tokens', 0, 'array<string|array>');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('namespaceName', 1, 'Eloquent\\Cosmos\\ClassName|null');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('usedClasses', 2, 'array<array<Eloquent\\Cosmos\\ClassName>>');
        } elseif ($argumentCount > 3) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(3, $arguments[3]);
        }
        $value = $arguments[0];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!(\is_string($subValue) || \is_array($subValue))) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[0])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'tokens',
                0,
                $arguments[0],
                'array<string|array>'
            );
        }
        $value = $arguments[2];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            $valueCheck = function ($subValue) {
                if (!\is_array($subValue)) {
                    return false;
                }
                foreach ($subValue as $key => $subValue) {
                    if (!$subValue instanceof \Eloquent\Cosmos\ClassName) {
                        return false;
                    }
                }
                return true;
            };
            foreach ($value as $key => $subValue) {
                if (!$valueCheck($subValue)) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[2])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'usedClasses',
                2,
                $arguments[2],
                'array<array<Eloquent\\Cosmos\\ClassName>>'
            );
        }
    }

    public function parseClassMemberModifiers(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 5) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('token', 0, 'tuple<integer, string, integer>');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('tokens', 1, 'array<string|array>');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('accessModifier', 2, 'null');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isStatic', 3, 'null');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 4, 'null');
        } elseif ($argumentCount > 5) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(5, $arguments[5]);
        }
        $value = $arguments[0];
        if (!(\is_array($value) && \array_keys($value) === \range(0, 2) && \is_int($value[0]) && \is_string($value[1]) && \is_int($value[2]))) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'token',
                0,
                $arguments[0],
                'tuple<integer, string, integer>'
            );
        }
        $value = $arguments[1];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!(\is_string($subValue) || \is_array($subValue))) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[1])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'tokens',
                1,
                $arguments[1],
                'array<string|array>'
            );
        }
        $value = $arguments[2];
        if (!($value === null)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'accessModifier',
                2,
                $arguments[2],
                'null'
            );
        }
        $value = $arguments[3];
        if (!($value === null)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'isStatic',
                3,
                $arguments[3],
                'null'
            );
        }
        $value = $arguments[4];
        if (!($value === null)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                4,
                $arguments[4],
                'null'
            );
        }
    }

    public function parseProperty(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 6) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('token', 0, 'tuple<integer, string, integer>');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('tokens', 1, 'array<string|array>');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('accessModifier', 2, 'Icecave\\Pasta\\AST\\Type\\AccessModifier');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isStatic', 3, 'boolean');
            }
            if ($argumentCount < 5) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 4, 'string');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('lineNumber', 5, 'integer');
        } elseif ($argumentCount > 6) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(6, $arguments[6]);
        }
        $value = $arguments[0];
        if (!(\is_array($value) && \array_keys($value) === \range(0, 2) && \is_int($value[0]) && \is_string($value[1]) && \is_int($value[2]))) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'token',
                0,
                $arguments[0],
                'tuple<integer, string, integer>'
            );
        }
        $value = $arguments[1];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!(\is_string($subValue) || \is_array($subValue))) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[1])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'tokens',
                1,
                $arguments[1],
                'array<string|array>'
            );
        }
        $value = $arguments[3];
        if (!\is_bool($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'isStatic',
                3,
                $arguments[3],
                'boolean'
            );
        }
        $value = $arguments[4];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                4,
                $arguments[4],
                'string'
            );
        }
        $value = $arguments[5];
        if (!\is_int($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'lineNumber',
                5,
                $arguments[5],
                'integer'
            );
        }
    }

    public function parseMethod(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 6) {
            if ($argumentCount < 1) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('token', 0, 'tuple<integer, string, integer>');
            }
            if ($argumentCount < 2) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('tokens', 1, 'array<string|array>');
            }
            if ($argumentCount < 3) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('accessModifier', 2, 'Icecave\\Pasta\\AST\\Type\\AccessModifier');
            }
            if ($argumentCount < 4) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('isStatic', 3, 'boolean');
            }
            if ($argumentCount < 5) {
                throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('source', 4, 'string');
            }
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('lineNumber', 5, 'integer');
        } elseif ($argumentCount > 6) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(6, $arguments[6]);
        }
        $value = $arguments[0];
        if (!(\is_array($value) && \array_keys($value) === \range(0, 2) && \is_int($value[0]) && \is_string($value[1]) && \is_int($value[2]))) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'token',
                0,
                $arguments[0],
                'tuple<integer, string, integer>'
            );
        }
        $value = $arguments[1];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!(\is_string($subValue) || \is_array($subValue))) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[1])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'tokens',
                1,
                $arguments[1],
                'array<string|array>'
            );
        }
        $value = $arguments[3];
        if (!\is_bool($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'isStatic',
                3,
                $arguments[3],
                'boolean'
            );
        }
        $value = $arguments[4];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'source',
                4,
                $arguments[4],
                'string'
            );
        }
        $value = $arguments[5];
        if (!\is_int($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'lineNumber',
                5,
                $arguments[5],
                'integer'
            );
        }
    }

    public function parseClassName(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('tokens', 0, 'array<string|array>');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        $check = function ($value) {
            if (!\is_array($value)) {
                return false;
            }
            foreach ($value as $key => $subValue) {
                if (!(\is_string($subValue) || \is_array($subValue))) {
                    return false;
                }
            }
            return true;
        };
        if (!$check($arguments[0])) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'tokens',
                0,
                $arguments[0],
                'array<string|array>'
            );
        }
    }

    public function fileIterator(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('directoryPath', 0, 'string');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!\is_string($value)) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'directoryPath',
                0,
                $arguments[0],
                'string'
            );
        }
    }

    public function normalizeToken(array $arguments)
    {
        $argumentCount = \count($arguments);
        if ($argumentCount < 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\MissingArgumentException('token', 0, 'string|tuple<integer, string, integer>');
        } elseif ($argumentCount > 1) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentException(1, $arguments[1]);
        }
        $value = $arguments[0];
        if (!(\is_string($value) || \is_array($value) && \array_keys($value) === \range(0, 2) && \is_int($value[0]) && \is_string($value[1]) && \is_int($value[2]))) {
            throw new \Eloquent\Typhoon\TypeCheck\Exception\UnexpectedArgumentValueException(
                'token',
                0,
                $arguments[0],
                'string|tuple<integer, string, integer>'
            );
        }
    }

}
