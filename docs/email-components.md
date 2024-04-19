
## Introduction

A collection of high-quality, un-styled components for creating beautiful emails using Laravel.
It reduces the pain of coding responsive emails with dark mode support. It also takes care of inconsistencies between Gmail, Outlook, and other email clients for you.

## Why

We believe that email is an extremely important medium for people to communicate. However, we need to stop developing emails like 2010, and rethink how email can be done in 2022 and beyond. Email development needs a revamp. A renovation. Modernized for the way we build web apps today.

## Install
```bash
composer require damilaredev/laravel-email
```

## Getting started

Add the component to your email template. Include styles where needed.

```html
<x-barua-html>
    <x-barua-head>
        <link rel="dns-prefetch" href="//fonts.gstatic.com">

        <x-barua-font
                :font-family="'Br Firma'"
                :web-font="[
                     'url' => 'https://fonts.gstatic.com/s/opensans/v18/mem8YaGs126MiZpBA-UFVZ0e.ttf',
                     'format' => 'truetype'
                ]"
                :font-style="'normal'"
                :font-weight="400"
        />
    </x-barua-head>

    <x-barua-body
            style="margin-left:auto;margin-right:auto;margin-top:auto;margin-bottom:auto;background-color:rgba(255, 255, 255, 1);font-family:Open Sans, ui-sans-serif, system-ui, -apple-system,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,Ubuntu,sans-serif"
    >
        <x-barua-container style="margin-left:auto;margin-right:auto; max-width:50em;margin:10px auto;">
            <x-barua-section>
                <x-barua-heading style="font-size:1.75rem;line-height:43.99px;font-weight:700;text-align:left; color: rgba(80, 85, 94, 1);">
                    Laravel Email
                </x-barua-heading>
            </x-barua-section>
        </x-barua-container>
    </x-barua-body>
</x-barua-html>
```

## Components

A set of standard components to help you build amazing emails without having to deal with the mess of creating table-based layouts and maintaining archaic markup.

### HTML
```html
<x-barua-html lang="en" dir="ltr">
    <x-barua-link href="https://example.com">
        Click Me
    </x-barua-link>
</x-barua-html>
```

### Head
```html
<x-barua-head>
    <title>email title</title>
</x-barua-head>
```

### Heading
```html
<x-barua-heading as="h1">Lorem Ipsum</x-barua-heading> 
```

### Link
```html
<x-barua-link href="https://example.com">
    Example
</x-barua-link> 
```

### Image
```html
<x-barua-img 
    src="dog.jpg"
    alt="dog"
    width="200"
    height="200"
/>

# Props
| Name   | Type   | Default | Description                        |
| ------ | ------ | ------- | ---------------------------------- |
| alt    | string |         | Alternate description for an image |
| src    | string |         | The path to the image              |
| width  | string |         | The width of an image in pixels    |
| height | string |         | The height of an image in pixels   |

```

### Divider
```html
<x-barua-hr /> 
```

### Paragraph
```html
<x-barua-text>Lorem Ipsum</x-barua-text>
```

### Container
```html
<x-barua.container>
    <x-barua-link href="https://example.com" style="font-weight: 500; color: #0000;">
        Click here
    </x-barua-link>
</x-barua.container>
```

### Body
```html
 <x-barua-html lang="en">
    <x-barua-body style="background-color: rgba(37, 60, 172, 1);">
        <x-barua-section>
            <x-barua-column style="width: 50%">
                {{-- First column --}}
            </x-barua-column>
            <x-barua-column style="width: 50%">
                {{-- Second column --}}
            </x-barua-column>
        </x-barua-section>
    </x-barua-body>
</x-barua-html>
```

### Row
```html
<x-barua-row>
    <x-barua-column>A</x-barua-column>
    <x-barua-column>B</x-barua-column>
    <x-barua-column>C</x-barua-column>
</x-barua-row>
```

### Section
```html
{{-- A Simple Section --}}
<x-barua-section>
    <x-barua-text>Lorem Ipsum</x-barua-text>
</x-barua-section>

{{-- Formatted with `rows` and `columns` --}}
<x-barua-section>
    <x-barua-row>
        <x-barua-column>Column 1, Row 1</x-barua-column>
        <x-barua-column>Column 2, Row 1</x-barua-column>
    </x-barua-row>

    <x-barua-row>
        <x-barua-column>Column 1, Row 2</x-barua-column>
        <x-barua-column>Column 2, Row 2</x-barua-column>
    </x-barua-row>
</x-barua-section>
```

### Font
```html
<x-barua-html lang="en">
   <x-barua-head>
       <x-barua-font
           font-family="Br Firma"
           fallback-font-family="Verdana"
           :web-font="[
                     'url' => 'https://fonts.gstatic.com/s/opensans/v18/mem8YaGs126MiZpBA-UFVZ0e.ttf',
                     'format' => 'truetype'
                ]"
           font-style="normal"
           font-weight="400"
       />
   </x-barua-head> 
</x-barua-html>
```

### Td
```html
{{-- A Simple Td --}}
<x-barua-td>
    <x-barua-text>Lorem Ipsum</x-barua-text>
</x-barua-td>
```
