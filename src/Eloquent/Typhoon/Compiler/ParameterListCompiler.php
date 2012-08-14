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

use Eloquent\Typhax\Renderer\TypeRenderer;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Eloquent\Typhoon\Parameter\Visitor;
use Typhoon\Typhoon;

class ParameterListCompiler implements Visitor
{
    /**
     * @param TyphaxCompiler|null $typhaxCompiler
     * @param TypeRenderer|null $typeRenderer
     */
    public function __construct(
        TyphaxCompiler $typhaxCompiler = null,
        TypeRenderer $typeRenderer = null
    ){
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $typhaxCompiler) {
            $typhaxCompiler = new TyphaxCompiler;
        }
        if (null === $typeRenderer) {
            $typeRenderer = new TypeRenderer;
        }

        $this->typhaxCompiler = $typhaxCompiler;
        $this->typeRenderer = $typeRenderer;
    }

    /**
     * @return TyphaxCompiler
     */
    public function typhaxCompiler()
    {
        $this->typhoon->typhaxCompiler(func_get_args());

        return $this->typhaxCompiler;
    }

    /**
     * @return TypeRenderer
     */
    public function typeRenderer()
    {
        $this->typhoon->typeRenderer(func_get_args());

        return $this->typeRenderer;
    }

    /**
     * @param Parameter $parameter
     *
     * @return string
     */
    public function visitParameter(Parameter $parameter)
    {
        $this->typhoon->visitParameter(func_get_args());

        $check = $parameter->type()->accept(
            $this->typhaxCompiler()
        );

        $parameterName = var_export(
            $parameter->name(),
            true
        );
        $expectedType = var_export(
            $parameter->type()->accept($this->typeRenderer()),
            true
        );

        return $this->createCallback(
            '$argument, $index',
            <<<EOD
\$check = $check;
if (!\$check(\$argument)) {
    throw new UnexpectedArgumentValueException($parameterName, \$index, \$argument, $expectedType);
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
        $this->typhoon->visitParameterList(func_get_args());

        $parameters = $parameterList->parameters();
        $parameterCount = count($parameters);

        if ($parameterCount < 1) {
            return <<<EOD
if (count(\$arguments) > 0) {
    throw new UnexpectedArgumentException(0, \$arguments[0]);
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
                $parameterIndex = $i - 1;
                $parameterName = var_export(
                    $parameters[$parameterIndex]->name(),
                    true
                );
                $expectedType = var_export(
                    $parameters[$parameterIndex]
                        ->type()
                        ->accept($this->typeRenderer())
                    ,
                    true
                );

                $missingParameterContent .= <<<EOD
    if (\$argumentCount < $i) {
        throw new MissingArgumentException($parameterName, $parameterIndex, $expectedType);
    }

EOD;
            }
            $parameterIndex = $requiredParameterCount - 1;
            $parameterName = var_export(
                $parameters[$parameterIndex]->name(),
                true
            );
            $expectedType = var_export(
                $parameters[$parameterIndex]
                    ->type()
                    ->accept($this->typeRenderer())
                ,
                true
            );
            $missingParameterContent .= <<<EOD
    throw new MissingArgumentException($parameterName, $parameterIndex, $expectedType);
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
    throw new UnexpectedArgumentException($parameterCount, \$arguments[$parameterCount]);
}
EOD;
            if ($requiredParameterCount > 0) {
                $maxArgumentLengthCheck =
                    ' elseif '.$maxArgumentLengthCheck
                ;
            } else {
                $maxArgumentLengthCheck =
                    "\nif ".$maxArgumentLengthCheck
                ;
            }
        }

        $parameterChecks = '';
        foreach ($parameters as $index => $parameter) {
            $parameterChecks .= "\n";

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
\$argumentCount = count(\$arguments);$missingParameterContent$maxArgumentLengthCheck$parameterChecks
EOD
        ;
    }

    /**
     * @param string $parameters
     * @param string $content
     *
     * @return string
     */
    protected function createCallback($parameters, $content)
    {
        $this->typhoon->createCallback(func_get_args());

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
    protected function indent($content, $depth = 1)
    {
        $this->typhoon->indent(func_get_args());

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
    private $typhaxRenderer;
    private $typhoon;
}
