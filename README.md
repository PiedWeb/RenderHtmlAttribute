# Twig Extension : Render html tag attributes

[![Latest Version](https://img.shields.io/github/tag/PiedWeb/RenderHtmlAttribute.svg?style=flat&label=release)](https://github.com/PiedWeb/RenderHtmlAttribute/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/PiedWeb/PiedWeb/Tests?label=tests)](https://github.com/PiedWeb/RenderHtmlAttribute/actions)
[![Quality Score](https://img.shields.io/scrutinizer/g/PiedWeb/PiedWeb.svg?style=flat)](https://scrutinizer-ci.com/g/PiedWeb/RenderHtmlAttribute)
[![Code Coverage](https://codecov.io/gh/PiedWeb/PiedWeb/branch/main/graph/badge.svg)](https://codecov.io/gh/PiedWeb/PiedWeb/branch/main)
[![Type Coverage](https://shepherd.dev/github/PiedWeb/PiedWeb/coverage.svg)](https://shepherd.dev/github/PiedWeb/PiedWeb)
[![Total Downloads](https://img.shields.io/packagist/dt/piedweb/RenderHtmlAttribute.svg?style=flat)](https://packagist.org/packages/piedweb/render-html-attributes)

This package is an extension for both [Twig](https://github.com/twigphp/Twig) and ~Plate engine [Plates](https://github.com/thephpleague/plates)~.

Plates is not anymore supported since v1.0.3.

Two features for the same goal **Manipulate html tag attributes via object/PHP array** :

- `attr({class: "col", id: "piedweb", data-content:"Hello :)', ...})` transform an array in html tag attributes
- `mergeAttr($attributes1, $attributes2, [$attributes3, ...])` merge multiple array without loosing values (Eg. : `['class' => 'main']` + `['class' => 'content']` = `['class' => 'main content']`)

## Table of contents

- [Twig Extension : Render html tag attributes](#twig-extension--render-html-tag-attributes)
  - [Table of contents](#table-of-contents)
  - [Usage](#usage)
  - [Installation](#installation)
  - [Requirements](#requirements)
  - [Contributors](#contributors)
  - [License](#license)

## Usage

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

## Installation

```bash
composer require piedweb/render-html-attributes
```

## Requirements

Stand alone extension.

See `composer.json` file.

## Contributors

- Original author [Robin (PiedWeb from the Alps Mountain)](https://piedweb.com)
- ...

## License

MIT (see the LICENSE file for details)

<p align="center"><a href="https://dev.piedweb.com">
<img src="https://raw.githubusercontent.com/PiedWeb/piedweb-devoluix-theme/master/src/img/logo_title.png" width="200" height="200" alt="Open Source Package" />
</a></p>
