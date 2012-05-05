<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Assertion;

use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;

class ParameterAssertion implements Assertion
{
  public function __construct()
  {
    $this->parameter = new Parameter;
  }

  public function assert()
  {
    $assertion = $this->typeAssertion();

    try
    {
      $assertion->assert();
    }
    catch (Exception\UnexpectedTypeException $e)
    {
      if ($parameterName = $this->parameter->name())
      {
        $parameterName = new String($parameterName);
      }

      throw new Exception\UnexpectedArgumentException(
        $e->value()
        , new Integer($this->index)
        , $e->expectedType()
        , $parameterName
        , $e->typeInspector()
        , $e->typeRenderer()
        , $e
      );
    }
  }

  /**
   * @param Parameter $parameter
   */
  public function setParameter(Parameter $parameter)
  {
    $this->parameter = $parameter;
  }

  /**
   * @return Parameter
   */
  public function parameter()
  {
    return $this->parameter;
  }

  /**
   * @param mixed $value
   */
  public function setValue($value)
  {
    $this->value = $value;
  }

  /**
   * @return mixed
   */
  public function value()
  {
    return $this->value;
  }

  /**
   * @param Integer $index
   */
  public function setIndex(Integer $index)
  {
    $this->index = $index->value();
  }

  /**
   * @return integer
   */
  public function index()
  {
    return $this->index;
  }

  /**
   * @return TypeAssertion
   */
  protected function typeAssertion()
  {
    $assertion = new TypeAssertion;
    $assertion->setType($this->parameter->type());
    $assertion->setValue($this->value);

    return $assertion;
  }

  /**
   * @var Parameter
   */
  protected $parameter;

  /**
   * @var mixed
   */
  protected $value;

  /**
   * @var integer
   */
  protected $index = 0;
}
