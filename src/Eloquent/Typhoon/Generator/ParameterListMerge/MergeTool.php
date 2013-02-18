<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator\ParameterListMerge;

use Eloquent\Typhax\Comparator\TypeEquivalenceComparator;
use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhoon\ClassMapper\ClassDefinition;
use Eloquent\Typhoon\ClassMapper\MethodDefinition;
use Eloquent\Typhoon\CodeAnalysis\Issue\IssueInterface;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DefinedParameterVariableLength;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterByReferenceMismatch;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterNameMismatch;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterTypeMismatch;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterUndefined;
use Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\UndocumentedParameter;
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Generator\NullifiedType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use ReflectionClass;

class MergeTool
{
    /**
     * @param boolean $throwOnError
     */
    public function __construct($throwOnError = true)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        $this->throwOnError = $throwOnError;
        $this->clearIssues();

        $reflectionParameterClass = new ReflectionClass('ReflectionParameter');
        $this->nativeCallableAvailable =
            $reflectionParameterClass->hasMethod('isCallable')
        ;
    }

    /**
     * @return boolean
     */
    public function throwOnError()
    {
        $this->typeCheck->throwOnError(func_get_args());

        return $this->throwOnError;
    }

    public function clearIssues()
    {
        $this->typeCheck->clearIssues(func_get_args());

        $this->issues = array();
    }

    /**
     * @return array<IssueInterface>
     */
    public function issues()
    {
        $this->typeCheck->issues(func_get_args());

        return $this->issues;
    }

    /**
     * @return boolean
     */
    public function nativeCallableAvailable()
    {
        $this->typeCheck->nativeCallableAvailable(func_get_args());

        return $this->nativeCallableAvailable;
    }

    /**
     * @param RuntimeConfiguration $configuration
     *
     * @return boolean
     */
    public function useNativeCallable(RuntimeConfiguration $configuration)
    {
        $this->typeCheck->useNativeCallable(func_get_args());

        return
            $configuration->useNativeCallable() &&
            $this->nativeCallableAvailable()
        ;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassDefinition|null $classDefinition
     * @param MethodDefinition     $methodDefinition
     * @param ParameterList        $documentedParameterList
     * @param ParameterList        $nativeParameterList
     *
     * @return ParameterList
     */
    public function merge(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition = null,
        MethodDefinition $methodDefinition,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        $this->typeCheck->merge(func_get_args());

        $documentedParameters = $documentedParameterList->parameters();
        $nativeParameters = $nativeParameterList->parameters();

        $parameters = array();
        foreach ($nativeParameters as $index => $nativeParameter) {
            if (array_key_exists($index, $documentedParameters)) {
                $parameters[] = $this->mergeParameter(
                    $configuration,
                    $classDefinition,
                    $methodDefinition,
                    $documentedParameters[$index],
                    $nativeParameter
                );
            } else {
                $this->handleError(new UndocumentedParameter(
                    $classDefinition,
                    $methodDefinition,
                    $nativeParameter->name()
                ));

                $parameters[] = $nativeParameter;
            }
        }

        $documentedParameterCount = count($documentedParameters);
        $nativeParameterCount = count($nativeParameters);

        $isVariableLength = false;
        if ($documentedParameterList->isVariableLength()) {
            if ($documentedParameterCount > $nativeParameterCount + 1) {
                $this->handleError(new DocumentedParameterUndefined(
                    $classDefinition,
                    $methodDefinition,
                    $documentedParameters[$nativeParameterCount + 1]->name()
                ));
            } elseif ($documentedParameterCount === $nativeParameterCount) {
                $this->handleError(new DefinedParameterVariableLength(
                    $classDefinition,
                    $methodDefinition,
                    $nativeParameters[$nativeParameterCount - 1]->name()
                ));
            } elseif ($documentedParameterCount === $nativeParameterCount + 1) {
                $isVariableLength = true;
                $parameters[] = $documentedParameters[$nativeParameterCount];
            }
        } elseif ($documentedParameterCount > $nativeParameterCount) {
            for ($i = $nativeParameterCount; $i < $documentedParameterCount; $i ++) {
                $this->handleError(new DocumentedParameterUndefined(
                    $classDefinition,
                    $methodDefinition,
                    $documentedParameters[$i]->name()
                ));
            }
        }

        return new ParameterList($parameters, $isVariableLength);
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassDefinition|null $classDefinition
     * @param MethodDefinition     $methodDefinition
     * @param Parameter            $documentedParameter
     * @param Parameter            $nativeParameter
     *
     * @return Parameter
     */
    protected function mergeParameter(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition = null,
        MethodDefinition $methodDefinition,
        Parameter $documentedParameter,
        Parameter $nativeParameter
    ) {
        $this->typeCheck->mergeParameter(func_get_args());

        if ($documentedParameter->name() !== $nativeParameter->name()) {
            $this->handleError(new DocumentedParameterNameMismatch(
                $classDefinition,
                $methodDefinition,
                $nativeParameter->name(),
                $documentedParameter->name()
            ));

            return $nativeParameter;
        }

        if ($documentedParameter->isByReference() !== $nativeParameter->isByReference()) {
            $this->handleError(new DocumentedParameterByReferenceMismatch(
                $classDefinition,
                $methodDefinition,
                $nativeParameter->name(),
                $nativeParameter->isByReference()
            ));
        }

        return new Parameter(
            $documentedParameter->name(),
            $this->mergeType(
                $configuration,
                $classDefinition,
                $methodDefinition,
                $documentedParameter->name(),
                $documentedParameter->type(),
                $nativeParameter->type()
            ),
            $documentedParameter->description(),
            $nativeParameter->isOptional(),
            $documentedParameter->isByReference()
        );
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassDefinition|null $classDefinition
     * @param MethodDefinition     $methodDefinition
     * @param string               $parameterName
     * @param Type                 $documentedType
     * @param Type                 $nativeType
     *
     * @return Type
     */
    protected function mergeType(
        RuntimeConfiguration $configuration,
        ClassDefinition $classDefinition = null,
        MethodDefinition $methodDefinition,
        $parameterName,
        Type $documentedType,
        Type $nativeType
    ) {
        $this->typeCheck->mergeType(func_get_args());

        if (!$this->typeIsCompatible(
            $configuration,
            $documentedType,
            $nativeType
        )) {
            $this->handleError(new DocumentedParameterTypeMismatch(
                $classDefinition,
                $methodDefinition,
                $parameterName,
                $nativeType,
                $documentedType
            ));

            return $nativeType;
        }

        if (TypeEquivalenceComparator::equivalent($documentedType, $nativeType)) {
            return new NullifiedType($documentedType);
        }

        return $documentedType;
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param Type                 $documentedType
     * @param Type                 $nativeType
     * @param integer              $depth
     *
     * @return boolean
     */
    protected function typeIsCompatible(
        RuntimeConfiguration $configuration,
        Type $documentedType,
        Type $nativeType,
        $depth = 0
    ) {
        $this->typeCheck->typeIsCompatible(func_get_args());

        // callable
        if (
            $this->useNativeCallable($configuration) &&
            $documentedType instanceof CallableType
        ) {
            return $nativeType instanceof CallableType;
        }

        // null
        if ($documentedType instanceof NullType) {
            if ($depth < 1) {
                return $nativeType instanceof MixedType;
            }

            return $nativeType instanceof NullType;
        }

        // traversable
        if ($documentedType instanceof TraversableType) {
            return
                !$documentedType->primaryType() instanceof ArrayType ||
                $nativeType instanceof TraversableType
            ;
        }

        // tuple
        if ($documentedType instanceof TupleType) {
            return $nativeType instanceof TraversableType;
        }

        // object of type
        if (
            $documentedType instanceof ObjectType &&
            null !== $documentedType->ofType()
        ) {
            if (!$nativeType instanceof ObjectType) {
                return false;
            }
            if ($nativeType->ofType()->isRuntimeEquivalentTo($documentedType->ofType())) {
                return true;
            }

            $documentedClassReflector = new ReflectionClass(
                $documentedType->ofType()->string()
            );

            return $documentedClassReflector->isSubclassOf(
                $nativeType->ofType()->string()
            );
        }

        // or type
        if ($documentedType instanceof OrType) {
            if ($nativeType instanceof OrType) {
                foreach ($documentedType->types() as $documentedSubType) {
                    $compatible = false;
                    foreach ($nativeType->types() as $nativeSubType) {
                        $compatible = $this->typeIsCompatible(
                            $configuration,
                            $documentedSubType,
                            $nativeSubType,
                            $depth + 1
                        );
                        if ($compatible) {
                            break;
                        }
                    }
                    if (!$compatible) {
                        return false;
                    }
                }

                return $compatible;
            }

            if (!$nativeType instanceof MixedType) {
                return false;
            }

            $hasArray = false;
            $hasCallable = false;
            $hasNull = false;
            $hasObjectOfType = false;
            $impossibleNatively = false;
            foreach ($documentedType->types() as $documentedSubType) {
                if (
                    $documentedSubType instanceof TraversableType &&
                    $documentedSubType->primaryType() instanceof ArrayType
                ) {
                    $hasArray = true;
                } elseif ($documentedSubType instanceof CallableType) {
                    $hasCallable = true;
                } elseif ($documentedSubType instanceof NullType) {
                    $hasNull = true;
                } elseif (
                    $documentedSubType instanceof ObjectType &&
                    null !== $documentedSubType->ofType()
                ) {
                    $hasObjectOfType = true;
                } else {
                    return true;
                }
            }

            return
                (!$this->useNativeCallable($configuration) && $hasCallable) ||
                ($hasArray && $hasCallable) ||
                ($hasArray && $hasObjectOfType) ||
                ($hasCallable && $hasObjectOfType)
            ;
        }

        // and type
        if ($documentedType instanceof AndType) {
            foreach ($documentedType->types() as $documentedSubType) {
                $compatible = $this->typeIsCompatible(
                    $configuration,
                    $documentedSubType,
                    $nativeType,
                    $depth + 1
                );
                if ($compatible) {
                    return true;
                }
            }

            return false;
        }

        return $nativeType instanceof MixedType;
    }

    /**
     * @param IssueInterface $error
     */
    protected function handleError(IssueInterface $error)
    {
        $this->typeCheck->handleError(func_get_args());

        if ($this->throwOnError()) {
            throw new Exception\ParameterListMergeException($error);
        }

        $this->issues[] = $error;
    }

    private $throwOnError;
    private $issues;
    private $nativeCallableAvailable;
    private $typeCheck;
}
