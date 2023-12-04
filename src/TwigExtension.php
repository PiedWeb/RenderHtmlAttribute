<?php

namespace PiedWeb\RenderAttributes;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Transform an array in html tag attributes
 * Twig Extension
 * PSR-2 Coding Style, PSR-4 Autoloading.
 *
 * @author     Robin <contact@robin-d.fr> https://piedweb.com
 *
 * @see       https://github.com/PiedWeb/render-html-attributes
 * @since      File available since 2018.11.12
 */
class TwigExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('mergeAttr', [Attribute::class, 'mergeAndRender'], ['is_safe' => ['html']]),
            new TwigFunction('attr', [Attribute::class, 'renderAll'], ['is_safe' => ['html']]),
        ];
    }
}
