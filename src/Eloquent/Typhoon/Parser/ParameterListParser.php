<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser;

use Eloquent\Cosmos\ClassName;
use Eloquent\Blox\AST\DocumentationBlock;
use Eloquent\Blox\AST\DocumentationTag;
use Eloquent\Blox\AST\Visitor;
use Eloquent\Blox\BloxParser;
use Eloquent\Blox\DocumentationBlockParser;
use Eloquent\Typhax\Parser\Parser as TyphaxParser;
use Eloquent\Typhax\Parser\Exception\UnexpectedTokenException;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use ReflectionFunctionAbstract;
use ReflectionObject;
use ReflectionParameter;

class ParameterListParser implements Visitor
{
    /**
     * @param TyphaxParser|null $typhaxParser
     */
    public function __construct(TyphaxParser $typhaxParser = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $typhaxParser) {
            $typhaxParser = new TyphaxParser;
        }

        $this->typhaxParser = $typhaxParser;
    }

    /**
     * @return TyphaxParser
     */
    public function typhaxParser()
    {
        $this->typeCheck->typhaxParser(func_get_args());

        return $this->typhaxParser;
    }

    /**
     * @param ClassName|null                $className
     * @param string                        $functionName
     * @param string                        $blockComment
     * @param DocumentationBlockParser|null $documentationParser
     *
     * @return ParameterList
     */
    public function parseBlockComment(
        ClassName $className = null,
        $functionName,
        $blockComment,
        DocumentationBlockParser $documentationParser = null
    ) {
        $this->typeCheck->parseBlockComment(func_get_args());

        if (null === $documentationParser) {
            $documentationParser = new BloxParser;
        }

        try {
            $parameterList =
                $documentationParser
                ->parseBlockComment($blockComment)
                ->accept($this)
            ;
        } catch (Exception\ParseException $e) {
            throw new Exception\InvalidFunctionDocumentationException(
                $className,
                $functionName
            );
        }

        return $parameterList;
    }

    /**
     * @param ReflectionFunctionAbstract $reflector
     *
     * @return ParameterList
     */
    public function parseReflector(ReflectionFunctionAbstract $reflector)
    {
        $this->typeCheck->parseReflector(func_get_args());

        $parameters = array();
        foreach ($reflector->getParameters() as $parameterReflector) {
            $parameters[] = $this->parseParameterReflector($parameterReflector);
        }

        return new ParameterList($parameters);
    }

    /**
     * @param ReflectionParameter $reflector
     *
     * @return Parameter
     */
    public function parseParameterReflector(ReflectionParameter $reflector)
    {
        $this->typeCheck->parseParameterReflector(func_get_args());

        if ($class = $reflector->getClass()) {
            $type = new ObjectType(ClassName::fromString($class->getName())->toAbsolute());
        } elseif ($reflector->isArray()) {
            $type = new TraversableType(
                new ArrayType,
                new MixedType,
                new MixedType
            );
        } else {
            $reflectorReflector = new ReflectionObject($reflector);
            if (
                $reflectorReflector->hasMethod('isCallable') &&
                $reflector->isCallable()
            ) {
                $type = new CallableType;
            } else {
                $type = new MixedType;
            }
        }

        if (
            !$type instanceof MixedType &&
            $reflector->allowsNull()
        ) {
            $type = new OrType(array(
                $type,
                new NullType,
            ));
        }

        return new Parameter(
            $reflector->getName(),
            $type,
            null,
            $reflector->isOptional(),
            $reflector->isPassedByReference()
        );
    }

    /**
     * @param DocumentationBlock $documentationBlock
     *
     * @return ParameterList
     */
    public function visitDocumentationBlock(DocumentationBlock $documentationBlock)
    {
        $this->typeCheck->visitDocumentationBlock(func_get_args());

        $parameters = array();
        $paramTags = $documentationBlock->tagsByName('param');
        $lastIndex = count($paramTags) - 1;
        $variableLength = false;
        foreach ($paramTags as $index => $paramTag) {
            $parameters[] = $paramTag->accept($this);
            if ($index === $lastIndex) {
                $variableLength = preg_match(
                    static::PATTERN_VARIABLE_LENGTH,
                    $paramTag->content()
                ) && true;
            }
        }

        return new ParameterList($parameters, $variableLength);
    }

    /**
     * @param DocumentationTag $documentationTag
     *
     * @return Parameter
     */
    public function visitDocumentationTag(DocumentationTag $documentationTag)
    {
        $this->typeCheck->visitDocumentationTag(func_get_args());
        $content = $documentationTag->content() ?: '';

        $position = 0;
        $type = $this->parseType(
            $content,
            $position
        );
        $byReference = $this->parseByReference(
            $content,
            $position
        );
        $name = $this->parseName(
            $content,
            $position
        );
        $description = $this->parseDescription(
            $content,
            $position
        );
        $optional = $this->parseOptional(
            $content
        );

        return new Parameter(
            $name,
            $type,
            $description,
            $optional,
            $byReference
        );
    }

    const PATTERN_VARIABLE_LENGTH = '/^.*\s&?\$\w+,\.{3}(?:$|\s)/';

    /**
     * @param string $content
     * @param integer &$position
     *
     * @return Type
     */
    protected function parseType($content, &$position)
    {
        $this->typeCheck->parseType(func_get_args());

        try {
            return $this->typhaxParser()->parse(
                $content,
                $position
            );
        } catch (UnexpectedTokenException $e) {
            throw new Exception\UnexpectedContentException(
                'type',
                $e->position(),
                $e
            );
        }
    }

    /**
     * @param string $content
     * @param integer &$position
     *
     * @return boolean
     */
    protected function parseByReference($content, &$position)
    {
        $this->typeCheck->parseByReference(func_get_args());

        return null !== $this->parseContent(
            $content,
            $position,
            '/^\s*(&)/',
            true,
            'byReference'
        );
    }

    /**
     * @param string $content
     * @param integer &$position
     *
     * @return string
     */
    protected function parseName($content, &$position)
    {
        $this->typeCheck->parseName(func_get_args());

        return $this->parseContent(
            $content,
            $position,
            '/^\s*\$(\w+)(?:,\.{3})?/',
            false,
            'name'
        );
    }

    /**
     * @param string $content
     * @param integer &$position
     *
     * @return string|null
     */
    protected function parseDescription($content, &$position)
    {
        $this->typeCheck->parseDescription(func_get_args());

        return $this->parseContent(
            $content,
            $position,
            '/^\s*(.*)$/',
            true,
            'description'
        );
    }

    /**
     * @param string $content
     *
     * @return boolean
     */
    protected function parseOptional($content)
    {
        $this->typeCheck->parseOptional(func_get_args());

        if (preg_match(static::PATTERN_VARIABLE_LENGTH, $content)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $content
     * @param integer &$position
     * @param string  $pattern
     * @param boolean $optional
     * @param string  $type
     *
     * @return string|null
     */
    protected function parseContent($content, &$position, $pattern, $optional, $type)
    {
        $this->typeCheck->parseContent(func_get_args());

        $subject = substr($content, $position - 1);
        if (
            !preg_match($pattern, $subject, $matches) ||
            '' === $matches[1]
        ) {
            if ($optional) {
                return null;
            }

            throw new Exception\UnexpectedContentException(
                $type,
                $position
            );
        }

        $position += strlen($matches[0]);

        return $matches[1];
    }

    private $typhaxParser;
    private $typeCheck;
}
