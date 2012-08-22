<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Typhax\Type\AndType;
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
use Typhoon\Typhoon;
use ReflectionClass;

class NativeParameterListMergeTool
{
    public function __construct()
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());

        $reflectionParameterClass = new ReflectionClass('ReflectionParameter');
        $this->callableHintsAvailable =
            $reflectionParameterClass->hasMethod('isCallable')
        ;
    }

    /**
     * @param string $functionName
     * @param ParameterList $documentedParameterList
     * @param ParameterList $nativeParameterList
     */
    public function merge(
        $functionName,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        $this->typhoon->merge(func_get_args());

        $documentedParameters = $documentedParameterList->parameters();
        $nativeParameters = $nativeParameterList->parameters();

        $parameters = array();
        foreach ($nativeParameters as $index => $nativeParameter) {
            if (!array_key_exists($index, $documentedParameters)) {
                throw new Exception\UndocumentedParameterException(
                    $functionName,
                    $nativeParameter->name()
                );
            }

            $parameters[] = $this->mergeParameter(
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
                    $functionName,
                    $documentedParameters[$nativeParameterCount + 1]->name()
                );
            } elseif ($documentedParameterCount === $nativeParameterCount) {
                throw new Exception\DefinedParameterVariableLengthException(
                    $functionName,
                    $nativeParameters[$nativeParameterCount - 1]->name()
                );
            }

            $parameters[] = $documentedParameters[$nativeParameterCount];
        } elseif ($documentedParameterCount > $nativeParameterCount) {
            throw new Exception\DocumentedParameterUndefinedException(
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
     * @param string $functionName
     * @param Parameter $documentedParameter
     * @param Parameter $nativeParameter
     */
    protected function mergeParameter(
        $functionName,
        Parameter $documentedParameter,
        Parameter $nativeParameter
    ) {
        $this->typhoon->mergeParameter(func_get_args());

        if ($documentedParameter->name() !== $nativeParameter->name()) {
            throw new Exception\DocumentedParameterNameMismatchException(
                $functionName,
                $documentedParameter->name(),
                $nativeParameter->name()
            );
        }

        if ($documentedParameter->isByReference() !== $nativeParameter->isByReference()) {
            throw new Exception\DocumentedParameterByReferenceMismatchException(
                $functionName,
                $nativeParameter->name(),
                $documentedParameter->isByReference(),
                $nativeParameter->isByReference()
            );
        }

        return new Parameter(
            $documentedParameter->name(),
            $this->mergeType(
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
     * @param string $functionName
     * @param string $parameterName
     * @param Type $documentedType
     * @param Type $nativeType
     *
     * @return Type
     */
    protected function mergeType(
        $functionName,
        $parameterName,
        Type $documentedType,
        Type $nativeType
    ) {
        $this->typhoon->mergeType(func_get_args());

        if (!$this->typeIsCompatible($documentedType, $nativeType)) {
            throw new Exception\DocumentedParameterTypeMismatchException(
                $functionName,
                $parameterName,
                $documentedType,
                $nativeType
            );
        }

        return $documentedType;
    }

    /**
     * @param Type $documentedType
     * @param Type $nativeType
     *
     * @return boolean
     */
    protected function typeIsCompatible(
        Type $documentedType,
        Type $nativeType
    ) {
        $this->typhoon->typeIsCompatible(func_get_args());

        // callable
        if (
            $this->callableHintsAvailable &&
            $documentedType instanceof CallableType
        ) {
            return $nativeType instanceof CallableType;
        }

        // null
        if ($documentedType instanceof NullType) {
            return $nativeType instanceof NullType;
        }

        // traversable
        if ($documentedType instanceof TraversableType) {
            return
                !$documentedType->primaryType() instanceof ArrayType ||
                $nativeType instanceof TraversableType
            ;
        }

        // object of type
        if (
            $documentedType instanceof ObjectType &&
            null !== $documentedType->ofType()
        ) {
            if (!$nativeType instanceof ObjectType) {
                return false;
            }
            if ($nativeType->ofType() === $documentedType->ofType()) {
                return true;
            }

            $documentedClassReflector = new ReflectionClass(
                $documentedType->ofType()
            );

            return $documentedClassReflector->isSubclassOf(
                $nativeType->ofType()
            );
        }

        // or type
        if ($documentedType instanceof OrType) {
            if ($nativeType instanceof OrType) {
                foreach ($documentedType->types() as $documentedSubType) {
                    $compatible = false;
                    foreach ($nativeType->types() as $nativeSubType) {
                        $compatible = $this->typeIsCompatible(
                            $documentedSubType,
                            $nativeSubType
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
                (!$this->callableHintsAvailable && $hasCallable) ||
                ($hasArray && $hasCallable) ||
                ($hasArray && $hasObjectOfType) ||
                ($hasCallable && $hasObjectOfType)
            ;
        }

        // and type
        if ($documentedType instanceof AndType) {
            foreach ($documentedType->types() as $documentedSubType) {
                $compatible = $this->typeIsCompatible(
                    $documentedSubType,
                    $nativeType
                );
                if ($compatible) {
                    return true;
                }
            }

            return false;
        }

        return $nativeType instanceof MixedType;
    }

    protected $callableHintsAvailable;
    private $typhoon;
}
