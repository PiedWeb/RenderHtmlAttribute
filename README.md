<p align="center"><a href="https://dev.piedweb.com">
<img src="https://raw.githubusercontent.com/PiedWeb/piedweb-devoluix-theme/master/src/img/logo_title.png" width="200" height="200" alt="Open Source Package" />
</a></p>

# Plates/Twig Extension : Render html tag attributes

[![Latest Version](https://img.shields.io/github/tag/PiedWeb/RenderHtmlAttribute.svg?style=flat&label=release)](https://github.com/PiedWeb/RenderHtmlAttribute/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](https://github.com/PiedWeb/RenderHtmlAttribute/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/PiedWeb/RenderHtmlAttribute/master.svg?style=flat)](https://travis-ci.org/PiedWeb/RenderHtmlAttribute)
[![Quality Score](https://img.shields.io/scrutinizer/g/PiedWeb/RenderHtmlAttribute.svg?style=flat)](https://scrutinizer-ci.com/g/PiedWeb/RenderHtmlAttribute)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/PiedWeb/RenderHtmlAttribute.svg?style=flat)](https://scrutinizer-ci.com/g/PiedWeb/RenderHtmlAttribute/code-structure)
[![Total Downloads](https://img.shields.io/packagist/dt/piedweb/render-html-attribute.svg?style=flat)](https://packagist.org/packages/piedweb/render-html-attribute)

This package is an extension for both [Twig](https://github.com/twigphp/Twig) and Plate engine [Plates](https://github.com/thephpleague/plates).

Two features for the same goal **Manipulate html tag attributes via object/PHP array** :
* `attr({class: "col", id: "piedweb", data-content:"Hello :)', ...})` transform an array in html tag attributes
* `mergeAttr($attributes1, $attributes2, [$attributes3, ...])` merge multiple array without loosing values (Eg. : `['class' => 'main']` + `['class' => 'content']` = `['class' => 'main content']`)

## Table of contents
* [Usage](#usage)
* [Installation](#installation)
    * [Packagist](https://packagist.org/packages/piedweb/render-html-attributes)
* [Requirements](#requirements)
* [Contributors](#contributors)
* [Licence](#licence)

## Usage

## For Twig :

Load the extension in twig (eg for symfony) :
```
        piedweb.twig.extension.render_attributes:
        class: PiedWeb\RenderAttributes\TwigExtension
        public: false
        tags:
            - { name: twig.extension }
```

Then use it :
```
{{ attr({class:"main content"})|raw }}
{{ mergeAttr({class:"main"}, {class:"content"})|raw }}
```

## For Plates :

```php
/* Template Init */
$templateEngine = new \League\Plates\Engine('app/views');

/* Load this extension */
$templateEngine->loadExtension(new \PiedWeb\RenderAttributes\PlatesExtension());

$this->render('test', ['attributes' => ['class' => 'content']]);
```

In your `app/views/test.php` template file:
```php
<?php
$defaultAttributes = ['class' => 'main'];
$attributes        = isset($attributes) ? $this->mergeAttr($defaultAttributes, $attributes) : $defaultAttributes;
?>
<div<?=$this->attr($attributes)?>>Hello World !</div>
```

Will render:
```html
<div class="main content">Hello World !</div>
```

## Installation

```bash
composer require piedweb/render-html-attributes
```

## Requirements

Stand alone extension.

See `composer.json` file.

## Contributors

* Original author [Robin (PiedWeb from the Alps Mountain)](https://piedweb.com)
* ...

## License

MIT (see the LICENSE file for details)
