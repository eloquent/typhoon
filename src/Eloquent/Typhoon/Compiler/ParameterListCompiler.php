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
            return <<<EOD
if (count(\$arguments) > 0) {
    throw new \InvalidArgumentException("Unexpected argument at index 1.");
}
EOD
            ;
        }

        $requiredParameterCount = count(
            $parameterList->requiredParameters()
        );
        $parameterCountPlusOne = $parameterCount + 1;

        $missingParameterContent = '';
        if ($requiredParameterCount > 0) {
            for ($i = 1; $i < $requiredParameterCount; $i ++) {
                $parameterName = var_export($parameters[$i - 1]->name(), true);

                $missingParameterContent .= <<<EOD
    if (\$argumentCount < $i) {
        throw new \InvalidArgumentException("Missing argument for parameter $parameterName.");
    }

EOD;
            }
            $parameterName = var_export($parameters[$requiredParameterCount - 1]->name(), true);
            $missingParameterContent .= <<<EOD
    throw new \InvalidArgumentException("Missing argument for parameter $parameterName.");
EOD;

            $missingParameterContent = <<<EOD
if (\$argumentCount < $requiredParameterCount) {
$missingParameterContent
}
EOD;
        }

        $maxArgumentLengthCheck = '';
        if (!$parameterList->isVariableLength()) {
            $maxArgumentLengthCheck = <<<EOD
(\$argumentCount > $parameterCount) {
    throw new \InvalidArgumentException("Unexpected argument at index $parameterCountPlusOne.");
}
EOD;
            if ($requiredParameterCount > 0) {
                $maxArgumentLengthCheck =
                    ' elseif '.$maxArgumentLengthCheck
                ;
            } else {
                $maxArgumentLengthCheck =
                    'if '.$maxArgumentLengthCheck
                ;
            }
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
                $parameterCheck = <<<EOD

\$check = $check;
for (\$i = $index; \$i < \$argumentCount; \$i ++) {
    \$check(\$arguments[\$i], \$i);
}
EOD;
            } else {
                $parameterCheck = <<<EOD

\$check = $check;
\$check(\$arguments[$index], $index);
EOD;
            }

            if ($parameter->isOptional()) {
                $parameterCheck = $this->indent($parameterCheck);
                $parameterCheck = <<<EOD

if (\$argumentCount > $index) {{$parameterCheck}
}
EOD;
            }
            $parameterChecks .= $parameterCheck;
        }

        return <<<EOD
\$argumentCount = count(\$arguments);
$missingParameterContent$maxArgumentLengthCheck
$parameterChecks
EOD
        ;
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
