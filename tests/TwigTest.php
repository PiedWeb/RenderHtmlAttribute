<?php

namespace PiedWeb\RenderAttributes\Test;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigTest extends TestCase
{
    /*
     * @var string
     */
    public function render($template): string
    {
        $loader = new ArrayLoader([
            'template' => $template,
        ]);
        $twig = new Environment($loader);
        $twig->addExtension(new \PiedWeb\RenderAttributes\TwigExtension());

        return $twig->render('template');
    }

    public function testRendering(): void
    {
        $twig = '{{ attr({class:"main content"})|raw }}';
        $expected = ' class="main content"';

        $this->assertSame($this->render($twig), $expected);
    }

    public function testMerging(): void
    {
        $twig = '{{ mergeAttr({class:"main"}, {class:"content"})|raw }}';
        $expected = ' class="main content"';

        $this->assertSame($this->render($twig), $expected);
    }

    public function testEmptyClassOrStyle(): void
    {
        $twig = '{{ mergeAttr({class:""}, ["style"])|raw }}';
        $expected = '';

        $this->assertSame($this->render($twig), $expected);
    }

    public function testNullValuesAreSkipped(): void
    {
        $twig = '{{ mergeAttr({src:"/image.svg", width:null, height:null, alt:"icon"})|raw }}';
        $expected = ' src="/image.svg" alt="icon"';

        $this->assertSame($this->render($twig), $expected);
    }
}
