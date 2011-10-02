<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Assertion;

use Ezzatron\Typhoon\Typhoon;
use Ezzatron\Typhoon\Parameter\ParameterList\ParameterList;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;

class ParameterListAssertion implements Assertion
{
  public function __construct()
  {
    $this->parameterList = new ParameterList;
  }

  public function assert()
  {
    $index = -1;

    foreach ($this->arguments as $index => $value)
    {
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
        catch (Exception\UnexpectedTypeException $e)
        {
          if ($parameterName = $parameter->name())
          {
            $parameterName = new String($parameterName);
          }

          throw new Exception\UnexpectedArgumentException(
            new String($e->typeName())
            , new Integer($index)
            , new String($e->expectedTypeName())
            , $parameterName
            , $e
          );
        }

        continue;
      }

      $typeName = Typhoon::instance()->typeRenderer()->render(
        Typhoon::instance()->typeInspector()->typeOf($value)
      );

      throw new Exception\UnexpectedArgumentException(
        new String((string)$typeName)
        , new Integer($index)
      );
    }

    $index ++;

    if (count($this->parameterList) <= $index)
    {
      return;
    }

    $parameter = $this->parameterList[$index];

    if ($parameter->optional())
    {
      return;
    }

    throw new Exception\MissingArgumentException(new Integer($index), $parameter);
  }

  /**
   * @return Type
   */
  public function typeAssertion()
  {
    return Typhoon::instance()->typeAssertion();
  }

  /**
   * @param ParameterList $parameterList
   */
  public function setParameterList(ParameterList $parameterList)
  {
    $this->parameterList = $parameterList;
  }

  /**
   * @return ParameterList
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
    foreach ($arguments as $index => $argument)
    {
      new Integer($index);
    }
    
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
   * @var ParameterList
   */
  protected $parameterList;

  /**
   * @var array
   */
  protected $arguments = array();
}