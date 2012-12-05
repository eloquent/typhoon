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

use Eloquent\Typhoon\TypeCheck\TypeCheck;
use FilesystemIterator;
use Icecave\Isolator\Isolator;
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
        $tokens = $this->sourceTokens($source);

        while ($token = next($tokens)) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_NAMESPACE:
                        $namespaceName = $this->parseNamespaceName($tokens);
                        $usedClasses = array();
                        break;
                    case T_USE:
                        if ($usedClass = $this->parseUsedClass($tokens)) {
                            list($usedClassName, $usedClassAlias) = $usedClass;
                            $usedClasses[$usedClassName] = $usedClassAlias;
                        }
                        break;
                    case T_CLASS:
                        $classDefinitions[] = new ClassDefinition(
                            $this->parseClassName($tokens),
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
     * @param string $className
     * @param string $source
     *
     * @return ClassDefinition
     * @throws Exception\UndefinedClassException
     */
    public function classBySource($className, $source)
    {
        $this->typeCheck->classBySource(func_get_args());

        foreach ($this->classesBySource($source) as $classDefinition) {
            if ($classDefinition->canonicalClassName() === $className) {
                return $classDefinition;
            }
        }

        throw new Exception\UndefinedClassException($className);
    }

    /**
     * @param array<string|array> &$tokens
     *
     * @return string
     */
    protected function parseNamespaceName(array &$tokens)
    {
        $this->typeCheck->parseNamespaceName(func_get_args());

        $namespaceName = '';
        do {
            $token = next($tokens);

            switch ($token[0]) {
                case T_STRING:
                    $namespaceName .= $token[1];
                    break;
                case T_NS_SEPARATOR:
                    $namespaceName .= '\\';
                    break;
            }
        } while (
            T_STRING === $token[0]
            || T_NS_SEPARATOR === $token[0]
        );

        return $namespaceName;
    }

    /**
     * @param array<string|array> &$tokens
     *
     * @return tuple<string,string|null>|null
     */
    protected function parseUsedClass(array &$tokens)
    {
        $this->typeCheck->parseUsedClass(func_get_args());

        $usedClass = null;
        $token = next($tokens);
        while (
            is_array($token) && (
                T_STRING === $token[0] ||
                T_NS_SEPARATOR === $token[0]
            )
        ) {
            if (null === $usedClass) {
                $usedClass = array('', null);
            }

            $usedClass[0] .= $token[1];
            $token = next($tokens);
        }

        if (null === $usedClass) {
            return null;
        }

        if (
            !is_array($token) ||
            T_AS !== $token[0]
        ) {
            return $usedClass;
        }

        $token = next($tokens);
        if (
            is_array($token) &&
            T_STRING === $token[0]
        ) {
            $usedClass[1] = $token[1];
        }

        return $usedClass;
    }

    /**
     * @param array<string|array> &$tokens
     *
     * @return string
     */
    protected function parseClassName(array &$tokens)
    {
        $this->typeCheck->parseClassName(func_get_args());

        $token = next($tokens);

        return $token[1];
    }

    /**
     * @param string $source
     *
     * @return array<string|array>
     */
    protected function sourceTokens($source)
    {
        $this->typeCheck->sourceTokens(func_get_args());

        $tokens = $this->isolator->token_get_all($source);
        $tokens = array_filter($tokens, function($token) {
            if (!is_array($token)) {
                return true;
            }

            return T_WHITESPACE !== $token[0];
        });

        return $tokens;
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

    private $isolator;
    private $typeCheck;
}
