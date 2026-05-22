# JavaScript Standards

Conventions for writing JavaScript in the theme.

## File Structure

| Purpose | Location |
|---------|----------|
| Main app code | `assets/js/app.js` |
| Third-party libraries | `assets/js/lib/` |
| Compiled output | `dist/js/app.min.js` |

## Linting with JSHint

The theme uses **JSHint** for linting. Configuration is in `.jshintrc`.

### Running the Linter

```bash
gulp js
```

This will:
1. Lint all JS files with JSHint
2. Fail the build if there are errors
3. Concatenate and minify to `dist/js/app.min.js`

### JSHint Rules

Key rules enforced (from `.jshintrc`):

| Rule | Value | Meaning |
|------|-------|---------|
| `strict` | true | Require `'use strict'` |
| `camelcase` | true | Use camelCase for variables |
| `eqeqeq` | true | Use `===` instead of `==` |
| `quotmark` | single | Use single quotes |
| `indent` | 4 | 4-space indentation |
| `varstmt` | true | Disallow `var`, use `let`/`const` |
| `unused` | true | Warn on unused variables |
| `undef` | true | Warn on undefined variables |

## DOM-Based Routing

The theme uses a routing pattern that fires JavaScript based on body classes.

### Structure

```javascript
'use strict';

(function ($) {
    const wh = {
        // Fires on ALL pages
        common: {
            init: function () {
                // On DOM ready
            },
            finalize: function () {
                // After init
            },
        },
        // Fires on pages with body class "home"
        home: {
            init: function () {
                // Home page specific
            },
        },
        // Add more page-specific handlers...
        // Use underscores for dashed class names: "about_us" for "about-us"
    };

    const UTIL = {
        fire: function (func, funcname, args) {
            const namespace = wh;
            funcname = funcname === undefined ? 'init' : funcname;
            if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function () {
            UTIL.fire('common');
            $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function (i, classnm) {
                UTIL.fire(classnm);
                UTIL.fire(classnm, 'finalize');
            });
            UTIL.fire('common', 'finalize');
        },
    };

    $(document).ready(UTIL.loadEvents);
})(jQuery);
```

### Adding Page-Specific Code

1. Find the body class for your page (e.g., `page-about` → `page_about`)
2. Add a new object to the `wh` constant:

```javascript
page_about: {
    init: function () {
        // Code for about page
    },
},
```

## Code Style

### Do

```javascript
'use strict';

const myVariable = 'value';
let counter = 0;

if (condition === true) {
    doSomething();
}

$('.selector').on('click', function () {
    // handler
});
```

### Don't

```javascript
var myVariable = "value";  // Don't use var or double quotes

if (condition == true) {   // Don't use ==
    doSomething()
}
```

## Best Practices

1. **Always use `'use strict'`** at the top of your code
2. **Use `const` and `let`** - never `var`
3. **Use single quotes** for strings
4. **Use `===`** for comparisons, not `==`
5. **Use camelCase** for variable and function names
6. **Add page-specific code** using the routing pattern, not inline scripts
7. **Third-party libraries** go in `assets/js/lib/`
8. **Run `npx gulp build`** after making changes to compile and minify
