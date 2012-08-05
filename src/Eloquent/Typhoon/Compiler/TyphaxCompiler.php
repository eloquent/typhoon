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

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallbackType;
use Eloquent\Typhax\Type\CompositeType;
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
     * @return string
     */
    public function visitAndType(AndType $type)
    {
        if (count($type->types()) < 1) {
            return $this->createCallback(
                'return true;'
            );
        }

        $checks = '';
        $return = '';
        foreach ($type->types() as $index => $subType) {
            $checks .=
                '$check'.$index.
                ' = '.
                $subType->accept($this).";\n"
            ;

            if ($return) {
                $return .= ' &&';
            }
            $return .=
                "\n".
                '    $check'.$index.
                '($value)'
            ;
        }

        return $this->createCallback(
            $checks."\n".
            'return ('.
            $return.
            "\n);"
        );
    }

    /**
     * @param ArrayType
     *
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
     */
    public function visitOrType(OrType $type)
    {
        if (count($type->types()) < 1) {
            return $this->createCallback(
                'return true;'
            );
        }

        $content = '';
        foreach ($type->types() as $subType) {
            $content .=
                '$check'.
                ' = '.
                $subType->accept($this).";\n"
            ;
            $content .=
                'if ($check($value)) {'."\n".
                '    return true;'."\n".
                "}\n\n"
            ;
        }

        return $this->createCallback(
            $content.
            'return false;'
        );
    }

    /**
     * @param ResourceType
     *
     * @return string
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
     * @return string
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
     * @return string
     */
    public function visitTraversableType(TraversableType $type)
    {
        $primaryCheck =
            '$primaryCheck = '.
            $type->primaryType()->accept($this).';'
        ;
        $keyCheck =
            '$keyCheck = '.
            $type->keyType()->accept($this).';'
        ;
        $valueCheck =
            '$valueCheck = '.
            $type->valueType()->accept($this).';'
        ;

        return $this->createCallback(<<<EOD
$primaryCheck
if (!\$primaryCheck(\$value)) {
    return false;
}

$keyCheck
$valueCheck
foreach (\$value as \$key => \$subValue) {
    if (!\$keyCheck(\$key)) {
        return false;
    }
    if (!\$valueCheck(\$subValue)) {
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
     * @return string
     */
    public function visitTupleType(TupleType $type)
    {
        $typeCount = count($type->types());

        if ($typeCount < 1) {
            return $this->createCallback(
                'return $value === array();'
            );
        }

        $expectedKeys =
            'range(0, '.
            ($typeCount - 1).
            ')'
        ;

        $checks = '';
        $return = '';
        foreach ($type->types() as $index => $subType) {
            $checks .=
                '$check'.$index.
                ' = '.
                $subType->accept($this).";\n"
            ;

            if ($return) {
                $return .= ' &&';
            }
            $return .=
                "\n".
                '    $check'.$index.
                '($value['.$index.'])'
            ;
        }

        return $this->createCallback(<<<EOD
if (
    !is_array(\$value) ||
    array_keys(\$value) !== $expectedKeys
) {
    return false;
}

$checks
return$return
;
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
