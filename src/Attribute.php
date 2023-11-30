<?php

namespace PiedWeb\RenderAttributes;

/**
 * Transform an array in html tag attributes
 *
 * @author     Robin <contact@robin-d.fr> https://piedweb.com
 *
 * @see       https://github.com/PiedWeb/RenderHtmlAttribute
 */
final class Attribute
{
    public static function merge(): array
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

    public static function render(string $name, string $value = '')
    {
        if (in_array($name, ['class', 'style'], true) &&  (!is_string($value) || $value==='')) {
            return '';
        }

        if ($value === '') {
            return ' '.$name;
        }

        $e = str_contains($value, ' ') ? '"' : '';
        return ' '.$name.'='.$e.str_replace('"', '&quot;', $value).$e;
    }

    /**
     * Previously mapAttributes
     */
    public static function renderAll(array $attributes): string
    {
        $result = '';

        foreach ($attributes as $name => $value) {

            $result .= is_int($name) ? static::render($value) : static::render($name, $value);
        }

        return $result;
    }

    public static function mergeAndRender(): string
    {
        $arrays = \func_get_args();
        $result = [];

        foreach ($arrays as $array) {
            $result = self::mergeRecursive($result, $array);
        }

        return self::renderAll($result);
    }
}
