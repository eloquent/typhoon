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
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\CompositeType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Visitor;
use Typhoon\Typhoon;

class TyphaxCompiler implements Visitor
{
    public function __construct()
    {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
    }

    /**
     * @param AndType $type
     *
     * @return string
     */
    public function visitAndType(AndType $type)
    {
        $this->typhoon->visitAndType(func_get_args());

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
                ' = '.$subType->accept($this).";\n"
            ;

            if ($return) {
                $return .= " &&\n";
            }
            $return .=
                '    $check'.$index.'($value)'
            ;
        }
        $return .= "\n";

        return $this->createCallback(<<<EOD
$checks
return
$return;
EOD
        );

        return $this->createCallback(
            $checks."\n".
            'return ('.
            $return.
            "\n);"
        );
    }

    /**
     * @param ArrayType $type
     *
     * @return string
     */
    public function visitArrayType(ArrayType $type)
    {
        $this->typhoon->visitArrayType(func_get_args());

        return $this->createCallback(
            'return is_array($value);'
        );
    }

    /**
     * @param BooleanType $type
     *
     * @return string
     */
    public function visitBooleanType(BooleanType $type)
    {
        $this->typhoon->visitBooleanType(func_get_args());

        return $this->createCallback(
            'return is_bool($value);'
        );
    }

    /**
     * @param CallableType $type
     *
     * @return string
     */
    public function visitCallableType(CallableType $type)
    {
        $this->typhoon->visitCallableType(func_get_args());

        return $this->createCallback(
            'return is_callable($value);'
        );
    }

    /**
     * @param FloatType $type
     *
     * @return string
     */
    public function visitFloatType(FloatType $type)
    {
        $this->typhoon->visitFloatType(func_get_args());

        return $this->createCallback(
            'return is_float($value);'
        );
    }

    /**
     * @param IntegerType $type
     *
     * @return string
     */
    public function visitIntegerType(IntegerType $type)
    {
        $this->typhoon->visitIntegerType(func_get_args());

        return $this->createCallback(
            'return is_integer($value);'
        );
    }

    /**
     * @param MixedType $type
     *
     * @return string
     */
    public function visitMixedType(MixedType $type)
    {
        $this->typhoon->visitMixedType(func_get_args());

        return $this->createCallback(
            'return true;'
        );
    }

    /**
     * @param NullType $type
     *
     * @return string
     */
    public function visitNullType(NullType $type)
    {
        $this->typhoon->visitNullType(func_get_args());

        return $this->createCallback(
            'return $value === null;'
        );
    }

    /**
     * @param NumericType $type
     *
     * @return string
     */
    public function visitNumericType(NumericType $type)
    {
        $this->typhoon->visitNumericType(func_get_args());

        return $this->createCallback(
            'return is_numeric($value);'
        );
    }

    /**
     * @param ObjectType $type
     *
     * @return string
     */
    public function visitObjectType(ObjectType $type)
    {
        $this->typhoon->visitObjectType(func_get_args());

        if (null !== $type->ofType()) {
            return $this->createCallback(
                'return $value instanceof \\'.$type->ofType().';'
            );
        }

        return $this->createCallback(
            'return is_object($value);'
        );
    }

    /**
     * @param OrType $type
     *
     * @return string
     */
    public function visitOrType(OrType $type)
    {
        $this->typhoon->visitOrType(func_get_args());

        if (count($type->types()) < 1) {
            return $this->createCallback(
                'return true;'
            );
        }

        $content = '';
        foreach ($type->types() as $subType) {
            if ($content) {
                $content .= "\n";
            }
            $content .=
                '$check'.
                ' = '.
                $subType->accept($this).";\n"
            ;
            $content .=
                'if ($check($value)) {'."\n".
                '    return true;'."\n".
                "}\n"
            ;
        }

        return $this->createCallback(<<<EOD
$content
return false;
EOD
        );

        return $this->createCallback(
            $content.
            'return false;'
        );
    }

    /**
     * @param ResourceType $type
     *
     * @return string
     */
    public function visitResourceType(ResourceType $type)
    {
        $this->typhoon->visitResourceType(func_get_args());

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
     * @param StreamType $type
     *
     * @return string
     */
    public function visitStreamType(StreamType $type)
    {
        $this->typhoon->visitStreamType(func_get_args());

        $readableCheck = null;
        if (null !== $type->readable()) {
            if ($type->readable()) {
                $condition = <<<'EOD'
    false === strpos($streamMetaData['mode'], 'r') &&
    false === strpos($streamMetaData['mode'], '+')
EOD;
            } else {
                $condition = <<<'EOD'
    false !== strpos($streamMetaData['mode'], 'r') ||
    false !== strpos($streamMetaData['mode'], '+')
EOD;
            }

            $readableCheck = <<<EOD

if (
$condition
) {
    return false;
}

EOD;
        }

        $writableCheck = null;
        if (null !== $type->writable()) {
            if ($type->writable()) {
                $condition = <<<'EOD'
    false === strpos($streamMetaData['mode'], 'w') &&
    false === strpos($streamMetaData['mode'], 'a') &&
    false === strpos($streamMetaData['mode'], 'x') &&
    false === strpos($streamMetaData['mode'], 'c') &&
    false === strpos($streamMetaData['mode'], '+')
EOD;
            } else {
                $condition = <<<'EOD'
    false !== strpos($streamMetaData['mode'], 'w') ||
    false !== strpos($streamMetaData['mode'], 'a') ||
    false !== strpos($streamMetaData['mode'], 'x') ||
    false !== strpos($streamMetaData['mode'], 'c') ||
    false !== strpos($streamMetaData['mode'], '+')
EOD;
            }

            $writableCheck = <<<EOD

if (
$condition
) {
    return false;
}

EOD;
        }

        $fetchStreamMetaData = '';
        if (
            null !== $readableCheck ||
            null !== $writableCheck
        ) {
            $fetchStreamMetaData = <<<'EOD'

$streamMetaData = stream_get_meta_data($value);

EOD;
        }

        return $this->createCallback(<<<EOD
if (
    !is_resource(\$value) ||
    'stream' !== get_resource_type(\$value)
) {
    return false;
}
$fetchStreamMetaData$readableCheck$writableCheck
return true;
EOD
        );
    }

    /**
     * @param StringType $type
     *
     * @return string
     */
    public function visitStringType(StringType $type)
    {
        $this->typhoon->visitStringType(func_get_args());

        return $this->createCallback(
            'return is_string($value);'
        );
    }

    /**
     * @param StringableType $type
     *
     * @return string
     */
    public function visitStringableType(StringableType $type)
    {
        $this->typhoon->visitStringableType(func_get_args());

        return $this->createCallback(<<<'EOD'
if (
    is_string($value) ||
    is_integer($value) ||
    is_float($value)
) {
    return true;
}

if (!is_object($value)) {
    return false;
}

$reflector = new \ReflectionObject($value);

return $reflector->hasMethod('__toString');
EOD
        );
    }

    /**
     * @param TraversableType $type
     *
     * @return string
     */
    public function visitTraversableType(TraversableType $type)
    {
        $this->typhoon->visitTraversableType(func_get_args());

        if ($type->primaryType() instanceof ArrayType) {
            $traversableCheck = '';
        } elseif ($type->primaryType() instanceof ObjectType) {
            $traversableCheck = <<<'EOD'
if (!$value instanceof \Traversable) {
    return false;
}


EOD;
        } else {
            $traversableCheck = <<<'EOD'
if (
    !is_array($value) &&
    !$value instanceof \Traversable
) {
    return false;
}


EOD;
        }

        $primaryCheck = $type->primaryType()->accept($this);
        $keyCheck = $type->keyType()->accept($this);
        $valueCheck = $type->valueType()->accept($this);

        return $this->createCallback(<<<EOD
$traversableCheck\$primaryCheck = $primaryCheck;
if (!\$primaryCheck(\$value)) {
    return false;
}

\$keyCheck = $keyCheck;
\$valueCheck = $valueCheck;
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
     * @param TupleType $type
     *
     * @return string
     */
    public function visitTupleType(TupleType $type)
    {
        $this->typhoon->visitTupleType(func_get_args());

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
                $return .= " &&\n";
            }
            $return .= '    $check'.$index.'($value['.$index.'])';
        }
        $return .= "\n";

        return $this->createCallback(<<<EOD
if (
    !is_array(\$value) ||
    array_keys(\$value) !== $expectedKeys
) {
    return false;
}

$checks
return
$return;
EOD
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function createCallback($content)
    {
        $this->typhoon->createCallback(func_get_args());

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

    private $typhoon;
}
