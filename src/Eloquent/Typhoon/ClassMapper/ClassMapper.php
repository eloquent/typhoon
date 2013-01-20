<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use Eloquent\Cosmos\ClassName;
use Eloquent\Typhoon\TypeCheck\TypeCheck;
use FilesystemIterator;
use Icecave\Isolator\Isolator;
use Icecave\Pasta\AST\Type\AccessModifier;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClassMapper
{
    /**
     * @param Isolator|null $isolator
     */
    public function __construct(Isolator $isolator = null)
    {
        $this->typeCheck = TypeCheck::get(__CLASS__, func_get_args());
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * @param string $path
     *
     * @return array<ClassDefinition>
     */
    public function classesByPath($path)
    {
        $this->typeCheck->classesByPath(func_get_args());

        if ($this->isolator->is_dir($path)) {
            return $this->classesByDirectory($path);
        }

        return $this->classesByFile($path);
    }

    /**
     * @param array<string> $paths
     *
     * @return array<ClassDefinition>
     */
    public function classesByPaths(array $paths)
    {
        $this->typeCheck->classesByPaths(func_get_args());

        $classMap = array();
        foreach ($paths as $path) {
            $classMap = array_merge(
                $classMap,
                $this->classesByPath($path)
            );
        }

        return $classMap;
    }

    /**
     * @param string $directoryPath
     *
     * @return array<ClassDefinition>
     */
    public function classesByDirectory($directoryPath)
    {
        $this->typeCheck->classesByDirectory(func_get_args());

        $classDefinitions = array();
        foreach ($this->fileIterator($directoryPath) as $filePathInfo) {
            $classDefinitions = array_merge(
                $classDefinitions,
                $this->classesByFile($filePathInfo->getPathname())
            );
        }

        return $classDefinitions;
    }

    /**
     * @param string $filePath
     *
     * @return array<ClassDefinition>
     */
    public function classesByFile($filePath)
    {
        $this->typeCheck->classesByFile(func_get_args());

        return $this->classesBySource(
            $this->isolator->file_get_contents($filePath)
        );
    }

    /**
     * @param string $source
     *
     * @return array<ClassDefinition>
     */
    public function classesBySource($source)
    {
        $this->typeCheck->classesBySource(func_get_args());

        $classDefinitions = array();
        $namespaceName = null;
        $usedClasses = array();
        $tokens = $this->isolator->token_get_all($source);

        while ($token = next($tokens)) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_NAMESPACE:
                        $namespaceName = $this->parseNamespaceName($tokens);
                        $usedClasses = array();
                        break;
                    case T_USE:
                        $usedClasses[] = $this->parseUsedClass($tokens);
                        break;
                    case T_CLASS:
                        $classDefinitions[] = $this->parseClassDefinition(
                            $tokens,
                            $namespaceName,
                            $usedClasses
                        );
                        break;
                }
            }
        }

        return $classDefinitions;
    }

    /**
     * @param ClassName $className
     * @param string    $source
     *
     * @return ClassDefinition
     * @throws Exception\UndefinedClassException
     */
    public function classBySource(ClassName $className, $source)
    {
        $this->typeCheck->classBySource(func_get_args());

        foreach ($this->classesBySource($source) as $classDefinition) {
            if ($classDefinition->className()->string() === $className->toAbsolute()->string()) {
                return $classDefinition;
            }
        }

        throw new Exception\UndefinedClassException($className);
    }

    /**
     * @param array<string|array> &$tokens
     *
     * @return ClassName
     */
    protected function parseNamespaceName(array &$tokens)
    {
        $this->typeCheck->parseNamespaceName(func_get_args());

        $namespaceAtoms = array();
        do {
            $token = next($tokens);

            switch ($token[0]) {
                case T_STRING:
                    $namespaceAtoms[] = $token[1];
                    break;
            }
        } while (
            T_STRING === $token[0]
            || T_NS_SEPARATOR === $token[0]
            || T_WHITESPACE === $token[0]
        );

        return ClassName::fromAtoms($namespaceAtoms, true);
    }

    /**
     * @param array<string|array> &$tokens
     *
     * @return array<ClassName>
     */
    protected function parseUsedClass(array &$tokens)
    {
        $this->typeCheck->parseUsedClass(func_get_args());

        $usedClassTuple = array();
        $usedClassAtoms = array();

        $token = next($tokens);
        while (
            is_array($token) && (
                T_STRING === $token[0] ||
                T_NS_SEPARATOR === $token[0] ||
                T_WHITESPACE === $token[0]
            )
        ) {
            if (T_STRING === $token[0]) {
                $usedClassAtoms[] .= $token[1];
            }

            $token = next($tokens);
        }
        $usedClassTuple[] = ClassName::fromAtoms($usedClassAtoms, true);

        if (
            !is_array($token) ||
            T_AS !== $token[0]
        ) {
            return $usedClassTuple;
        }

        $token = next($tokens);
        while (
            is_array($token) &&
            T_WHITESPACE === $token[0]
        ) {
            $token = next($tokens);
        }

        if (
            is_array($token) &&
            T_STRING === $token[0]
        ) {
            $usedClassTuple[] = ClassName::fromAtoms(array($token[1]), false);
        }

        return $usedClassTuple;
    }

    /**
     * @param array<string|array>     &$tokens
     * @param ClassName|null          $namespaceName
     * @param array<array<ClassName>> $usedClasses
     *
     * @return ClassDefinition
     */
    protected function parseClassDefinition(array &$tokens, ClassName $namespaceName = null, array $usedClasses)
    {
        $this->typeCheck->parseClassDefinition(func_get_args());

        $className = $this->parseClassName($tokens);
        $methods = array();
        $properties = array();
        $inClassBody = false;
        $accessModifier = null;
        $isStatic = null;
        $source = null;
        while ($token = next($tokens)) {
            $token = $this->normalizeToken($token);

            if ($inClassBody) {
                if ('}' === $token[0]) {
                    break;
                }

                if (
                    T_PUBLIC === $token[0] ||
                    T_PROTECTED === $token[0] ||
                    T_PRIVATE === $token[0] ||
                    T_STATIC === $token[0]
                ) {
                    $lineNumber = $token[2];
                    $token = $this->parseClassMemberModifiers(
                        $token,
                        $tokens,
                        $accessModifier,
                        $isStatic,
                        $source
                    );

                    if (T_FUNCTION === $token[0]) {
                        $methods[] = $this->parseMethod(
                            $token,
                            $tokens,
                            $accessModifier,
                            $isStatic,
                            $source,
                            $lineNumber
                        );
                    } elseif (T_VARIABLE === $token[0]) {
                        $properties[] = $this->parseProperty(
                            $token,
                            $tokens,
                            $accessModifier,
                            $isStatic,
                            $source,
                            $lineNumber
                        );
                    }

                    $accessModifier = null;
                    $isStatic = null;
                    $source = null;
                }
            } elseif ('{' === $token[0]) {
                $inClassBody = true;
            }
        }

        if (null !== $namespaceName) {
            $className = $namespaceName->join($className);
        }

        return new ClassDefinition(
            $className,
            $usedClasses,
            $methods,
            $properties
        );
    }

    /**
     * @param tuple<integer,string,integer> $token
     * @param array<string|array>           &$tokens
     * @param null                          &$accessModifier
     * @param null                          &$isStatic
     * @param null                          &$source
     *
     * @return tuple<integer|string,string,integer|null>
     */
    protected function parseClassMemberModifiers(
        array $token,
        array &$tokens,
        &$accessModifier,
        &$isStatic,
        &$source
    ) {
        $this->typeCheck->parseClassMemberModifiers(func_get_args());

        $isStatic = false;
        $source = '';

        while ($token) {
            $token = $this->normalizeToken($token);
            $source .= $token[1];
            if (
                T_FUNCTION === $token[0] ||
                T_VARIABLE === $token[0]
            ) {
                break;
            } elseif (
                T_PUBLIC === $token[0] ||
                T_PROTECTED === $token[0] ||
                T_PRIVATE === $token[0]
            ) {
                $accessModifier = AccessModifier::instanceByValue(
                    strtolower($token[1])
                );
            } elseif (T_STATIC === $token[0]) {
                $isStatic = true;
            }

            $token = next($tokens);
        }

        return $token;
    }

    /**
     * @param tuple<integer,string,integer> $token
     * @param array<string|array>           &$tokens
     * @param AccessModifier $accessModifier
     * @param boolean        $isStatic
     * @param string         $source
     * @param integer        $lineNumber
     *
     * @return PropertyDefinition
     */
    protected function parseProperty(
        array $token,
        array &$tokens,
        AccessModifier $accessModifier,
        $isStatic,
        $source,
        $lineNumber
    ) {
        $this->typeCheck->parseProperty(func_get_args());

        $name = substr($token[1], 1);

        while ($token = next($tokens)) {
            $token = $this->normalizeToken($token);
            $source .= $token[1];
            if (';' === $token[0]) {
                break;
            }
        }

        return new PropertyDefinition(
            $name,
            $isStatic,
            $accessModifier,
            $lineNumber,
            $source
        );
    }

    /**
     * @param tuple<integer,string,integer> $token
     * @param array<string|array>           &$tokens
     * @param AccessModifier $accessModifier
     * @param boolean        $isStatic
     * @param string         $source
     * @param integer        $lineNumber
     *
     * @return MethodDefinition
     */
    protected function parseMethod(
        array $token,
        array &$tokens,
        AccessModifier $accessModifier,
        $isStatic,
        $source,
        $lineNumber
    ) {
        $this->typeCheck->parseMethod(func_get_args());

        do {
            $token = $this->normalizeToken(next($tokens));
            $source .= $token[1];
        } while (T_WHITESPACE === $token[0]);

        $name = $token[1];

        $bracketDepth = 0;
        while ($token = next($tokens)) {
            $token = $this->normalizeToken($token);
            $source .= $token[1];

            if ('{' === $token[0]) {
                $bracketDepth ++;
            } elseif ('}' === $token[0]) {
                $bracketDepth --;

                if ($bracketDepth < 1) {
                    break;
                }
            }
        }

        return new MethodDefinition(
            $name,
            $isStatic,
            $accessModifier,
            $lineNumber,
            $source
        );
    }

    /**
     * @param array<string|array> &$tokens
     *
     * @return ClassName
     */
    protected function parseClassName(array &$tokens)
    {
        $this->typeCheck->parseClassName(func_get_args());

        do {
            $token = $this->normalizeToken(next($tokens));
        } while (T_WHITESPACE === $token[0]);

        return ClassName::fromAtoms(array($token[1]), false);
    }

    /**
     * @param string $directoryPath
     *
     * @return RecursiveIteratorIterator
     */
    protected function fileIterator($directoryPath)
    {
        $this->typeCheck->fileIterator(func_get_args());

        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directoryPath,
                FilesystemIterator::FOLLOW_SYMLINKS |
                FilesystemIterator::SKIP_DOTS
            )
        );
    }

    /**
     * @param string|tuple<integer,string,integer> $token
     *
     * @return tuple<integer|string,string,integer|null>
     */
    protected function normalizeToken($token)
    {
        $this->typeCheck->normalizeToken(func_get_args());

        if (is_array($token)) {
            return $token;
        }

        return array($token, $token, null);
    }

    private $isolator;
    private $typeCheck;
}
