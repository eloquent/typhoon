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

use Eloquent\Cosmos\ClassName;
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
use Eloquent\Typhoon\Configuration\RuntimeConfiguration;
use Eloquent\Typhoon\Generator\NullifiedType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use ReflectionClass;

class MergeTool
{
    public function __construct()
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());

        $reflectionParameterClass = new ReflectionClass('ReflectionParameter');
        $this->nativeCallableAvailable =
            $reflectionParameterClass->hasMethod('isCallable')
        ;
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
     * @param ClassName|null       $className
     * @param string               $functionName
     * @param ParameterList        $documentedParameterList
     * @param ParameterList        $nativeParameterList
     */
    public function merge(
        RuntimeConfiguration $configuration,
        ClassName $className = null,
        $functionName,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        $this->typeCheck->merge(func_get_args());

        $documentedParameters = $documentedParameterList->parameters();
        $nativeParameters = $nativeParameterList->parameters();

        $parameters = array();
        foreach ($nativeParameters as $index => $nativeParameter) {
            if (!array_key_exists($index, $documentedParameters)) {
                throw new Exception\UndocumentedParameterException(
                    $className,
                    $functionName,
                    $nativeParameter->name()
                );
            }

            $parameters[] = $this->mergeParameter(
                $configuration,
                $className,
                $functionName,
                $documentedParameters[$index],
                $nativeParameter
            );
        }

        $documentedParameterCount = count($documentedParameters);
        $nativeParameterCount = count($nativeParameters);

        if ($documentedParameterList->isVariableLength()) {
            if ($documentedParameterCount > $nativeParameterCount + 1) {
                throw new Exception\DocumentedParameterUndefinedException(
                    $className,
                    $functionName,
                    $documentedParameters[$nativeParameterCount + 1]->name()
                );
            } elseif ($documentedParameterCount === $nativeParameterCount) {
                throw new Exception\DefinedParameterVariableLengthException(
                    $className,
                    $functionName,
                    $nativeParameters[$nativeParameterCount - 1]->name()
                );
            }

            $parameters[] = $documentedParameters[$nativeParameterCount];
        } elseif ($documentedParameterCount > $nativeParameterCount) {
            throw new Exception\DocumentedParameterUndefinedException(
                $className,
                $functionName,
                $documentedParameters[$nativeParameterCount]->name()
            );
        }

        return new ParameterList(
            $parameters,
            $documentedParameterList->isVariableLength()
        );
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param ClassName|null       $className
     * @param string               $functionName
     * @param Parameter            $documentedParameter
     * @param Parameter            $nativeParameter
     */
    protected function mergeParameter(
        RuntimeConfiguration $configuration,
        ClassName $className = null,
        $functionName,
        Parameter $documentedParameter,
        Parameter $nativeParameter
    ) {
        $this->typeCheck->mergeParameter(func_get_args());

        if ($documentedParameter->name() !== $nativeParameter->name()) {
            throw new Exception\DocumentedParameterNameMismatchException(
                $className,
                $functionName,
                $documentedParameter->name(),
                $nativeParameter->name()
            );
        }

        if ($documentedParameter->isByReference() !== $nativeParameter->isByReference()) {
            throw new Exception\DocumentedParameterByReferenceMismatchException(
                $className,
                $functionName,
                $nativeParameter->name(),
                $documentedParameter->isByReference(),
                $nativeParameter->isByReference()
            );
        }

        return new Parameter(
            $documentedParameter->name(),
            $this->mergeType(
                $configuration,
                $className,
                $functionName,
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
     * @param ClassName|null       $className
     * @param string               $functionName
     * @param string               $parameterName
     * @param Type                 $documentedType
     * @param Type                 $nativeType
     *
     * @return Type
     */
    protected function mergeType(
        RuntimeConfiguration $configuration,
        ClassName $className = null,
        $functionName,
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
            throw new Exception\DocumentedParameterTypeMismatchException(
                $className,
                $functionName,
                $parameterName,
                $documentedType,
                $nativeType
            );
        }

        if (TypeEquivalenceComparator::equivalent(
            $documentedType,
            $nativeType
        )) {
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

    private $nativeCallableAvailable;
    private $typeCheck;
}
