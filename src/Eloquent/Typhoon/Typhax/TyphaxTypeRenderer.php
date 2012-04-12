<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Typhax;

use Eloquent\Typhax\Lexer\Token;
use Eloquent\Typhoon\Attribute\Attributes;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\Composite\CompositeType;
use Eloquent\Typhoon\Type\Dynamic\DynamicType;
use Eloquent\Typhoon\Type\Inspector\TypeInspector;
use Eloquent\Typhoon\Type\SubTyped\SubTypedType;
use Eloquent\Typhoon\Type\ArrayType;
use Eloquent\Typhoon\Type\BooleanType;
use Eloquent\Typhoon\Type\FloatType;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\MixedType;
use Eloquent\Typhoon\Type\NullType;
use Eloquent\Typhoon\Type\ObjectType;
use Eloquent\Typhoon\Type\Renderer\TypeRenderer;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\TraversableType;
use Eloquent\Typhoon\Type\Type;

class TyphaxTypeRenderer implements TypeRenderer
{
  /**
   * @param Type $type
   *
   * @return string
   */
  public function render(Type $type)
  {
    if ($type instanceof CompositeType)
    {
      return $this->renderComposite($type);
    }

    $class = null;
    if ($type instanceof ObjectType)
    {
      $class = $type->typhoonAttributes()->get(ObjectType::ATTRIBUTE_INSTANCE_OF, null);
    }
    else if ($type instanceof TraversableType)
    {
      $class = $type->typhoonAttributes()->get(TraversableType::ATTRIBUTE_INSTANCE_OF, null);
    }

    if ($class)
    {
      $rendered = $class;
    }
    else
    {
      $rendered = $type->typhoonName();
    }

    if ($type instanceof SubTypedType)
    {
      $rendered .= $this->renderSubTypes($type->typhoonTypes());
    }

    if (null === $class && $type instanceof DynamicType)
    {
      $rendered .= $this->renderAttributes($type->typhoonAttributes());
    }

    return $rendered;
  }

  /**
   * @param CompositeType $composite
   *
   * @return string
   */
  protected function renderComposite(CompositeType $composite)
  {
    $rendered = array();
    foreach ($composite->typhoonTypes() as $type)
    {
      $rendered[] = $this->render($type);
    }

    return implode($composite->typhoonOperator(), $rendered);
  }

  /**
   * @param array $subTypes
   *
   * @return string
   */
  protected function renderSubTypes(array $subTypes)
  {
    if (count($subTypes) < 1)
    {
      return '';
    }

    $rendered = array();
    foreach ($subTypes as $subType)
    {
      $rendered[] = $this->render($subType);
    }

    return
      Token::TOKEN_LESS_THAN
      .implode(Token::TOKEN_COMMA, $rendered)
      .Token::TOKEN_GREATER_THAN
    ;
  }

  /**
   * @param Attributes $attributes
   *
   * @return string
   */
  protected function renderAttributes(Attributes $attributes)
  {
    if (count($attributes) < 1)
    {
      return '';
    }

    return
      Token::TOKEN_PARENTHESIS_OPEN
      .$this->renderHashContents($attributes->values())
      .Token::TOKEN_PARENTHESIS_CLOSE
    ;
  }

  /**
   * @param array $hash
   *
   * @return string
   */
  protected function renderHash(array $hash)
  {
    return
      Token::TOKEN_BRACE_OPEN
      .$this->renderHashContents($hash)
      .Token::TOKEN_BRACE_CLOSE
    ;
  }

  /**
   * @param array $array
   *
   * @return string
   */
  protected function renderArray(array $array)
  {
    return
      Token::TOKEN_SQUARE_BRACKET_OPEN
      .$this->renderArrayContents($array)
      .Token::TOKEN_SQUARE_BRACKET_CLOSE
    ;
  }

  /**
   * @param array $values
   *
   * @return string
   */
  protected function renderHashContents(array $values)
  {
    $rendered = array();
    foreach ($values as $key => $value)
    {
      $rendered[] =
        $this->renderValue($key)
        .Token::TOKEN_COLON
        .$this->renderValue($value)
      ;
    }

    return implode(Token::TOKEN_COMMA, $rendered);
  }

  /**
   * @param array $values
   *
   * @return string
   */
  protected function renderArrayContents(array $values)
  {
    $rendered = array();
    foreach ($values as $value)
    {
      $rendered[] = $this->renderValue($value);
    }

    return implode(Token::TOKEN_COMMA, $rendered);
  }

  /**
   * @param mixed $value
   *
   * @return string
   */
  protected function renderValue($value)
  {
    $valueType = $this->typeInspector()->typeOf($value);

    if ($valueType instanceof ArrayType)
    {
      if ($this->isSequentialArray($value))
      {
        return $this->renderArray($value);
      }
      else
      {
        return $this->renderHash($value);
      }
    }
    if ($valueType instanceof NullType)
    {
      return $this->renderNull();
    }
    if ($valueType instanceof BooleanType)
    {
      return $this->renderBoolean($value);
    }
    if ($valueType instanceof StringType)
    {
      return $this->renderString($value);
    }
    if ($valueType instanceof IntegerType)
    {
      return $this->renderInteger($value);
    }
    if ($valueType instanceof FloatType)
    {
      return $this->renderFloat($value);
    }

    throw new Exception\UnsupportedValueException($value);
  }

  /**
   * @return string
   */
  protected function renderNull()
  {
    return Token::TOKEN_NULL;
  }

  /**
   * @param boolean $boolean
   *
   * @return string
   */
  protected function renderBoolean($boolean)
  {
    return
      $boolean
      ? Token::TOKEN_BOOLEAN_TRUE
      : Token::TOKEN_BOOLEAN_FALSE
    ;
  }

  /**
   * @param string $string
   *
   * @return string
   */
  protected function renderString($string)
  {
    if (preg_match('/^\d+(?:\.\d+)?$/', $string))
    {
      return var_export($string, true);
    }

    return $string;
  }

  /**
   * @param integer $integer
   *
   * @return string
   */
  protected function renderInteger($integer)
  {
    return (string)$integer;
  }

  /**
   * @param float $float
   *
   * @return string
   */
  protected function renderFloat($float)
  {
    $float = (string)$float;
    if (false === strpos($float, '.')) {
      $float .= '.0';
    }

    return $float;
  }

  /**
   * @param array $array
   *
   * @return boolean
   */
  protected function isSequentialArray(array $array)
  {
    return
      0 === count($array)
      || array_keys($array) === range(0, count($array) - 1)
    ;
  }

  /**
   * @return TypeInspector
   */
  protected function typeInspector()
  {
    if (null === $this->typeInspector)
    {
      $this->typeInspector = new TypeInspector;
    }

    return $this->typeInspector;
  }

  /**
   * @var TypeInspector
   */
  protected $typeInspector;
}
