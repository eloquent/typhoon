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

        return new AnalysisResult(
            $classesMissingConstructorCall,
            $classesMissingProperty,
            $methodsMissingCall
        );
    }

    const VARIABLE_NAME_PATTERN = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';

    /**
     * @param ClassDefinition                                $classDefinition
     * @param ClassName                                      $facadeClassName
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

        $hasConstructor = false;
        $hasConstructorCall = false;
        $propertyName = null;
        $hasNonStaticMethods = false;
        foreach ($classDefinition->methods() as $methodDefinition) {
            if ('__construct' === $methodDefinition->name()) {
                $hasConstructor = true;
                list($hasConstructorCall, $propertyName) = $this->analyzeConstructor(
                    $methodDefinition,
                    $expectedfacadeClassName
                );
            } elseif (!$methodDefinition->isStatic()) {
                $hasNonStaticMethods = true;
            }
        }

        if ($hasNonStaticMethods && !$hasConstructorCall) {
            $classesMissingConstructorCall[] = $classDefinition;
        }
    }

    /**
     * @param MethodDefinition $methodDefinition
     * @param ClassName $expectedfacadeClassName
     *
     * @return tuple<boolean,string|null>
     */
    protected function analyzeConstructor(
        MethodDefinition $methodDefinition,
        ClassName $expectedfacadeClassName
    ) {
        $this->typeCheck->analyzeConstructor(func_get_args());

        $hasConstructorCall = false;
        $propertyName = null;

        $callPattern = sprintf(
            '/^\s*\$this\s*->\s*(%s)\s*=\s*%s\s*::\s*get\s*\(\s*__CLASS__\s*,\s*\\\\?func_get_args\s*\(\s*\)\s*\)\s*;$/',
            static::VARIABLE_NAME_PATTERN,
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

    private $classMapper;
    private $typeCheck;
}
