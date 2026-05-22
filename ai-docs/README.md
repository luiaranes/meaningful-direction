# AI Documentation

Coding standards and conventions for AI agents and developers. Reference these specs when implementing features.

## Available Specs

| Spec | Description |
|------|-------------|
| [theme-development.md](theme-development.md) | Layout system (Bulma), PHP templates, file structure |
| [scss-colors.md](scss-colors.md) | Color variables and semantic aliases |
| [scss-typography.md](scss-typography.md) | Fonts, sizes, and typography styles |
| [buttons.md](buttons.md) | Button component styles and usage |
| [assets.md](assets.md) | Images, SVGs, and static assets |
| [javascript.md](javascript.md) | JavaScript standards, linting, DOM routing |

## Build Command

**Always run after editing SCSS or JS files:**

```bash
npx gulp build
```

This compiles SCSS to CSS and minifies JavaScript.

## General Principles

1. **Use Bulma columns** for all layouts (not Bootstrap, Flexbox, or CSS Grid)
2. **Never hardcode values** - Always use SCSS variables
3. **Use semantic aliases** - Prefer `$color-primary` over `$color-dark-green`
4. **Keep variables centralized** - All design tokens in `_variables.scss`
5. **Separate concerns** - Variables define values, other files apply them
6. **Run `npx gulp build`** after any SCSS or JS changes
