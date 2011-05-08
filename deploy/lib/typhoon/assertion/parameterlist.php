<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Assertion;

use Typhoon;
use Typhoon\Assertion;
use Typhoon\ParameterList as ParameterListObject;
use Typhoon\ParameterList\Exception\MissingArgument;
use Typhoon\ParameterList\Exception\UnexpectedArgument;
use Typhoon\Primitive\Integer;
use Typhoon\Type\Exception\UnexpectedType;

class ParameterList implements Assertion
{
  public function __construct()
  {
    $this->parameterList = new ParameterListObject;
  }

  public function assert()
  {
    $index = -1;

    foreach ($this->arguments as $index => $value)
    {
      $indexPrimitive = new Integer($index);
      $parameter = null;

      if (isset($this->parameterList[$index]))
      {
        $parameter = $this->parameterList[$index];
      }
      elseif ($this->parameterList->variableLength())
      {
        $parameter = $this->parameterList[count($this->parameterList) - 1];
      }

      if ($parameter)
      {
        $assertion = $this->typeAssertion();
        $assertion->setType($parameter->type());
        $assertion->setValue($value);

        try
        {
          $assertion->assert();
        }
        catch (UnexpectedType $e)
        {
          throw new UnexpectedArgument($value, $indexPrimitive, $parameter, $e);
        }

        continue;
      }

      throw new UnexpectedArgument($value, $indexPrimitive);
    }

    $index ++;

    if (count($this->parameterList) <= $index) return;

    $parameter = $this->parameterList[$index];

    if ($parameter->optional()) return;

    throw new MissingArgument(new Integer($index), $parameter);
  }

  /**
   * @return Type
   */
  public function typeAssertion()
  {
    return Typhoon::instance()->typeAssertion();
  }

  /**
   * @param ParameterListObject $parameterList
   */
  public function setParameterList(ParameterListObject $parameterList)
  {
    $this->parameterList = $parameterList;
  }

  /**
   * @return ParameterListObject
   */
  public function parameterList()
  {
    return $this->parameterList;
  }

  /**
   * @param array $arguments
   */
  public function setArguments(array $arguments)
  {
    foreach ($arguments as $index => $argument) new Integer($index);
    
    $this->arguments = $arguments;
  }

  /**
   * @return array
   */
  public function arguments()
  {
    return $this->arguments;
  }

  /**
   * @var ParameterListObject
   */
  protected $parameterList;

  /**
   * @var array
   */
  protected $arguments = array();
}