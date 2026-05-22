# SCSS Typography

Font variables go in `_variables.scss`. Font styles go in `_typography.scss`.

## Font Variables (`_variables.scss`)

```scss
// Font families
$font-heading: "Font Name", fallback, sans-serif;
$font-body: "Font Name", fallback, sans-serif;

// Font sizes
$font-size-h1: 50px;
$font-size-h2: 40px;
$font-size-h3: 30px;
$font-size-h4: 20px;
$font-size-body: 17px;
$font-size-button: 18px;

// Letter spacing
$letter-spacing-heading: 0.1em;
$letter-spacing-button: 0.1em;
```

## Font Styles (`_typography.scss`)

Apply variables to HTML elements:

```scss
body {
    font-family: $font-body;
    font-size: $font-size-body;
}

h1, h2, h3, h4, h5, h6 {
    font-family: $font-heading;
    font-weight: 700;
    letter-spacing: $letter-spacing-heading;
}

h1 { font-size: $font-size-h1; }
h2 { font-size: $font-size-h2; }
h3 { font-size: $font-size-h3; }
h4 { font-size: $font-size-h4; }
```

## Loading Web Fonts

Load Google Fonts in `functions.php`:

```php
function sperling_add_google_fonts() {
    $google_fonts_url = 'https://fonts.googleapis.com/css2?family=...&display=swap';
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="<?php echo $google_fonts_url; ?>" rel="stylesheet">
    <?php
}
add_action('wp_head', 'sperling_add_google_fonts', 1);
```

## Usage Rules

1. **Never hardcode font names** - Use `$font-heading` or `$font-body`
2. **Never hardcode sizes** - Use `$font-size-*` variables
3. **Define variables** in `_variables.scss`
4. **Apply styles** in `_typography.scss`

## File Locations

- Variables: `web/app/themes/{theme-name}/assets/styles/common/_variables.scss`
- Typography: `web/app/themes/{theme-name}/assets/styles/common/_typography.scss`
- Fonts: `web/app/themes/{theme-name}/functions.php`
