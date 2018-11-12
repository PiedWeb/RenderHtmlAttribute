# Plates/Twig Extension : Render html tag attributes

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
