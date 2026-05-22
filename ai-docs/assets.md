# Images & Assets

Conventions for handling images and static assets.

## File Locations

| Type | Source | Compiled |
|------|--------|----------|
| Images | `assets/images/` | `dist/images/` |
| Fonts | `assets/fonts/` | `dist/fonts/` |
| SVGs | `assets/images/` | `dist/images/` |

## Adding Images

1. Save images to `assets/images/`
2. Run `npx gulp build` to optimize and copy to `dist/`
3. Reference in templates using the dist path

### In PHP Templates

```php
<img src="<?php echo get_template_directory_uri(); ?>/dist/images/image-name.jpg" alt="Description">
```

### In SCSS

```scss
background-image: url('../images/image-name.jpg');
```

## Image Naming Conventions

Use lowercase with hyphens:

```
hero-background.jpg
icon-arrow-right.svg
logo-white.png
```

### Naming Pattern

```
[component]-[description]-[variant].[ext]

Examples:
hero-banner-desktop.jpg
hero-banner-mobile.jpg
icon-facebook.svg
icon-instagram.svg
```

## Image Optimization

Gulp automatically optimizes images when running `npx gulp build`.

### Recommended Formats

| Use Case | Format |
|----------|--------|
| Photos | `.jpg` (compressed) |
| Icons/Logos | `.svg` (scalable) |
| Transparency | `.png` |
| Animated | `.gif` (use sparingly) |

### Size Guidelines

- **Hero images**: Max 1920px wide
- **Content images**: Max 1200px wide
- **Thumbnails**: 400-600px wide
- **File size**: Keep under 200KB when possible

## SVG Icons

For icons, prefer inline SVG or SVG sprites for styling flexibility.

### Inline SVG Example

```php
<svg class="icon icon--arrow" width="24" height="24" viewBox="0 0 24 24">
    <path d="..."/>
</svg>
```

### Styling SVGs

```scss
.icon {
    fill: $color-primary;
    width: 24px;
    height: 24px;

    &--white {
        fill: $color-white;
    }
}
```

## Alt Text

**Always include descriptive alt text for images.**

```php
// Good
<img src="..." alt="Wedding ceremony at Topsfield Fair Arena">

// Bad
<img src="..." alt="image1">
<img src="..." alt="">  // Only OK for decorative images
```

## Best Practices

1. **Optimize before upload** - compress images before adding to repo
2. **Use descriptive names** - not `IMG_1234.jpg`
3. **Include alt text** - for accessibility and SEO
4. **Use SVG for icons** - scalable and styleable
5. **Run `npx gulp build`** - after adding new images
