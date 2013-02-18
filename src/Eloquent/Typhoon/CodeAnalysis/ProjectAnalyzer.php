<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhax\Resolver\ObjectTypeClassNameResolver;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\Generator\ParameterListMerge\MergeTool;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parser\ParameterListParser;
use Eloquent\Typhoon\Resolver\ParameterListClassNameResolver;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use Icecave\Pasta\AST\Type\AccessModifier;

class ProjectAnalyzer
{
    /**
     * @param ClassMapper|null         $classMapper
     * @param ParameterListParser|null $parameterListParser
     * @param MergeTool|null           $mergeTool
     */
    public function __construct(
        ClassMapper $classMapper = null,
        ParameterListParser $parameterListParser = null,
        MergeTool $mergeTool = null
    ) {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        if (null === $classMapper) {
            $classMapper = new ClassMapper;
        }
        if (null === $parameterListParser) {
            $parameterListParser = new ParameterListParser;
        }
        if (null === $mergeTool) {
            $mergeTool = new MergeTool(false);
        }

        $this->classMapper = $classMapper;
        $this->parameterListParser = $parameterListParser;
        $this->mergeTool = $mergeTool;
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
     * @return ParameterListParser
     */
    public function parameterListParser()
    {
        $this->typeCheck->parameterListParser(func_get_args());

        return $this->parameterListParser;
    }

    /**
     * @return MergeTool
     */
    public function mergeTool()
    {
        $this->typeCheck->mergeTool(func_get_args());

        return $this->mergeTool;
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
        $issues = array();
        foreach ($this->classMapper()->classesByPaths($sourcePaths) as $classDefinition) {
            $this->analyzeClass(
                $configuration,
                $classDefinition,
                $facadeClassName,
                $issues
            );
        }

        return new AnalysisResult($issues);
    }

    /**
     * @param Configuration               $configuration
     * @param ClassDefinition             $classDefinition
     * @param ClassName                   $facadeClassName
     * @param array<Issue\IssueInterface> &$issues
     */
    protected function analyzeClass(
        Configuration $configuration,
        ClassDefinition $classDefinition,
        ClassName $facadeClassName,
        array &$issues
    ) {
        $this->typeCheck->analyzeClass(func_get_args());

        $expectedfacadeClassName = $classDefinition
            ->classNameResolver()
            ->shorten($facadeClassName)
        ;

        $hasConstructorInit = false;
        $propertyName = 'typeCheck';
        $hasNonStaticCalls = false;

        if ($classDefinition->hasMethod('__construct')) {
            $methodDefinition = $classDefinition->method('__construct');
            if ($this->methodHasInit($methodDefinition, $expectedfacadeClassName, $propertyName)) {
                $hasConstructorInit = true;
            } elseif (!$this->methodHasConstructorStaticCall($methodDefinition, $expectedfacadeClassName)) {
                $issues[] = new Issue\ClassIssue\MissingConstructorCall(
                    $classDefinition
                );
            }
        }

        foreach ($classDefinition->methods() as $methodDefinition) {
            switch ($methodDefinition->name()) {
                case '__construct':
                case '__wakeup':
                    break;
                case '__destruct':
                case '__toString':
                    if (
                        $this->methodHasCall($methodDefinition, $propertyName) ||
                        $this->methodHasStaticCall($methodDefinition, $expectedfacadeClassName)
                    ) {
                        $issues[] = new Issue\MethodIssue\InadmissibleMethodCall(
                            $classDefinition,
                            $methodDefinition
                        );
                    }
                    break;
                case 'unserialize':
                    if ($this->classImplementsSerializable($classDefinition)) {
                        break;
                    }
                default:
                    if ($methodDefinition->isAbstract()) {
                        break;
                    }

                    $missingCall = false;
                    if ($methodDefinition->isStatic()) {
                        $missingCall = !$this->methodHasStaticCall($methodDefinition, $expectedfacadeClassName);
                    } else {
                        if ($this->methodHasCall($methodDefinition, $propertyName)) {
                            $hasNonStaticCalls = true;
                        } elseif (!$this->methodHasStaticCall($methodDefinition, $expectedfacadeClassName)) {
                            $missingCall = true;
                        }
                    }

                    if ($missingCall) {
                        $issues[] = new Issue\MethodIssue\MissingMethodCall(
                            $classDefinition,
                            $methodDefinition
                        );
                    }
            }

            if (!$methodDefinition->isAbstract()) {
                $methodReflector = $methodDefinition->createReflector();
                $blockComment = $methodReflector->getDocComment();
                if (false === $blockComment) {
                    $documentedParameterList = new ParameterList;
                } else {
                    $documentedParameterList = $this->parameterListParser()
                        ->parseBlockComment(
                            $classDefinition->className(),
                            $methodDefinition->name(),
                            $blockComment
                        )
                    ;
                }
                $documentedParameterList = $documentedParameterList->accept(
                    $this->createClassNameResolver($classDefinition)
                );
                $this->mergeTool()->clearIssues();
                $this->mergeTool()->merge(
                    $configuration,
                    $classDefinition,
                    $methodDefinition,
                    $documentedParameterList,
                    $this->parameterListParser()->parseReflector($methodReflector)
                );

                $issues = array_merge($issues, $this->mergeTool()->issues());
            }
        }

        if ($hasNonStaticCalls && !$hasConstructorInit) {
            array_unshift(
                $issues,
                new Issue\ClassIssue\MissingConstructorCall(
                    $classDefinition
                )
            );
        }
        if ($hasConstructorInit) {
            if ($classDefinition->hasProperty($propertyName)) {
                $typhoonProperty = $classDefinition->property($propertyName);
                $missingProperty =
                    $typhoonProperty->isStatic() ||
                    AccessModifier::PRIVATE_() !== $typhoonProperty->accessModifier()
                ;
            } else {
                $missingProperty = true;
            }

            if ($missingProperty) {
                $issues[] = new Issue\ClassIssue\MissingProperty(
                    $classDefinition
                );
            }
        }
    }

    /**
     * @param MethodDefinition $methodDefinition
     * @param ClassName        $expectedfacadeClassName
     * @param string           &$propertyName
     *
     * @return boolean
     */
    protected function methodHasInit(
        MethodDefinition $methodDefinition,
        ClassName $expectedfacadeClassName,
        &$propertyName
    ) {
        $this->typeCheck->methodHasInit(func_get_args());

        $hasInit = false;
        $callPattern = sprintf(
            '/^\s*\$this\s*->\s*(%s)\s*=\s*%s\s*::\s*get\s*\(\s*__CLASS__\s*,\s*\\\\?func_get_args\s*\(\s*\)\s*\)\s*;$/',
            '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*', // PHP variable name
            preg_quote($expectedfacadeClassName->string(), '/')
        );

        $firstStatement = $this->parseFirstMethodStatement($methodDefinition->source());
        if (preg_match($callPattern, $firstStatement, $matches)) {
            $hasInit = true;
            $propertyName = $matches[1];
        }

        return $hasInit;
    }

    /**
     * @param MethodDefinition $methodDefinition
     * @param string           $propertyName
     *
     * @return boolean
     */
    protected function methodHasCall(
        MethodDefinition $methodDefinition,
        $propertyName
    ) {
        $this->typeCheck->methodHasCall(func_get_args());

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

        return $hasCall;
    }

    /**
     * @param MethodDefinition $methodDefinition
     * @param ClassName        $expectedfacadeClassName
     *
     * @return boolean
     */
    protected function methodHasStaticCall(
        MethodDefinition $methodDefinition,
        ClassName $expectedfacadeClassName
    ) {
        $this->typeCheck->methodHasStaticCall(func_get_args());

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

        return $hasCall;
    }

    /**
     * @param MethodDefinition $methodDefinition
     * @param ClassName        $expectedfacadeClassName
     *
     * @return boolean
     */
    protected function methodHasConstructorStaticCall(
        MethodDefinition $methodDefinition,
        ClassName $expectedfacadeClassName
    ) {
        $this->typeCheck->methodHasConstructorStaticCall(func_get_args());

        $hasCall = false;
        $callPattern = sprintf(
            '/^\s*%s\s*::\s*get\s*\(\s*__CLASS__\s*,\s*\\\\?func_get_args\s*\(\s*\)\s*\)\s*;$/',
            preg_quote($expectedfacadeClassName->string(), '/')
        );

        $firstStatement = $this->parseFirstMethodStatement($methodDefinition->source());
        if (preg_match($callPattern, $firstStatement)) {
            $hasCall = true;
        }

        return $hasCall;
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

    /**
     * @param ClassDefinition $classDefinition
     *
     * @return boolean
     */
    protected function classImplementsSerializable(ClassDefinition $classDefinition)
    {
        $this->typeCheck->classImplementsSerializable(func_get_args());

        return $classDefinition->createReflector()->implementsInterface('Serializable');
    }

    /**
     * @param ClassDefinition $classDefinition
     *
     * @return ParameterListClassNameResolver
     */
    protected function createClassNameResolver(ClassDefinition $classDefinition)
    {
        $this->typeCheck->createClassNameResolver(func_get_args());

        return new ParameterListClassNameResolver(
            new ObjectTypeClassNameResolver(
                $classDefinition->classNameResolver()
            )
        );
    }

    private $classMapper;
    private $parameterListParser;
    private $mergeTool;
    private $typeCheck;
}
