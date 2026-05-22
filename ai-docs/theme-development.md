# Theme Development

Conventions for building WordPress theme templates and sections.

## Layout System (Bulma Columns)

This theme uses **Bulma's column system** (v0.8.0) for layouts. Do NOT use Bootstrap, Flexbox utilities, or CSS Grid directly.

### Basic Structure

```html
<div class="container">
    <div class="columns">
        <div class="column">
            <!-- content -->
        </div>
    </div>
</div>
```

### Column Sizes

Use Bulma size classes on `.column`:

| Class | Width |
|-------|-------|
| `is-full` | 100% |
| `is-three-quarters` | 75% |
| `is-two-thirds` | 66.66% |
| `is-half` | 50% |
| `is-one-third` | 33.33% |
| `is-one-quarter` | 25% |
| `is-1` to `is-12` | 12-column grid |

### Responsive Suffixes

Add breakpoint suffix for responsive columns:

| Suffix | Breakpoint |
|--------|------------|
| `-mobile` | max 768px |
| `-tablet` | min 769px |
| `-desktop` | min 1024px |
| `-widescreen` | min 1216px |
| `-fullhd` | min 1408px |

**Always specify all breakpoints** for responsive layouts:

```html
<div class="column is-4-desktop is-6-tablet is-12-mobile">
```

This means:
- **Desktop** (1024px+): 4 columns wide (33.33%)
- **Tablet** (769px-1023px): 6 columns wide (50%)
- **Mobile** (≤768px): 12 columns wide (100%, full width)

### Common Responsive Patterns

**Three columns → two → one:**
```html
<div class="columns is-multiline">
    <div class="column is-4-desktop is-6-tablet is-12-mobile">Item 1</div>
    <div class="column is-4-desktop is-6-tablet is-12-mobile">Item 2</div>
    <div class="column is-4-desktop is-6-tablet is-12-mobile">Item 3</div>
</div>
```

**Two columns → stacked:**
```html
<div class="columns is-multiline">
    <div class="column is-6-desktop is-12-tablet is-12-mobile">Left</div>
    <div class="column is-6-desktop is-12-tablet is-12-mobile">Right</div>
</div>
```

**Four columns → two → one:**
```html
<div class="columns is-multiline">
    <div class="column is-3-desktop is-6-tablet is-12-mobile">Item</div>
    <div class="column is-3-desktop is-6-tablet is-12-mobile">Item</div>
    <div class="column is-3-desktop is-6-tablet is-12-mobile">Item</div>
    <div class="column is-3-desktop is-6-tablet is-12-mobile">Item</div>
</div>
```

### Column Modifiers

| Class | Effect |
|-------|--------|
| `is-centered` | Center columns horizontally (on `.columns`) |
| `is-vcentered` | Center columns vertically (on `.columns`) |
| `is-multiline` | Allow columns to wrap (on `.columns`) |
| `is-gapless` | Remove column gaps (on `.columns`) |

### Example: Two Column Layout

```html
<div class="container">
    <div class="columns">
        <div class="column is-two-thirds">
            <!-- main content -->
        </div>
        <div class="column is-one-third">
            <!-- sidebar -->
        </div>
    </div>
</div>
```

### Example: Three Equal Columns

```html
<div class="container">
    <div class="columns">
        <div class="column">First</div>
        <div class="column">Second</div>
        <div class="column">Third</div>
    </div>
</div>
```

## PHP Template Structure

### Page Templates

```php
<?php get_header(); ?>

<div class="container">
    <div class="columns">
        <div class="column">

            <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>

        </div>
    </div>
</div>

<?php get_footer(); ?>
```

### Custom Sections

For custom page sections, use the pattern:

```php
<section class="section-name">
    <div class="container">
        <div class="columns">
            <div class="column">
                <!-- section content -->
            </div>
        </div>
    </div>
</section>
```

## SCSS for Sections

**Every section must have its own SCSS file.**

### Creating a Section SCSS File

1. Create the file in `assets/styles/layouts/`:
   ```
   assets/styles/layouts/_section-name.scss
   ```

2. Import it in `app.scss`:
   ```scss
   @import 'layouts/section-name';
   ```

### Section SCSS Structure

```scss
.section-name {
    // Section container styles
    padding: 60px 0;
    background-color: $color-secondary;

    .section-name__title {
        // Element styles using BEM naming
    }

    .section-name__content {
        // Nested elements
    }
}
```

### Naming Convention

- Use the section class name for the file: `.hero-banner` → `_hero-banner.scss`
- Use BEM naming for nested elements: `.section-name__element`
- Use modifiers for variations: `.section-name--dark`

### ACF Fields

Use Advanced Custom Fields for dynamic content:

```php
<?php 
$field_value = get_field('field_name');
$option_value = get_field('field_name', 'option');
?>
```

## File Locations

| Purpose | Location |
|---------|----------|
| Page templates | `web/app/themes/{theme}/` |
| Template parts | `web/app/themes/{theme}/template-parts/` |
| SCSS styles | `web/app/themes/{theme}/assets/styles/` |
| Images | `web/app/themes/{theme}/dist/images/` |
| JavaScript | `web/app/themes/{theme}/assets/scripts/` |
| ACF JSON | `web/app/themes/{theme}/acf-json/` |

## ACF Local JSON

ACF field groups are synced as JSON files in the `acf-json/` folder inside the theme.

### Setup

1. Create the folder: `web/app/themes/{theme}/acf-json/`
2. ACF will automatically save field group JSON files here when you save in the admin

### File Naming

ACF generates files named by field group key:
- `group_abc123.json`

### Version Control

- **Commit ACF JSON files** to Git for team sync
- When pulling changes, go to **Custom Fields → Sync** in WP admin to import

## Best Practices

1. **Always use Bulma columns** for layout - never raw flexbox or CSS grid
2. **Wrap content in `.container`** for consistent max-width
3. **Use `.columns` > `.column`** hierarchy - never skip levels
4. **Add responsive classes** for mobile-friendly layouts (`is-X-desktop is-X-tablet is-X-mobile`)
5. **Create an SCSS file for every section** in `assets/styles/layouts/`
6. **Import new SCSS files** in `app.scss`
7. **Use BEM naming** for section elements (`.section__element--modifier`)
8. **Run `npx gulp build`** after any SCSS or JS changes to compile
