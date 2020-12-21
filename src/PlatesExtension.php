<?php

namespace PiedWeb\RenderAttributes;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * Transform an array in html tag attributes
 * Plates Extension
 * PSR-2 Coding Style, PSR-4 Autoloading.
 *
 * @author     Robin <contact@robin-d.fr> https://piedweb.com
 *
 * @see       https://github.com/PiedWeb/render-html-attributes
 * @since      File available since Release 2014.12.15
 */
class PlatesExtension implements ExtensionInterface
{
    use AttributesTrait;

    public function register(Engine $engine)
    {
        $engine->registerFunction('mergeAttr', [$this, 'mergeAttributes']);
        $engine->registerFunction('attr', [$this, 'mapAttributes']);
    }
}
