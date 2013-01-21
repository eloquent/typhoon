<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Type\AccessModifier;

class ProjectAnalyzer
{
    /**
     * @param ClassMapper|null $classMapper
     */
    public function __construct(
        ClassMapper $classMapper = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }

        $this->classMapper = $classMapper;
    }

    /**
     * @return ClassMapper
     */
    public function classMapper()
    {
        $this->typeCheck->classMapper(func_get_args());

        return $this->classMapper;
    }

    /**
     * @param Configuration $configuration
     *
     * @return AnalysisResult
     */
    public function analyze(Configuration $configuration)
    {
        $this->typeCheck->analyze(func_get_args());

        $facadeClassName = $configuration
            ->validatorNamespace()
            ->joinAtoms('TypeCheck')
        ;

        $sourcePaths = $configuration->sourcePaths();
        $classesMissingConstructorCall = array();
        $classesMissingProperty = array();
        $methodsMissingCall = array();
        foreach ($this->classMapper()->classesByPaths($sourcePaths) as $classDefinition) {
            $this->analyzeClass(
                $classDefinition,
                $facadeClassName,
                $classesMissingConstructorCall,
                $classesMissingProperty,
                $methodsMissingCall
            );
        }

        usort(
            $classesMissingConstructorCall,
            'Eloquent\Typhoon\ClassMapper\ClassDefinition::compare'
        );
        usort(
            $classesMissingProperty,
            'Eloquent\Typhoon\ClassMapper\ClassDefinition::compare'
        );
        usort(
            $methodsMissingCall,
            'Eloquent\Typhoon\ClassMapper\ClassMemberDefinition::compareTuples'
        );

        return new AnalysisResult(
            $classesMissingConstructorCall,
            $classesMissingProperty,
            $methodsMissingCall
        );
    }

    /**
     * @param ClassDefinition $classDefinition
     * @param ClassName       $facadeClassName
     * @param array<ClassDefinition>                         &$classesMissingConstructorCall
     * @param array<ClassDefinition>                         &$classesMissingProperty
     * @param array<tuple<ClassDefinition,MethodDefinition>> &$methodsMissingCall
     */
    protected function analyzeClass(
        ClassDefinition $classDefinition,
        ClassName $facadeClassName,
        array &$classesMissingConstructorCall,
        array &$classesMissingProperty,
        array &$methodsMissingCall
    ) {
        $this->typeCheck->analyzeClass(func_get_args());

        $expectedfacadeClassName = $classDefinition
            ->classNameResolver()
            ->shorten($facadeClassName)
        ;

        $hasConstructorCall = false;
        $propertyName = 'typeCheck';
        foreach ($classDefinition->methods() as $methodDefinition) {
            if ('__construct' !== $methodDefinition->name()) {
                continue;
            }

            list($hasConstructorCall, $propertyName) = $this->analyzeConstructor(
                $methodDefinition,
                $expectedfacadeClassName
            );
        }
        $hasCheckableMethods = false;
        foreach ($classDefinition->methods() as $methodDefinition) {
            if (
                '__construct' === $methodDefinition->name() ||
                '__destruct' === $methodDefinition->name() ||
                '__wakeup' === $methodDefinition->name() ||
                'unserialize' === $methodDefinition->name()
            ) {
                continue;
            }

            if ($methodDefinition->isStatic()) {
                list($hasCall) = $this->analyzeStaticMethod(
                    $methodDefinition,
                    $expectedfacadeClassName
                );
            } elseif (!$methodDefinition->isAbstract()) {
                $hasCheckableMethods = true;
                list($hasCall) = $this->analyzeMethod(
                    $methodDefinition,
                    $propertyName
                );
            }

            if (!$hasCall) {
                $methodsMissingCall[] = array(
                    $classDefinition,
                    $methodDefinition,
                );
            }
        }

        $hasProperty = false;
        foreach ($classDefinition->properties() as $propertyDefinition) {
            if (
                !$propertyDefinition->isStatic() &&
                $propertyDefinition->accessModifier() === AccessModifier::PRIVATE_() &&
                $propertyDefinition->name() === $propertyName
            ) {
                $hasProperty = true;
            }
        }

        if ($hasCheckableMethods && !$hasConstructorCall) {
            $classesMissingConstructorCall[] = $classDefinition;
        }
        if ($hasCheckableMethods && !$hasProperty) {
            $classesMissingProperty[] = $classDefinition;
        }
    }

    /**
     * @param MethodDefinition $methodDefinition
     * @param ClassName        $expectedfacadeClassName
     *
     * @return tuple<boolean,string|null>
     */
    protected function analyzeConstructor(
        MethodDefinition $methodDefinition,
        ClassName $expectedfacadeClassName
    ) {
        $this->typeCheck->analyzeConstructor(func_get_args());

        $hasConstructorCall = false;
        $propertyName = 'typeCheck';
        $callPattern = sprintf(
            '/^\s*\$this\s*->\s*(%s)\s*=\s*%s\s*::\s*get\s*\(\s*__CLASS__\s*,\s*\\\\?func_get_args\s*\(\s*\)\s*\)\s*;$/',
            '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*', // PHP variable name
            preg_quote($expectedfacadeClassName->string(), '/')
        );

        $firstStatement = $this->parseFirstMethodStatement($methodDefinition->source());
        if (preg_match($callPattern, $firstStatement, $matches)) {
            $hasConstructorCall = true;
            $propertyName = $matches[1];
        }

        return array(
            $hasConstructorCall,
            $propertyName,
        );
    }

    /**
     * @param MethodDefinition $methodDefinition
     * @param string           $propertyName
     *
     * @return tuple<boolean>
     */
    protected function analyzeMethod(
        MethodDefinition $methodDefinition,
        $propertyName
    ) {
        $this->typeCheck->analyzeMethod(func_get_args());

        $hasCall = false;
        $expectedMethodName = $this->normalizeValidatorMethodName(
            $methodDefinition->name()
        );
        $callPattern = sprintf(
            '/^\s*\$this\s*->\s*%s\s*->\s*%s\s*\(\s*\\\\?func_get_args\s*\(\s*\)\s*\)\s*;$/',
            preg_quote($propertyName, '/'),
            preg_quote($expectedMethodName, '/')
        );

        $firstStatement = $this->parseFirstMethodStatement($methodDefinition->source());
        if (preg_match($callPattern, $firstStatement)) {
            $hasCall = true;
        }

        return array(
            $hasCall,
        );
    }

    /**
     * @param MethodDefinition $methodDefinition
     * @param ClassName        $expectedfacadeClassName
     *
     * @return tuple<boolean,string|null>
     */
    protected function analyzeStaticMethod(
        MethodDefinition $methodDefinition,
        ClassName $expectedfacadeClassName
    ) {
        $this->typeCheck->analyzeStaticMethod(func_get_args());

        $hasCall = false;
        $expectedMethodName = $this->normalizeValidatorMethodName(
            $methodDefinition->name()
        );
        $callPattern = sprintf(
            '/^\s*%s\s*::\s*get\s*\(\s*__CLASS__\s*\)\s*->\s*%s\s*\(\s*\\\\?func_get_args\s*\(\s*\)\s*\)\s*;$/',
            preg_quote($expectedfacadeClassName->string(), '/'),
            preg_quote($expectedMethodName, '/')
        );

        $firstStatement = $this->parseFirstMethodStatement($methodDefinition->source());
        if (preg_match($callPattern, $firstStatement)) {
            $hasCall = true;
        }

        return array(
            $hasCall,
        );
    }

    /**
     * @param string $source
     *
     * @return string
     */
    protected function parseFirstMethodStatement($source)
    {
        $this->typeCheck->parseFirstMethodStatement(func_get_args());

        $tokens = token_get_all('<?php '.$source);
        array_shift($tokens);
        array_shift($tokens);

        $inModifiers = true;
        $inName = false;
        $inArguments = false;
        $parenthesisDepth = 0;
        $inBody = false;
        $inStatement = false;
        $statement = '';
        while ($token = next($tokens)) {
            $token = $this->normalizeToken($token);

            if ($inStatement) {
                $statement .= $token[1];
                if (';' === $token[0]) {
                    break;
                }
            } elseif ($inBody) {
                if (
                    T_WHITESPACE !== $token[0] &&
                    T_COMMENT !== $token[0] &&
                    T_DOC_COMMENT !== $token[0]
                ) {
                    $inStatement = true;
                    $statement .= $token[1];
                }
            } elseif ($inArguments) {
                if (')' === $token[0]) {
                    $parenthesisDepth --;
                } elseif ('(' === $token[0]) {
                    $parenthesisDepth ++;
                }

                if ('{' === $token[0] && 0 === $parenthesisDepth) {
                    $inBody = true;
                }
            } elseif ($inName) {
                if ('(' === $token[0]) {
                    $inArguments = true;
                    $parenthesisDepth ++;
                }
            } elseif ($inModifiers) {
                if (T_FUNCTION === $token[0]) {
                    $inName = true;
                }
            }
        }

        return $statement;
    }

    /**
     * @param string|tuple<integer,string,integer> $token
     *
     * @return tuple<integer|string,string,integer|null>
     */
    protected function normalizeToken($token)
    {
        $this->typeCheck->normalizeToken(func_get_args());

        if (is_array($token)) {
            return $token;
        }

        return array($token, $token, null);
    }

    /**
     * @param string $methodName
     *
     * @return string
     */
    protected function normalizeValidatorMethodName($methodName)
    {
        $this->typeCheck->normalizeValidatorMethodName(func_get_args());

        if ('__' === substr($methodName, 0, 2)) {
            $methodName = sprintf(
                'validate%s',
                ucfirst(substr($methodName, 2))
            );
        }

        return $methodName;
    }

    private $classMapper;
    private $typeCheck;
}
