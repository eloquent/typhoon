<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Typhax\Compiler;

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallbackType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Visitor;

class TyphaxCompiler implements Visitor
{
    /**
     * @param AndType
     *
     * @return mixed
     */
    public function visitAndType(AndType $type)
    {
        $callbacks = array();
        foreach ($type->types() as $subType) {
            $callbacks[] =
                "call_user_func(\n".
                $this->indent(
                    $subType->accept($this)
                ).",\n".
                '    $value'."\n".
                ')'
            ;
        }

        if (count($callbacks) < 1) {
            $content = 'return true;';
        } else {
            $content =
                "return (\n".
                $this->indent(implode(" &&\n", $callbacks))."\n".
                ');'
            ;
        }

        return $this->createCallback($content);
    }

    /**
     * @param ArrayType
     *
     * @return mixed
     */
    public function visitArrayType(ArrayType $type)
    {
        return $this->createCallback(
            'return is_array($value);'
        );
    }

    /**
     * @param BooleanType
     *
     * @return mixed
     */
    public function visitBooleanType(BooleanType $type)
    {
        return $this->createCallback(
            'return is_bool($value);'
        );
    }

    /**
     * @param CallbackType
     *
     * @return mixed
     */
    public function visitCallbackType(CallbackType $type)
    {
        return $this->createCallback(
            'return is_callable($value);'
        );
    }

    /**
     * @param FloatType
     *
     * @return mixed
     */
    public function visitFloatType(FloatType $type)
    {
        return $this->createCallback(
            'return is_float($value);'
        );
    }

    /**
     * @param IntegerType
     *
     * @return mixed
     */
    public function visitIntegerType(IntegerType $type)
    {
        return $this->createCallback(
            'return is_integer($value);'
        );
    }

    /**
     * @param MixedType
     *
     * @return mixed
     */
    public function visitMixedType(MixedType $type)
    {
        return $this->createCallback(
            'return true;'
        );
    }

    /**
     * @param NullType
     *
     * @return mixed
     */
    public function visitNullType(NullType $type)
    {
        return $this->createCallback(
            'return $value === null;'
        );
    }

    /**
     * @param ObjectType
     *
     * @return mixed
     */
    public function visitObjectType(ObjectType $type)
    {
        if (null !== $type->ofType()) {
            return $this->createCallback(
                'return $value instanceof '.$type->ofType().';'
            );
        }

        return $this->createCallback(
            'return is_object($value);'
        );
    }

    /**
     * @param OrType
     *
     * @return mixed
     */
    public function visitOrType(OrType $type)
    {
        $callbacks = array();
        foreach ($type->types() as $subType) {
            $callbacks[] =
                "call_user_func(\n".
                $this->indent(
                    $subType->accept($this)
                ).",\n".
                '    $value'."\n".
                ')'
            ;
        }

        if (count($callbacks) < 1) {
            $content = 'return true;';
        } else {
            $content =
                "return (\n".
                $this->indent(implode(" ||\n", $callbacks))."\n".
                ');'
            ;
        }

        return $this->createCallback($content);
    }

    /**
     * @param ResourceType
     *
     * @return mixed
     */
    public function visitResourceType(ResourceType $type)
    {
        if (null !== $type->ofType()) {
            return $this->createCallback(
                "return\n".
                '    is_resource($value) &&'."\n".
                '    get_resource_type($value) === '.
                var_export($type->ofType(), true)."\n".
                ';'
            );
        }

        return $this->createCallback(
            'return is_resource($value);'
        );
    }

    /**
     * @param StringType
     *
     * @return mixed
     */
    public function visitStringType(StringType $type)
    {
        return $this->createCallback(
            'return is_string($value);'
        );
    }

    /**
     * @param TraversableType
     *
     * @return mixed
     */
    public function visitTraversableType(TraversableType $type)
    {
        $primaryCallback =
            "call_user_func(\n".
            $this->indent(
                $type->primaryType()->accept($this),
                2
            ).",\n".
            '        $value'."\n".
            '    )'
        ;
        $keyCallback =
            "call_user_func(\n".
            $this->indent(
                $type->keyType()->accept($this),
                3
            ).",\n".
            '            $key'."\n".
            '        )'
        ;
        $valueCallback =
            "call_user_func(\n".
            $this->indent(
                $type->valueType()->accept($this),
                3
            ).",\n".
            '            $subValue'."\n".
            '        )'
        ;

        return $this->createCallback(<<<EOD
if (
    !$primaryCallback
) {
    return false;
}

foreach (\$value as \$key => \$subValue) {
    if (
        !$keyCallback
    ) {
        return false;
    }
    if (
        !$valueCallback
    ) {
        return false;
    }
}

return true;
EOD
        );
    }

    /**
     * @param TupleType
     *
     * @return mixed
     */
    public function visitTupleType(TupleType $type)
    {
        if (count($type->types()) < 1) {
            return $this->createCallback(
                'return $value === array();'
            );
        }
        $callbacks = array();
        foreach ($type->types() as $index => $subType) {
            $callbacks[] =
                "call_user_func(\n".
                $this->indent(
                    $subType->accept($this)
                ).",\n".
                '    $value['.$index."]\n".
                ')'
            ;
        }

        $expectedKeys =
            'range(0, '.
            (count($callbacks) - 1).
            ')'
        ;

        $tupleCallback = $this->createCallback(
            "    return (\n".
            $this->indent(
                implode(" &&\n", $callbacks),
                2
            )."\n".
            '    );'
        );

        $tupleCallback =
            "call_user_func(\n".
            $this->indent(
                $tupleCallback,
                2
            ).",\n".
            '        $value'."\n".
            '    )'
        ;

        return $this->createCallback(<<<EOD
if (
    !is_array(\$value) ||
    array_keys(\$value) !== $expectedKeys
) {
    return false;
}

if (
    !$tupleCallback
) {
    return false;
}

return true;
EOD
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function createCallback($content) {
        return
            "function(\$value) {\n".
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
}
