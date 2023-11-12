<?php

namespace PiedWeb\RenderAttributes;

/**
 * Transform an array in html tag attributes
 *
 * @author     Robin <contact@robin-d.fr> https://piedweb.com
 *
 * @see       https://github.com/PiedWeb/RenderHtmlAttribute
 */
final class RenderAttributes
{
    public static function mergeAttributes(): array
    {
        $arrays = \func_get_args();
        $result = [];

        foreach ($arrays as $array) {
            $result = self::mergeRecursive($result, $array);
        }

        return $result;
    }

    protected static function mergeRecursive(array $arr1, array $arr2): array
    {
        foreach ($arr2 as $key => $v) {
            if (\is_array($v)) {
                $arr1[$key] = isset($arr1[$key]) ? self::mergeRecursive($arr1[$key], $v) : $v;
            } else {
                $arr1[$key] = isset($arr1[$key]) ? $arr1[$key].($arr1[$key] != $v ? ' '.$v : '') : $v;
            }
        }

        return $arr1;
    }

    public static function renderAttributes(array $attributes): string
    {
        $result = '';

        foreach ($attributes as $attribute => $value) {
            if (empty($value)) {
                $result .= ' '.$attribute;
                continue;
            }

            if (is_int($attribute)) {
                $result .= ' '.$value;
                continue;
            }

            $e = str_contains($value, ' ') ? '"' : '';
            $result .= ' '.$attribute.'='.$e.str_replace('"', '&quot;', $value).$e;
        }

        return $result;
    }

    public static function mergeAndRenderAttributes(): string
    {
        $arrays = \func_get_args();
        $result = [];

        foreach ($arrays as $array) {
            $result = self::mergeRecursive($result, $array);
        }

        return self::renderAttributes($result);
    }
}
