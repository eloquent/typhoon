<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Compiler;

use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parameter\Visitor;

class ParameterListCompiler implements Visitor
{
    public function __construct(
        TyphaxCompiler $typhaxCompiler = null
    ){
        if (null === $typhaxCompiler) {
            $typhaxCompiler = new TyphaxCompiler;
        }

        $this->typhaxCompiler = $typhaxCompiler;
    }

    /**
     * @return TyphaxCompiler
     */
    public function typhaxCompiler()
    {
        return $this->typhaxCompiler;
    }

    /**
     * @param Parameter $parameter
     *
     * @return string
     */
    public function visitParameter(Parameter $parameter)
    {
        $check = $parameter->type()->accept(
            $this->typhaxCompiler()
        );

        return $this->createCallback(
            '$argument, $index',
            <<<EOD
\$check = $check;
if (!\$check(\$argument)) {
    throw new \InvalidArgumentException("Unexpected argument for parameter '{$parameter->name()}' at index ".\$index.".");
}
EOD
        );
    }

    /**
     * @param ParameterList $parameterList
     *
     * @return string
     */
    public function visitParameterList(ParameterList $parameterList)
    {
        $parameters = $parameterList->parameters();
        $parameterCount = count($parameters);

        if ($parameterCount < 1) {
            return $this->createCallback(
                'array $arguments',
                <<<EOD
if (count(\$arguments) > 0) {
    throw new \InvalidArgumentException("Unexpected argument at index 1.");
}
EOD
            );
        }

        $mandatoryParameterCount = $parameterCount;
        if ($parameterList->isVariableLength()) {
            $mandatoryParameterCount --;
        }
        $parameterCountPlusOne = $parameterCount + 1;

        $missingParameterContent = '';
        for ($i = 1; $i < $mandatoryParameterCount; $i ++) {
            $parameterName = var_export($parameters[$i - 1]->name(), true);

            $missingParameterContent .= <<<EOD
    if (\$argumentCount < $i) {
        throw new \InvalidArgumentException("Missing argument for parameter $parameterName.");
    }

EOD;
        }
        $parameterName = var_export($parameters[$mandatoryParameterCount - 1]->name(), true);
        $missingParameterContent .= <<<EOD
    throw new \InvalidArgumentException("Missing argument for parameter $parameterName.");
EOD;

        $maxArgumentLengthCheck = '';
        if (!$parameterList->isVariableLength()) {
            $maxArgumentLengthCheck = <<<EOD
 elseif (\$argumentCount > $parameterCount) {
    throw new \InvalidArgumentException("Unexpected argument at index $parameterCountPlusOne.");
}
EOD;
        }

        $parameterChecks = '';
        foreach ($parameters as $index => $parameter) {
            if ($parameterChecks) {
                $parameterChecks .= "\n";
            }

            $check = $parameter->accept($this);

            if (
                $parameterList->isVariableLength() &&
                $index === $parameterCount - 1
            ) {
                $parameterChecks .= <<<EOD

\$check = $check;
for (\$i = $index; \$i < \$argumentCount; \$i ++) {
    \$check(\$arguments[\$i], \$i);
}
EOD;
            } else {
                $parameterChecks .= <<<EOD

\$check = $check;
\$check(\$arguments[$index], $index);
EOD;
            }
        }

        return $this->createCallback(
            'array $arguments',
            <<<EOD
\$argumentCount = count(\$arguments);
if (\$argumentCount < $mandatoryParameterCount) {
$missingParameterContent
}$maxArgumentLengthCheck
$parameterChecks
EOD
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function createCallback($parameters, $content) {
        return
            "function($parameters) {\n".
            $this->indent($content)."\n".
            '}'
        ;
    }

    /**
     * @param string $content
     * @param integer $depth
     *
     * @return string
     */
    protected function indent($content, $depth = 1) {
        $indent = str_repeat('    ', $depth);
        $lines = explode("\n", $content);
        $lines = array_map(function($line) use($indent) {
            if (!$line) {
                return '';
            }

            return $indent.$line;
        }, $lines);

        return implode("\n", $lines);
    }

    private $typhaxCompiler;
}
