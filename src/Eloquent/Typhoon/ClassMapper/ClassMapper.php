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

use FilesystemIterator;
use Icecave\Isolator\Isolator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClassMapper
{
    public function __construct(Isolator $isolator = null)
    {
        $this->isolator = Isolator::get($isolator);
    }

    public function classesByDirectory($directoryPath)
    {
        $classes = array();

        foreach ($this->fileIterator($directoryPath) as $filePathInfo) {
            $filePath = $filePathInfo->getPathname();
            foreach ($this->classesByFile($filePath) as $class) {
                $classes[$class] = $filePath;
            }
        }

        return $classes;
    }

    public function classesByFile($filePath)
    {
        return $this->classesBySource(
            $this->isolator->file_get_contents($filePath)
        );
    }

    public function classesBySource($source)
    {
        $classes = array();
        $namespace = null;
        $tokens = $this->sourceTokens($source);

        while ($token = next($tokens)) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_NAMESPACE:
                        $namespace = $this->parseNamespace($tokens);
                        break;
                    case T_CLASS:
                        $classes[] = $this->parseClass($tokens, $namespace);
                        break;
                }
            }
        }

        return $classes;
    }

    protected function parseNamespace(array &$tokens) {
        $namespace = '';

        do {
            $token = next($tokens);

            switch ($token[0]) {
                case T_STRING:
                    $namespace .= $token[1];
                    break;
                case T_NS_SEPARATOR:
                    $namespace .= '\\';
                    break;
            }
        } while (
            T_STRING === $token[0]
            || T_NS_SEPARATOR === $token[0]
        );

        return $namespace;
    }

    protected function parseClass(array &$tokens, $namespace) {
        $token = next($tokens);
        $class = $token[1];
        if (null !== $namespace) {
            $class = $namespace.'\\'.$class;
        }

        return $class;
    }

    protected function sourceTokens($source)
    {
        $tokens = $this->isolator->token_get_all($source);
        $tokens = array_filter($tokens, function($token) {
            if (!is_array($token)) {
                return true;
            }

            return T_WHITESPACE !== $token[0];
        });

        return $tokens;
    }

    protected function fileIterator($directoryPath)
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directoryPath)
        );
    }

    private $isolator;
}
