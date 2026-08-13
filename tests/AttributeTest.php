<?php

namespace PiedWeb\RenderAttributes\Test;

use PHPUnit\Framework\TestCase;
use PiedWeb\RenderAttributes\Attribute;

class AttributeTest extends TestCase
{
    public function testMergeKeepsTheLastValue(): void
    {
        $merged = Attribute::merge(['sizes' => '100vw'], ['sizes' => '63vw'], ['sizes' => '50vw']);

        $this->assertSame(['sizes' => '50vw'], $merged);
    }

    public function testMergeConcatenatesEveryTokenListAttribute(): void
    {
        $merged = Attribute::merge(
            ['class' => 'main', 'rel' => 'noopener', 'aria-describedby' => 'a', 'aria-labelledby' => 'x'],
            ['class' => 'content', 'rel' => 'noreferrer', 'aria-describedby' => 'b', 'aria-labelledby' => 'y'],
        );

        $this->assertSame([
            'class' => 'main content',
            'rel' => 'noopener noreferrer',
            'aria-describedby' => 'a b',
            'aria-labelledby' => 'x y',
        ], $merged);
    }

    public function testMergeCollapsesWhitespaceBetweenTokens(): void
    {
        $merged = Attribute::merge(['class' => '  main   wide '], ['class' => "content\n main"]);

        $this->assertSame(['class' => 'main wide content'], $merged);
    }

    public function testMergeStringifiesStringable(): void
    {
        $srcset = new class implements \Stringable {
            public function __toString(): string
            {
                return 'a.webp 1x, a@2x.webp 2x';
            }
        };

        $this->assertSame(['srcset' => 'a.webp 1x, a@2x.webp 2x'], Attribute::merge(['srcset' => $srcset]));
    }

    public function testMergeEmptiesValuesItCannotStringify(): void
    {
        $this->assertSame(['srcset' => ''], Attribute::merge(['srcset' => new \stdClass()]));
    }

    public function testMergeRecursesIntoNestedArrays(): void
    {
        $merged = Attribute::merge(
            ['data' => ['class' => 'main', 'id' => 'first']],
            ['data' => ['class' => 'content', 'id' => 'second']],
        );

        $this->assertSame(['data' => ['class' => 'main content', 'id' => 'second']], $merged);
    }
}
