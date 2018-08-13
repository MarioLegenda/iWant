<?php

namespace App\Tests\Unit;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function test_recursive_closure_util()
    {
        $multiArray = [
            'depth' => 0,
            'id' => uniqid(),
            [
                'depth' => 1,
                'id' => uniqid(),
                [
                    'depth' => 2,
                    'id' => uniqid(),
                ],
                [
                    'depth' => 2,
                    'id' => uniqid(),
                ],
            ],
            [
                'depth' => 0,
                'id' => uniqid(),
                [
                    'depth' => 1,
                    'id' => uniqid(),
                    [
                        'depth' => 2,
                        'id' => uniqid(),
                        [
                            'depth' => 2,
                            'id' => uniqid(),
                        ]
                    ]
                ]
            ],
        ];

        $onlyDepth2 = [];
        Util::nonStopRecursiveExecution(function(\Closure $closure, array $data) use (&$onlyDepth2) {
            foreach ($data as $key => $item) {
                if ($key === 'depth') {
                    if ($item === 2) {
                        $onlyDepth2[] = $item;
                    }
                }

                if (is_array($item)) {
                    $closure($closure, $item);
                }
            }
        }, $multiArray);

        static::assertEquals(4, count($onlyDepth2));
    }

    public function test_typed_static_array()
    {
        $data = [
            'value1',
            'value2',
            'value3',
            'value4',
            'value5',
        ];

        $staticArray = TypedArray::create('integer', 'string');

        foreach ($data as $item) {
            $staticArray[] = $item;
        }

        $dataKeys = array_keys($data);

        foreach ($dataKeys as $key) {
            static::assertTrue(isset($staticArray[$key]));
        }
    }
}