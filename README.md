# Twig Extension : Render html tag attributes

[![Latest Version](https://img.shields.io/github/tag/PiedWeb/RenderHtmlAttribute.svg?style=flat&label=release)](https://github.com/PiedWeb/RenderHtmlAttribute/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)
[![Code Coverage](https://codecov.io/gh/PiedWeb/PiedWeb/branch/main/graph/badge.svg)](https://codecov.io/gh/PiedWeb/PiedWeb/branch/main)
[![Type Coverage](https://shepherd.dev/github/PiedWeb/PiedWeb/coverage.svg)](https://shepherd.dev/github/PiedWeb/PiedWeb)
[![Total Downloads](https://img.shields.io/packagist/dt/piedweb/render-html-attributes.svg?style=flat)](https://packagist.org/packages/piedweb/render-html-attributes)

This package is an extension for both [Twig](https://github.com/twigphp/Twig) and ~Plate engine [Plates](https://github.com/thephpleague/plates)~.

Plates is not anymore supported since v1.0.3.

Two features for the same goal **Manipulate html tag attributes via object/PHP array** :

- `attr({class: "col", id: "piedweb", data-content:"Hello :)', ...})` transform an array in html tag attributes
- `mergeAttr($attributes1, $attributes2, [$attributes3, ...])` merge multiple arrays, the last value winning (Eg. : `['sizes' => '100vw']` + `['sizes' => '63vw']` = `['sizes' => '63vw']`), except for [token list attributes](#token-list-attributes) whose tokens are concatenated (Eg. : `['class' => 'main']` + `['class' => 'content']` = `['class' => 'main content']`)

### Token list attributes

These attributes hold a space separated list of tokens, so merging them concatenates
the tokens instead of replacing the value. Duplicated tokens are dropped
(`class="btn"` + `class="btn btn-lg"` = `class="btn btn-lg"`).

- `class`
- `rel`
- `aria-describedby`
- `aria-labelledby`

Every other attribute (`src`, `srcset`, `sizes`, `alt`, `id`, `style`, `width`,
`height`, `loading`, …) is replaced by the last value merged in, which is what lets a
caller override an attribute already set by a template.

Values are stringified when they are scalar or `Stringable` — a `Twig\Markup`, as
returned by a Twig macro, is therefore rendered as its string content.

## Table of contents

- [Twig Extension : Render html tag attributes](#twig-extension--render-html-tag-attributes)
  - [Token list attributes](#token-list-attributes)
  - [Table of contents](#table-of-contents)
  - [Usage](#usage)
  - [Installation](#installation)
  - [Upgrade](#upgrade)
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

## Upgrade

### Scalar attributes are now replaced instead of concatenated

**This changes an observable behaviour.** `mergeAttr` used to concatenate *every*
scalar value, so an attribute already set by a template could never be overridden by
a caller — it got both values glued together (`sizes="100vw 63vw"`, and the same
corruption on `src`, `alt`, `id`, `style`, `width`, `height`, `loading`, `srcset`).

Merging now applies last-wins to every attribute outside the
[token list](#token-list-attributes). A project relying on the concatenation of an
attribute that is *not* in that list will see its rendering change: pass the final
value instead, or merge the parts yourself.

`class` (and the other token list attributes) keep concatenating, so the common
"template sets a base class, caller adds one" pattern is unchanged. Duplicated tokens
are now dropped, though: `class="btn"` + `class="btn btn-lg"` used to render
`class="btn btn btn-lg"` and now renders `class="btn btn-lg"`.

### Unrenderable values now throw instead of rendering garbage

`merge` recurses into nested arrays, but an array has no html attribute form, so
`attr` and `mergeAttr` used to emit a PHP `Array to string conversion` warning and
render `data="Array"`. They now throw an `InvalidArgumentException` naming the
attribute and the type received. The same applies to any object that is not
`Stringable`, which previously died with a less helpful conversion error.

Nesting remains supported by `merge` itself, which returns the merged array
untouched — only rendering rejects it.

### `Stringable` values are no longer emptied

A non-scalar value was silently rendered as an empty attribute. `Stringable` objects
— including the `Twig\Markup` returned by any Twig macro — are now stringified, so an
attribute fed by a macro finally carries its value.

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
