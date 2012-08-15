<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser;

use Eloquent\Blox\AST\DocumentationBlock;
use Eloquent\Blox\AST\DocumentationTag;
use Eloquent\Blox\AST\Visitor;
use Eloquent\Blox\BloxParser;
use Eloquent\Blox\DocumentationBlockParser;
use Eloquent\Typhax\Parser\Parser as TyphaxParser;
use Eloquent\Typhax\Parser\Exception\UnexpectedTokenException;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use Typhoon\Typhoon;

class ParameterListParser implements Visitor
{
    public function __construct(
        TyphaxParser $typhaxParser = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        if (null === $typhaxParser) {
            $typhaxParser = new TyphaxParser;
        }

        $this->typhaxParser = $typhaxParser;
    }

    /**
     * @return TyphaxParser
     */
    public function typhaxParser()
    {
        $this->typhoon->typhaxParser(func_get_args());

        return $this->typhaxParser;
    }

    /**
     * @param string $blockComment
     * @param DocumentationBlockParser $documentationParser
     *
     * @return ParameterList
     */
    public function parseBlockComment(
        $blockComment,
        DocumentationBlockParser $documentationParser = null
    ) {
        $this->typhoon->parseBlockComment(func_get_args());

        if (null === $documentationParser) {
            $documentationParser = new BloxParser;
        }

        return
            $documentationParser
            ->parseBlockComment($blockComment)
            ->accept($this)
        ;
    }

    /**
     * @param DocumentationBlock $documentationBlock
     *
     * @return ParameterList
     */
    public function visitDocumentationBlock(DocumentationBlock $documentationBlock)
    {
        $this->typhoon->visitDocumentationBlock(func_get_args());

        $parameters = array();
        $paramTags = $documentationBlock->tagsByName('param');
        $lastIndex = count($paramTags) - 1;
        $variableLength = false;
        foreach ($paramTags as $index => $paramTag) {
            $parameters[] = $paramTag->accept($this);
            if ($index === $lastIndex) {
                $variableLength = preg_match(
                    static::PATTERN_VARIABLE_LENGTH,
                    $paramTag->content()
                ) && true;
            }
        }

        return new ParameterList($parameters, $variableLength);
    }

    /**
     * @param DocumentationTag $documentationTag
     *
     * @return Parameter
     */
    public function visitDocumentationTag(DocumentationTag $documentationTag)
    {
        $this->typhoon->visitDocumentationTag(func_get_args());

        $position = 0;
        $type = $this->parseType(
            $documentationTag->content() ?: '',
            $position
        );
        $name = $this->parseName(
            $documentationTag->content(),
            $position
        );
        $description = $this->parseDescription(
            $documentationTag->content(),
            $position
        );
        $optional = $this->parseOptional(
            $documentationTag->content()
        );

        return new Parameter(
            $name,
            $type,
            $description,
            $optional
        );
    }

    const PATTERN_VARIABLE_LENGTH = '/^.*\s&?\$\w+,\.{3}(?:$|\s)/';

    /**
     * @param string $content
     * @param integer &$position
     *
     * @return Type
     */
    protected function parseType($content, &$position)
    {
        $this->typhoon->parseType(func_get_args());

        try {
            return $this->typhaxParser()->parse(
                $content,
                $position
            );
        } catch (UnexpectedTokenException $e) {
            throw new Exception\UnexpectedContentException(
                'type',
                $e->position(),
                $e
            );
        }
    }

    /**
     * @param string $content
     * @param integer &$position
     *
     * @return string
     */
    protected function parseName($content, &$position)
    {
        $this->typhoon->parseName(func_get_args());

        return $this->parseContent(
            $content,
            $position,
            '/^\s*&?\$(\w+)(?:,\.{3})?/',
            false,
            'name'
        );
    }

    /**
     * @param string $content
     * @param integer &$position
     *
     * @return string|null
     */
    protected function parseDescription($content, &$position)
    {
        $this->typhoon->parseDescription(func_get_args());

        return $this->parseContent(
            $content,
            $position,
            '/^\s*(.*)$/',
            true,
            'description'
        );
    }

    /**
     * @param string $content
     *
     * @return boolean
     */
    protected function parseOptional($content)
    {
        $this->typhoon->parseOptional(func_get_args());

        if (preg_match(static::PATTERN_VARIABLE_LENGTH, $content)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $content
     * @param integer &$position
     * @param string $pattern
     * @param boolean $optional
     * @param string $type
     *
     * @return string|null
     */
    protected function parseContent($content, &$position, $pattern, $optional, $type)
    {
        $this->typhoon->parseContent(func_get_args());

        $subject = substr($content, $position - 1);
        if (
            !preg_match($pattern, $subject, $matches) ||
            '' === $matches[1]
        ) {
            if ($optional) {
                return null;
            }

            throw new Exception\UnexpectedContentException(
                $type,
                $position
            );
        }

        $position += strlen($matches[0]);

        return $matches[1];
    }

    private $typhaxParser;
    private $typhoon;
}
