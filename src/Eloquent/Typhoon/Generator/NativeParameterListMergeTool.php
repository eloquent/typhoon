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

use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Typhoon\Typhoon;

class NativeParameterListMergeTool
{
    public function __construct()
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
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
     * @param string $functionName,
     * @param Parameter $documentedParameter,
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
            $documentedParameter->type(),
            $documentedParameter->description(),
            $nativeParameter->isOptional(),
            $documentedParameter->isByReference()
        );
    }

    private $typhoon;
}
