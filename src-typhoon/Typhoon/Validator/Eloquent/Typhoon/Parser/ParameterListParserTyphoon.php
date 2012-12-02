<?php
namespace Typhoon\Validator\Eloquent\Typhoon\Parser;


class ParameterListParserTyphoon extends \Typhoon\AbstractValidator
{
    public function validateConstruct(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function typhaxParser(array $arguments)
    {
        if ((\count($arguments) > 0))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(0, $arguments[0]));
        }
    }
    public function parseBlockComment(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('functionName', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('blockComment', 1, 'string'));
        }
        elseif (($argumentCount > 3))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(3, $arguments[3]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'functionName',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'blockComment',
                1,
                $arguments[1],
                'string'
            ));
        }
    }
    public function parseReflector(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('reflector', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function parseParameterReflector(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('reflector', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function visitDocumentationBlock(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('documentationBlock', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function visitDocumentationTag(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('documentationTag', 0, 'mixed'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
    }
    public function parseType(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('content', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('position', 1, 'integer'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'content',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_int($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'position',
                1,
                $arguments[1],
                'integer'
            ));
        }
    }
    public function parseByReference(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('content', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('position', 1, 'integer'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'content',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_int($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'position',
                1,
                $arguments[1],
                'integer'
            ));
        }
    }
    public function parseName(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('content', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('position', 1, 'integer'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'content',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_int($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'position',
                1,
                $arguments[1],
                'integer'
            ));
        }
    }
    public function parseDescription(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 2))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('content', 0, 'string'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('position', 1, 'integer'));
        }
        elseif (($argumentCount > 2))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(2, $arguments[2]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'content',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_int($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'position',
                1,
                $arguments[1],
                'integer'
            ));
        }
    }
    public function parseOptional(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 1))
        {
            throw (new \Typhoon\Exception\MissingArgumentException('content', 0, 'string'));
        }
        elseif (($argumentCount > 1))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(1, $arguments[1]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'content',
                0,
                $arguments[0],
                'string'
            ));
        }
    }
    public function parseContent(array $arguments)
    {
        ($argumentCount = \count($arguments));
        if (($argumentCount < 5))
        {
            if (($argumentCount < 1))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('content', 0, 'string'));
            }
            if (($argumentCount < 2))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('position', 1, 'integer'));
            }
            if (($argumentCount < 3))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('pattern', 2, 'string'));
            }
            if (($argumentCount < 4))
            {
                throw (new \Typhoon\Exception\MissingArgumentException('optional', 3, 'boolean'));
            }
            throw (new \Typhoon\Exception\MissingArgumentException('type', 4, 'string'));
        }
        elseif (($argumentCount > 5))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentException(5, $arguments[5]));
        }
        ($value = $arguments[0]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'content',
                0,
                $arguments[0],
                'string'
            ));
        }
        ($value = $arguments[1]);
        if ((!\is_int($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'position',
                1,
                $arguments[1],
                'integer'
            ));
        }
        ($value = $arguments[2]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'pattern',
                2,
                $arguments[2],
                'string'
            ));
        }
        ($value = $arguments[3]);
        if ((!\is_bool($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'optional',
                3,
                $arguments[3],
                'boolean'
            ));
        }
        ($value = $arguments[4]);
        if ((!\is_string($value)))
        {
            throw (new \Typhoon\Exception\UnexpectedArgumentValueException(
                'type',
                4,
                $arguments[4],
                'string'
            ));
        }
    }
}
