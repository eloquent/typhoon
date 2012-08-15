<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use ArrayIterator;
use PHPUnit_Framework_TestCase;
use stdClass;

class TypeInspectorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_inspector = new TypeInspector;
        $this->_streams = array();
        $this->_files = array();
    }

    protected function tearDown()
    {
        parent::tearDown();

        foreach ($this->_streams as $stream) {
            fclose($stream);
        }
        foreach ($this->_files as $file) {
            unlink($file);
        }
    }

    protected function streamFixture($mode) {
        $this->_files[] = $file = $path = sys_get_temp_dir().'/'.uniqid('typhoon-');
        touch($file);
        $this->_streams[] = $stream = fopen($file, $mode);

        return $stream;
    }

    public function inspectorData()
    {
        $data = array();

        $data['Array'] = array('array', array());
        $data['Boolean true'] = array('boolean', true);
        $data['Boolean false'] = array('boolean', false);
        $data['Float'] = array('float', 1.11);
        $data['Integer'] = array('integer', 111);
        $data['Null'] = array('null', null);
        $data['Object'] = array('stdClass', new stdClass);
        $data['String'] = array('string', 'foo');

        $data['Stream context resource'] = array(
            'resource {ofType: stream-context}',
            stream_context_create(),
        );

        $data['Array with subtypes'] = array(
            'array<integer|string, float|null>',
            array(
                1.11,
                'foo' => null,
            ),
        );

        $data['Array with subtypes and max iterations'] = array(
            'array<integer, float>',
            array(
                1.11,
                'foo' => null,
            ),
            1,
        );

        $data['Array with subtypes and default max iterations'] = array(
            'array<integer, float>',
            array(
                1.11,
                1.11,
                1.11,
                1.11,
                1.11,
                1.11,
                1.11,
                1.11,
                1.11,
                1.11,
                'foo' => null,
            ),
        );

        $data['Traversable with subtypes'] = array(
            'ArrayIterator<integer|string, float|null>',
            new ArrayIterator(array(
                1.11,
                'foo' => null,
            )),
        );

        $data['Traversable with subtypes and max iterations'] = array(
            'ArrayIterator<integer, float>',
            new ArrayIterator(array(
                1.11,
                'foo' => null,
            )),
            1,
        );

        return $data;
    }

    /**
     * @dataProvider inspectorData
     */
    public function testInspector($expected, $value, $maxIterations = 10)
    {
        $this->assertSame($expected, $this->_inspector->type($value, $maxIterations));
    }

    public function testStreamInspection()
    {
        $readable = $this->streamFixture('rb');
        $writable = $this->streamFixture('wb');
        $readwrite = $this->streamFixture('wb+');

        $this->assertSame(
            'stream {readable: true, writable: false}',
            $this->_inspector->type($readable)
        );
        $this->assertSame(
            'stream {readable: false, writable: true}',
            $this->_inspector->type($writable)
        );
        $this->assertSame(
            'stream {readable: true, writable: true}',
            $this->_inspector->type($readwrite)
        );
    }
}
