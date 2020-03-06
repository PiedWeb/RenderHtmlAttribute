<?php
namespace PiedWeb\RenderAttributes;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * Transform an array in html tag attributes
 * PSR-2 Coding Style, PSR-4 Autoloading
 *
 * @author     Robin <contact@robin-d.fr> https://piedweb.com
 * @link       https://github.com/RobinDev/platesAttributes
 * @since      File available since Release 2014.12.15
 */
trait AttributesTrait
{

    /**
     * Merge multiple attributes arrays without erase values
     *
     * @return array
     */
    public static function mergeAttributes()
    {
        $arrays = func_get_args();
        $result = [];

        foreach ($arrays as $array) {
            $result = self::mergeRecursive($result, $array);
        }

        return $result;
    }

    /**
     * @param  array $arr1
     * @param  array $arr2
     * @return array
     */
    protected static function mergeRecursive(array $arr1, array $arr2)
    {
        foreach ($arr2 as $key => $v) {
            if (is_array($v)) {
                $arr1[$key] = isset($arr1[$key]) ? self::mergeRecursive($arr1[$key], $v) : $v;
            } else {
                $arr1[$key] = isset($arr1[$key]) ? $arr1[$key].($arr1[$key] != $v ? ' '.$v : '') : $v;
            }
        }

        return $arr1;
    }

    /**
     * Render the attributes
     *
     * @param  array  $attributes
     * @return string
     */
    public static function mapAttributes(array $attributes)
    {
        $result = '';

        foreach ($attributes as $attribute => $value) {
            if (empty($value)) {
                $result .= ' '.$attribute;
            } else {
                $e = strpos($value, ' ') !== false ? '"' : '';
                $result .= ' '.(is_int($attribute) ? $value : $attribute.'='.$e.str_replace('"', '&quot;', $value).$e);
            }
        }

        return $result;
    }



    /**
     * Merge and Map
     *
     * @return string
     */
    public static function mergeAndMapAttributes()
    {
        $arrays = func_get_args();
        $result = [];

        foreach ($arrays as $array) {
            $result = self::mergeRecursive($result, $array);
        }

        return self::mapAttributes($result);
    }
}
