# Components

14 Blade components under the `laranail-barua` prefix, for building email markup that holds together across mail clients.

Each renders table-based markup and is un-styled: any attribute you pass, `style` included, is merged onto the element. Classes live in `Simtabi\Laranail\Barua\View\Components`, views in `resources/views/components`.

| Tag | Renders | Props |
|---|---|---|
| `<x-laranail-barua::html>` | the document, with the XHTML doctype | `lang` (`en`), `dir` (`ltr`) |
| `<x-laranail-barua::head>` | `<head>` with the charset and viewport metas | |
| `<x-laranail-barua::body>` | `<body>` | |
| `<x-laranail-barua::container>` | a centred table, max width 37.5em | |
| `<x-laranail-barua::section>` | a full-width table with one cell | `styling` (raw attributes for the cell) |
| `<x-laranail-barua::row>` | a table row | `styling` (raw attributes for the row) |
| `<x-laranail-barua::column>` | a `<td>` column | |
| `<x-laranail-barua::td>` | a `<td>` with body text styles | |
| `<x-laranail-barua::heading>` | `<h1>` to `<h6>` | `as` (`h1`), margins `m`, `mx`, `my`, `mt`, `mr`, `mb`, `ml` in px |
| `<x-laranail-barua::text>` | a paragraph | |
| `<x-laranail-barua::link>` | a link | `target` (`_blank`) |
| `<x-laranail-barua::img>` | an image | `src` (required), `alt`, `width`, `height` (`100%`) |
| `<x-laranail-barua::hr>` | a divider | |
| `<x-laranail-barua::font>` | a `@font-face` and a `*` rule | `font-family` (required), `fallback-font-family`, `:web-font` (`['url' => ..., 'format' => ...]`), `font-style`, `font-weight` |

## Examples

```blade
<x-laranail-barua::heading as="h2" my="8" mt="0">Order shipped</x-laranail-barua::heading>

<x-laranail-barua::section>
    <x-laranail-barua::row>
        <x-laranail-barua::column style="width: 50%">Item</x-laranail-barua::column>
        <x-laranail-barua::column style="width: 50%; text-align: right">$12.00</x-laranail-barua::column>
    </x-laranail-barua::row>
</x-laranail-barua::section>

<x-laranail-barua::img src="https://example.com/logo.png" alt="Acme" width="120" height="40" />

<x-laranail-barua::head>
    <x-laranail-barua::font
        font-family="Open Sans"
        fallback-font-family="Verdana"
        :web-font="['url' => 'https://fonts.gstatic.com/s/opensans/v18/mem8YaGs126MiZpBA-UFVZ0e.ttf', 'format' => 'truetype']"
    />
</x-laranail-barua::head>
```

A later margin wins over a general one: `my="8" mt="0"` gives `margin-top: 0` and `margin-bottom: 8px`.

## Mail client support

The original author reported the components working in Gmail, Apple Mail, Outlook, Yahoo! Mail, HEY and Superhuman. That was not re-tested when the package moved to Laravel 13; the automated suite checks that each component renders, not how each client displays it.

## Customising

Publish the views (`--tag=laranail::barua-views`) and edit your copies in `resources/views/vendor/laranail/barua/components`. The component classes stay in the package.

---

[← Docs index](../../README.md#documentation)
