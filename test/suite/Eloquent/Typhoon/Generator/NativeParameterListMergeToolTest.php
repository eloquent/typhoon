<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Generator;

use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Parameter\ParameterList;
use PHPUnit_Framework_TestCase;

class NativeParameterListMergeToolTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_mergeTool = new NativeParameterListMergeTool;
    }

    public function mergeData()
    {
        $data = array();

        $documentedParameterList = new ParameterList;
        $nativeParameterList = new ParameterList;
        $expected = $documentedParameterList;
        $data['Empty parameter list'] = array(
            $expected,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            )
        );
        $expected = $documentedParameterList;
        $data['Unchanged parameter list'] = array(
            $expected,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            ),
            true
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
            )
        );
        $expected = $documentedParameterList;
        $data['Unchanged parameter list (variable length)'] = array(
            $expected,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    true,
                    false
                ),
            )
        );
        $expected = $nativeParameterList;
        $data['Copy isOptional from native'] = array(
            $expected,
            $documentedParameterList,
            $nativeParameterList,
        );

        return $data;
    }

    /**
     * @dataProvider mergeData
     */
    public function testMerge(
        $expected,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        $this->assertEquals($expected, $this->_mergeTool->merge(
            'foo',
            $documentedParameterList,
            $nativeParameterList
        ));
    }

    public function mergeFailureData()
    {
        $data = array();

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    true,
                    false
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterUndefinedException';
        $expectedMessage = "Documented parameter 'baz' not defined in 'foo'.";
        $data['Documented parameter not defined'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    true,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    false
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\UndocumentedParameterException';
        $expectedMessage = "Parameter 'baz' is undocumented in 'foo'.";
        $data['Native parameter not documented'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    false
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterNameMismatchException';
        $expectedMessage = "Documented parameter name 'bar' does not match defined parameter name 'baz' in 'foo'.";
        $data['Parameter name mismatch'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    true
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterByReferenceMismatchException';
        $expectedMessage = "Parameter 'bar' is documented as by-value but defined as by-reference in 'foo'.";
        $data['Parameter by-reference mismatch'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
            ),
            true
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DefinedParameterVariableLengthException';
        $expectedMessage = "Variable-length parameter 'baz' should only be documented, not defined in 'foo'.";
        $data['Non documentation-only variable length parameter'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        $documentedParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
                new Parameter(
                    'baz',
                    new MixedType,
                    'Baz description.',
                    false,
                    true
                ),
                new Parameter(
                    'qux',
                    new MixedType,
                    'Qux description.',
                    true,
                    true
                ),
            ),
            true
        );
        $nativeParameterList = new ParameterList(
            array(
                new Parameter(
                    'bar',
                    new MixedType,
                    'Bar description.',
                    false,
                    false
                ),
            )
        );
        $expected = __NAMESPACE__.'\Exception\DocumentedParameterUndefinedException';
        $expectedMessage = "Documented parameter 'qux' not defined in 'foo'.";
        $data['Variable length parameters with undefined penultimate parameter'] = array(
            $expected,
            $expectedMessage,
            $documentedParameterList,
            $nativeParameterList,
        );

        return $data;
    }

    /**
     * @dataProvider mergeFailureData
     */
    public function testMergeFailure(
        $expected,
        $expectedMessage,
        ParameterList $documentedParameterList,
        ParameterList $nativeParameterList
    ) {
        $this->setExpectedException($expected, $expectedMessage);
        $this->_mergeTool->merge(
            'foo',
            $documentedParameterList,
            $nativeParameterList
        );
    }
}
