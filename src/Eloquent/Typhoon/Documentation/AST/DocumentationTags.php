<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Documentation\AST;

use Eloquent\Typhoon\Collection\Collection;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\ObjectType;

class DocumentationTags extends Collection
{
  /**
   * @param String $name
   *
   * @return DocumentationTags
   */
  public function byName(String $name)
  {
    $tags = new DocumentationTags;
    foreach ($this->values() as $tag)
    {
      if ($name->value() === $tag->name())
      {
        $tags->set(NULL, $tag);
      }
    }

    return $tags;
  }

  /**
   * @return IntegerType
   */
  protected function keyType()
  {
    return new IntegerType;
  }

  /**
   * @param mixed $key
   *
   * @return ObjectType
   */
  protected function valueType($key)
  {
    return new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => __NAMESPACE__.'\DocumentationTag',
    ));
  }
}
