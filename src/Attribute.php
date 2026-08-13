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
     * last merged value.
     */
    private const array TOKEN_LIST_ATTRIBUTES = ['class', 'rel', 'aria-describedby', 'aria-labelledby'];

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
            $result = self::mergeRecursive($result, $array);
        }

        /** @var array<string, mixed> */
        return $result;
    }

    /**
     * @param array<string, mixed> $arr1
     * @param array<string, mixed> $arr2
     *
     * @return array<string, mixed>
     */
    private static function mergeRecursive(array $arr1, array $arr2): array
    {
        foreach ($arr2 as $key => $v) {
            if (null === $v) {
                $arr1[$key] = null;
            } elseif (\is_array($v)) {
                /** @var array<string, mixed> $existing */
                $existing = isset($arr1[$key]) && \is_array($arr1[$key]) ? $arr1[$key] : [];
                /** @var array<string, mixed> $vArray */
                $vArray = $v;
                $arr1[$key] = [] !== $existing ? self::mergeRecursive($existing, $vArray) : $v;
            } else {
                $vStr = \is_scalar($v) || $v instanceof \Stringable ? (string) $v : '';
                $existingStr = isset($arr1[$key]) && \is_scalar($arr1[$key]) ? (string) $arr1[$key] : '';
                $arr1[$key] = \in_array($key, self::TOKEN_LIST_ATTRIBUTES, true)
                    ? self::mergeTokens($existingStr, $vStr)
                    : $vStr;
            }
        }

        return $arr1;
    }

    private static function mergeTokens(string $existing, string $new): string
    {
        $tokens = preg_split('/\s+/', $existing.' '.$new, -1, \PREG_SPLIT_NO_EMPTY) ?: [];

        return implode(' ', array_unique($tokens));
    }

    public static function render(string $name, string $value = ''): string
    {
        if (\in_array($name, ['class', 'style'], true) && '' === $value) {
            return '';
        }

        if ('' === $value) {
            return ' '.$name;
        }

        $e = '"'; // str_contains($value, ' ') ? '"' : '';

        return ' '.$name.'='.$e.str_replace('"', '&quot;', $value).$e;
    }

    /**
     * Previously mapAttributes.
     *
     * @param array<int|string, string|null> $attributes
     */
    public static function renderAll(array $attributes): string
    {
        $result = '';

        foreach ($attributes as $name => $value) {
            if (null === $value) {
                continue;
            }

            $result .= \is_int($name) ? self::render($value) : self::render($name, (string) $value);
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
            $result = self::mergeRecursive($result, $array);
        }

        /** @var array<int|string, string> $result */
        return self::renderAll($result);
    }
}
