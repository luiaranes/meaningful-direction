# Button Styles

Button component patterns based on the Figma design system.

## Button Classes

Create buttons using the `.btn` class with modifiers.

### Primary Styles

| Class | Background | Text | Border |
|-------|------------|------|--------|
| `.btn--primary` | Dark Green | White | None |
| `.btn--secondary` | Tan | Black | None |
| `.btn--dark` | Black | White | None |
| `.btn--white` | White | Black | None |

### Outlined Styles

| Class | Background | Text | Border |
|-------|------------|------|--------|
| `.btn--outline` | Transparent | Black | Black |
| `.btn--outline-white` | Transparent | White | White |

## HTML Structure

```html
<a href="#" class="btn btn--primary">Learn More</a>

<button class="btn btn--outline">Contact Us</button>
```

## SCSS Implementation

Add to `assets/styles/common/_buttons.scss`:

```scss
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 18px;
    font-family: $font-heading;
    font-size: $font-size-button;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: $letter-spacing-button;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;

    // Primary - Dark Green
    &--primary {
        background-color: $color-dark-green;
        color: $color-white;
        border: 1px solid $color-dark-green;

        &:hover {
            background-color: darken($color-dark-green, 10%);
        }
    }

    // Secondary - Tan
    &--secondary {
        background-color: $color-tan;
        color: $color-black;
        border: 1px solid $color-tan;

        &:hover {
            background-color: darken($color-tan, 5%);
        }
    }

    // Dark - Black
    &--dark {
        background-color: $color-black;
        color: $color-white;
        border: 1px solid $color-black;

        &:hover {
            background-color: lighten($color-black, 15%);
        }
    }

    // White
    &--white {
        background-color: $color-white;
        color: $color-black;
        border: 1px solid $color-white;

        &:hover {
            background-color: darken($color-white, 5%);
        }
    }

    // Outlined - Black border
    &--outline {
        background-color: transparent;
        color: $color-black;
        border: 1px solid $color-black;

        &:hover {
            background-color: $color-black;
            color: $color-white;
        }
    }

    // Outlined - White border (for dark backgrounds)
    &--outline-white {
        background-color: transparent;
        color: $color-white;
        border: 1px solid $color-white;

        &:hover {
            background-color: $color-white;
            color: $color-black;
        }
    }
}
```

## Usage Guidelines

### On Light Backgrounds (Tan/White)

```html
<a href="#" class="btn btn--primary">Learn More</a>
<a href="#" class="btn btn--outline">Learn More</a>
```

### On Dark Backgrounds (Dark Green/Black)

```html
<a href="#" class="btn btn--white">Learn More</a>
<a href="#" class="btn btn--outline-white">Learn More</a>
```

### With Icons

```html
<a href="#" class="btn btn--primary">
    Learn More
    <svg class="btn__icon">...</svg>
</a>
```

```scss
.btn__icon {
    margin-left: 8px;
    width: 18px;
    height: 18px;
}
```

## Setup

1. Create `assets/styles/common/_buttons.scss`
2. Add to `app.scss`:
   ```scss
   @import 'common/buttons';
   ```
3. Run `npx gulp build`

## Best Practices

1. **Use semantic elements** - `<a>` for links, `<button>` for actions
2. **Always include hover states** - for better UX
3. **Match background context** - use outline-white on dark backgrounds
4. **Keep text short** - "Learn More", "Contact Us", "View Details"
