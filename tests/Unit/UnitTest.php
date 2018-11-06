<?php

namespace App\Tests\Unit;

use App\Component\Search\Ebay\Model\Request\Model\TranslationEntry;
use App\Component\Search\Ebay\Model\Request\Model\Translations;
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

    public function test_cache_translations_object()
    {
        $translations = new Translations();

        static::assertTrue($translations->isEmpty());

        $translationsArray = [
            'item' => [
                'en' => [
                    'original' => 'original en text',
                    'translated' => 'translated en text',
                ],
                'fr' => [
                    'original' => 'original fr text',
                    'translated' => 'translated fr text',
                ],
                'es' => [
                    'original' => 'original es text',
                    'translated' => 'translated es text',
                ]
            ],
            'description' => [
                'en' => [
                    'original' => 'original desc en text',
                    'translated' => 'translated desc en text',
                ],
                'fr' => [
                    'original' => 'original desc fr text',
                    'translated' => 'translated desc fr text',
                ],
                'es' => [
                    'original' => 'original desc es text',
                    'translated' => 'translated desc es text',
                ]
            ],
        ];

        $translations = new Translations($translationsArray);

        static::assertFalse($translations->isEmpty());
        static::assertTrue($translations->hasEntryByLocale('item', 'en'));

        /** @var TranslationEntry $translationEntry */
        $translationEntry = $translations->getEntryByLocale('item', 'en');

        static::assertInstanceOf(TranslationEntry::class, $translationEntry);

        static::assertEquals($translationEntry->getLocale(), 'en');
        static::assertEquals($translationEntry->getOriginal(), 'original en text');
        static::assertEquals($translationEntry->getTranslation(), 'translated en text');

        /** @var TranslationEntry $translationEntry */
        $translationEntry = $translations->getEntryByLocale('description', 'es');

        static::assertInstanceOf(TranslationEntry::class, $translationEntry);

        static::assertEquals($translationEntry->getLocale(), 'es');
        static::assertEquals($translationEntry->getOriginal(), 'original desc es text');
        static::assertEquals($translationEntry->getTranslation(), 'translated desc es text');

        $translations->putTranslation(
            'shortDescription',
            'en',
            'original short en description',
            'translated short en description'
        );

        /** @var TranslationEntry $translationEntry */
        $translationEntry = $translations->getEntryByLocale('shortDescription', 'en');

        static::assertEquals($translationEntry->getLocale(), 'en');
        static::assertEquals($translationEntry->getOriginal(), 'original short en description');
        static::assertEquals($translationEntry->getTranslation(), 'translated short en description');

        static::assertNotEmpty($translations->toArray());
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