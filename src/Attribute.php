<?php

namespace PiedWeb\RenderAttributes;

/**
 * Transform an array in html tag attributes.
 *
 * @author     Robin <contact@robin-d.fr> https://piedweb.com
 *
 * @see       https://github.com/PiedWeb/RenderHtmlAttribute
 */
final class Attribute
{
    /**
     * Attributes holding a space separated token list : merging them concatenates
     * the tokens (duplicates removed). Every other attribute is replaced by the
     * last merged value. Kept as a map so the lookup is a hash hit per attribute
     * rather than a scan.
     */
    private const array TOKEN_LIST_ATTRIBUTES = [
        'class' => true,
        'rel' => true,
        'aria-describedby' => true,
        'aria-labelledby' => true,
    ];

    /**
     * @param array<string, mixed> ...$arrays
     *
     * @return array<string, mixed>
     */
    public static function merge(array ...$arrays): array
    {
        /** @var array<string, mixed> $result */
        $result = [];

        foreach ($arrays as $array) {
            self::mergeRecursive($result, $array);
        }

        /** @var array<string, mixed> */
        return $result;
    }

    /**
     * $arr1 is taken by reference so merging a list of arrays does not copy the
     * accumulator once per array.
     *
     * @param array<string, mixed> $arr1
     * @param array<string, mixed> $arr2
     */
    private static function mergeRecursive(array &$arr1, array $arr2): void
    {
        foreach ($arr2 as $key => $v) {
            if (null === $v) {
                $arr1[$key] = null;
            } elseif (\is_array($v)) {
                /** @var array<string, mixed> $vArray */
                $vArray = $v;

                if (isset($arr1[$key]) && \is_array($arr1[$key]) && [] !== $arr1[$key]) {
                    /** @var array<string, mixed> $existing */
                    $existing = &$arr1[$key];
                    self::mergeRecursive($existing, $vArray);
                } else {
                    $arr1[$key] = $v;
                }
            } else {
                $vStr = \is_scalar($v) || $v instanceof \Stringable ? (string) $v : '';

                if (isset(self::TOKEN_LIST_ATTRIBUTES[$key])) {
                    $existing = $arr1[$key] ?? null;
                    $vStr = self::mergeTokens(\is_scalar($existing) ? (string) $existing : '', $vStr);
                }

                $arr1[$key] = $vStr;
            }
        }
    }

    private static function mergeTokens(string $existing, string $new): string
    {
        $tokens = preg_split('/\s+/', $existing.' '.$new, -1, \PREG_SPLIT_NO_EMPTY) ?: [];

        return implode(' ', array_unique($tokens));
    }

    public static function render(string $name, string $value = ''): string
    {
        if ('' === $value) {
            return 'class' === $name || 'style' === $name ? '' : ' '.$name;
        }

        return ' '.$name.'="'.str_replace('"', '&quot;', $value).'"';
    }

    /**
     * Previously mapAttributes.
     *
     * merge() recurses into nested arrays, but an array has no html attribute
     * form, so rendering one is rejected instead of emitting name="Array".
     *
     * @param array<int|string, mixed> $attributes
     *
     * @throws \InvalidArgumentException when a value cannot be rendered
     */
    public static function renderAll(array $attributes): string
    {
        $result = '';

        foreach ($attributes as $name => $value) {
            if (null === $value) {
                continue;
            }

            if (! \is_scalar($value) && ! $value instanceof \Stringable) {
                throw new \InvalidArgumentException(\sprintf('Attribute %s cannot be rendered : expected a scalar or a Stringable, got %s.', \is_int($name) ? '#'.$name : $name, get_debug_type($value)));
            }

            $result .= \is_int($name) ? self::render((string) $value) : self::render($name, (string) $value);
        }

        return $result;
    }

    /**
     * @param array<string, mixed> ...$arrays
     */
    public static function mergeAndRender(array ...$arrays): string
    {
        /** @var array<string, mixed> $result */
        $result = [];

        foreach ($arrays as $array) {
            self::mergeRecursive($result, $array);
        }

        /** @var array<int|string, string> $result */
        return self::renderAll($result);
    }
}
