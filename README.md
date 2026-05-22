# Sperling WP Boilerplate v3.0

## Dev Dependencies

-   **Lando** v3.22.1 or later; [follow instructions for your OS here](https://docs.devwithlando.io/getting-started/installation.html)
-   **Docker Desktop** v4.34.3 or later
    -   Note that the Lando installer for MacOS and Windows includes Docker Desktop! It's recommended to use the pair of versions that come together to ensure compatibility
-   **Node JS** v18.19.0 or later
-   **NPM** v10.2.4 or later
    -   Use **nvm** to manage node version easily
        -   Download NVM for windows [here](https://github.com/coreybutler/nvm-windows)
        -   For Mac and Linux [here](https://github.com/nvm-sh/nvm)

## Installation

1. Clone this repository in a folder with your project name:
    - SSH: `git clone git@github.com:sperling-interactive/sperling-wp-boilerplate-v3.git {project-name}`
    - HTTPS: `git clone https://github.com/sperling-interactive/sperling-wp-boilerplate-v3.git {project-name}`
2. Edit value of `name` in `lando.yml` into your `{project-name}`.
3. Run `lando start` to create the project's Lando appserver and database.
4. Duplicate `.env.example` and name it `.env` then open and edit the following values:
    - `WP_HOME` - Set it to `https://{project-name}.lndo.site`
    - `WP_TITLE` - Your site title (optional, defaults to 'My WordPress Site')
    - `WP_ADMIN_USER` - Admin username (optional, defaults to 'admin')
    - `WP_ADMIN_PASSWORD` - Admin password (optional, auto-generates if not set)
    - `WP_ADMIN_EMAIL` - Admin email (optional, defaults to 'admin@example.com')
5. Run `lando composer install` to install Wordpress and a list of our commonly used plugins (see `composer.json` file).
6. Run `lando composer wp-activate` to install WordPress, activate all plugins and theme automatically.
    - Note: This will only be a dummy WP install because we will clone the sandbox DB in later steps
    - The command uses environment variables from your `.env` file for WordPress installation
7. Navigate to `web/app/themes/` and rename the `sperling-starter-theme` folder to your `{project-name}`
8. Inside the theme folder, run the following commands to setup theme:
    - Check your nodejs and npm version, `nvm use 18` to change node version if needed
    - `npm install` to install dependencies
    - `gulp build` to build assets of the project, or alternatively use `gulp watch` during an active development to run browser-sync
9. Login to Wordpress Admin, go to the Plugins menu, and active the **WP Sync DB** and **WP Sync DB Media Files** plugins.
10. Go to Tools > Migrate DB then configure pull settings to pull DB from staging site [https://sperling-boilerplate-v3.sperlingsandbox.com/](https://sperling-boilerplate-v3.sperlingsandbox.com/)
11. Replace all instances of the default theme folder `sperling-starter-theme` to your `{project-name}` in the files:
    - `composer.json`
    - `.github-ci.yml`

## Coding Standards

[PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) is included in this boilerplate through the project composer installation to ensure we are following [Wordpress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).

#### Shortcut Scripts

Use `lando composer test-dev` to check for any errors and warnings for php files in the theme folder. This will run:

```
phpcs --report=full --colors -p --standard=Wordpress --ignore=*/node_modules/* web/app/themes/{project-name} --extensions=php
```

Use `lando composer test-fix` to fix fixable errors inside the project's theme folder. This will run the command:

```
phpcbf -p web/app/themes/{project-name} --standard=Wordpress --ignore=*/node_modules/* --extensions=php
```

#### Other Notes

-   Optionally run [your own set of configs](https://github.com/WordPress/WordPress-Coding-Standards#how-to-use) by utilising the other options phpcs and wpcs offer.
-   You can [install phpcs globally](https://github.com/squizlabs/PHP_CodeSniffer#composer), or you can use the project instance by calling `./vendor/bin/phpcs` directly.
-   This requires at least PHP version 8.3.0 so if yours is outdated, you can use `lando php ./vendor/bin/phpcs` instead.
