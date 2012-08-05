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
use Eloquent\Typhax\Parser\Parser as TyphaxParser;
use Eloquent\Typhax\Parser\Exception\UnexpectedTokenException;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;

class ParameterListParser implements Visitor
{
    public function __construct(
        TyphaxParser $typhaxParser = null
    ) {
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
        return $this->typhaxParser;
    }

    /**
     * @param DocumentationBlock $documentationBlock
     *
     * @return ParameterList
     */
    public function visitDocumentationBlock(DocumentationBlock $documentationBlock)
    {
        $parameters = array();
        $paramTags = $documentationBlock->tagsByName('param');
        $lastIndex = count($paramTags) - 1;
        $variableLength = false;
        foreach ($paramTags as $index => $paramTag) {
            $parameters[] = $paramTag->accept($this);
            if ($index === $lastIndex) {
                $variableLength = preg_match(
                    '/^.*\s\$\w+,\.{3}\s/',
                    $paramTag->content()
                );
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
        $position = 0;
        $type = $this->parseType(
            $documentationTag->content(),
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

        return new Parameter(
            $type,
            $name,
            $description
        );
    }

    /**
     * @param string $content
     * @param integer &$position
     *
     * @return Type
     */
    protected function parseType($content, &$position)
    {
        try {
            return $this->typhaxParser->parse(
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
        return $this->parseContent(
            $content,
            $position,
            '/^\s*\$(\w+)(?:,\.{3})?/',
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
     * @param integer &$position
     * @param string $pattern
     * @param boolean $optional
     * @param string $type
     *
     * @return string|null
     */
    protected function parseContent($content, &$position, $pattern, $optional, $type)
    {
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
}
