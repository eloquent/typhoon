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

use Eloquent\Typhoon\Documentation\AST\DocumentationBlock;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList\ParameterList;
use Eloquent\Typhoon\Parameter\ParameterList\Exception\InvalidParameterTagException;
use Eloquent\Typhoon\Parameter\ParameterList\Exception\VariableLengthParameterNotLastException;
use Eloquent\Typhoon\Parameter\ParameterList\ParameterListParser;
use Eloquent\Typhoon\Primitive\Boolean;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhax\Lexer\Lexer as TyphaxLexer;
use Eloquent\Typhax\Parser\Parser as TyphaxParser;

class TyphaxParameterListParser implements ParameterListParser
{
  public function __construct(
    TyphaxTranscompiler $typhaxTranscompiler
    , TyphaxLexer $typhaxLexer = null
    , TyphaxParser $typhaxParser = null
  )
  {
    if (null === $typhaxLexer)
    {
      $typhaxLexer = new TyphaxLexer;
    }
    if (null === $typhaxParser)
    {
      $typhaxParser = new TyphaxParser;
    }

    $this->typhaxTranscompiler = $typhaxTranscompiler;
    $this->typhaxLexer = $typhaxLexer;
    $this->typhaxParser = $typhaxParser;
  }

  /**
   * @return TyphaxTranscompiler
   */
  public function typhaxTranscompiler()
  {
    return $this->typhaxTranscompiler;
  }

  /**
   * @return TyphaxLexer
   */
  public function typhaxLexer()
  {
    return $this->typhaxLexer;
  }

  /**
   * @return TyphaxParser
   */
  public function typhaxParser()
  {
    return $this->typhaxParser;
  }

  /**
   * @param DocumentationBlock $documentationBlock
   *
   * @return ParameterList
   */
  public function parseDocumentationBlock(DocumentationBlock $documentationBlock)
  {
    $parameterList = new ParameterList;
    $variableLength = false;

    foreach ($documentationBlock->tags()->byName(new String(static::TAG_PARAMETER)) as $parameterTag)
    {
      if ($variableLength)
      {
        throw new VariableLengthParameterNotLastException(new String($parameter->name()));
      }
      if (!preg_match(static::PATTERN_PARAMETER, $parameterTag->content(), $matches))
      {
        throw new InvalidParameterTagException(new String($parameterTag->content()));
      }

      $parameter = new Parameter;
      $parameter->setType($this->parseTypeSpecification($matches['type']));
      $parameter->setName(new String($matches['name']));

      if (
        array_key_exists('description', $matches)
        && $matches['description']
      )
      {
        $parameter->setDescription(new String($matches['description']));
      }

      $parameterList[] = $parameter;
      $variableLength =
        array_key_exists('variableLength', $matches)
        && $matches['variableLength']
      ;
    }

    $parameterList->setVariableLength(new Boolean($variableLength));

    return $parameterList;
  }

  /**
   * @param string $typeSpecification
   *
   * @return Type
   */
  protected function parseTypeSpecification($typeSpecification)
  {
    $tokens = $this->typhaxLexer->tokens($typeSpecification);

    return $this->typhaxTranscompiler->parse(
      $this->typhaxParser->parseNode($tokens)
    );
  }

  const TAG_PARAMETER = 'param';

  const PATTERN_PARAMETER = '/(?<type>.*)\s+&?\$(?<name>\w+)(?<variableLength>,\.{3})?(?:\s+(?<description>.*))?$/';

  /**
   * @var TyphaxTranscompiler
   */
  protected $typhaxTranscompiler;

  /**
   * @var TyphaxLexer
   */
  protected $typhaxLexer;

  /**
   * @var TyphaxParser
   */
  protected $typhaxParser;
}
