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

use Eloquent\Typhax\AST\Composite as TyphaxComposite;
use Eloquent\Typhax\AST\Node as TyphaxNode;
use Eloquent\Typhax\AST\Type as TyphaxType;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\Dynamic\DynamicType;
use Eloquent\Typhoon\Type\Registry\Exception\UnregisteredTypeAliasException;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;
use Eloquent\Typhoon\Type\SubTyped\SubTypedType;

class TyphaxTranscompiler
{
  public function __construct(TypeRegistry $typeRegistry)
  {
    $this->typeRegistry = $typeRegistry;
  }

  /**
   * @param TyphaxNode $typhaxNode
   * 
   * @return Type
   */
  public function parse(TyphaxNode $typhaxNode)
  {
    if ($typhaxNode instanceof TyphaxComposite)
    {
      return $this->parseComposite($typhaxNode);
    }
    if ($typhaxNode instanceof TyphaxType)
    {
      return $this->parseType($typhaxNode);
    }

    throw new Exception\UnsupportedTyphaxNodeException(new String(get_class($typhaxNode)));
  }

  /**
   * @param TyphaxComposite $typhaxComposite
   *
   * @return CompositeType
   */
  public function parseComposite(TyphaxComposite $typhaxComposite)
  {
    switch ($typhaxComposite->separator())
    {
      case '|':
        $compositeClass = 'Eloquent\Typhoon\Type\Composite\OrType';
      break;

      case '&':
        $compositeClass = 'Eloquent\Typhoon\Type\Composite\AndType';
      break;

      default:
        throw new Exception\UnsupportedTyphaxNodeException(new String(get_class($typhaxComposite)));
    }

    $composite = new $compositeClass;
    foreach ($typhaxComposite->types() as $typhaxType)
    {
      $composite->addTyphoonType($this->parseType($typhaxType));
    }

    return $composite;
  }

  /**
   * @param TyphaxType $typhaxType
   *
   * @return Type
   */
  public function parseType(TyphaxType $typhaxType)
  {
    try
    {
      $typeClass = $this->typeRegistry->get($typhaxType->name());
    }
    catch (UnregisteredTypeAliasException $e)
    {
      throw new Exception\UnsupportedTyphaxNodeException(new String(get_class($typhaxType)), $e);
    }

    $typeIsDynamic = is_a($typeClass, 'Eloquent\Typhoon\Type\Dynamic\DynamicType', true);
    $typeIsSubTyped = is_a($typeClass, 'Eloquent\Typhoon\Type\SubTyped\SubTypedType', true);

    if (
      $typhaxType->attributes()
      && !$typeIsDynamic
    )
    {
      throw new Exception\UnsupportedTyphaxNodeException(new String(get_class($typhaxType)));
    }
    if (
      $typhaxType->subTypes()
      && !$typeIsSubTyped
    )
    {
      throw new Exception\UnsupportedTyphaxNodeException(new String(get_class($typhaxType)));
    }

    if ($typeIsDynamic)
    {
      $type = new $typeClass($typhaxType->attributes());
    }
    else
    {
      $type = new $typeClass;
    }

    if ($typeIsSubTyped)
    {
      $subTypes = array();
      foreach ($typhaxType->subTypes() as $typhaxSubType)
      {
        $subTypes[] = $this->parseType($typhaxSubType);
      }

      $type->setTyphoonSubTypes($subTypes);
    }
    
    return $type;
  }

  /**
   * @var TypeRegistry
   */
  protected $typeRegistry;
}
