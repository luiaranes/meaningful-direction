# SCSS Colors

Define all color variables in `_variables.scss`.

## Naming Convention

```scss
// Base colors - use descriptive names
$color-{name}: #HEXVAL;

// Examples
$color-tan: #F4EFE7;
$color-dark-green: #4E5E4E;
$color-yellow: #FFD35C;
```

## Semantic Aliases

Always create semantic aliases that describe purpose, not appearance:

```scss
$color-primary: $color-dark-green;
$color-secondary: $color-tan;
$color-accent: $color-yellow;
```

## Usage Rules

1. **Never use hex values directly** in component styles
2. **Prefer semantic aliases** (`$color-primary`) over base colors (`$color-dark-green`)
3. **Add new colors** to `_variables.scss`, not inline
4. **Group related colors** with comments

## File Location

`web/app/themes/{theme-name}/assets/styles/common/_variables.scss`
