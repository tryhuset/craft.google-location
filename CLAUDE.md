# CLAUDE.md

Guidance for Claude Code when working in this repository.

## Local development runs in DDEV

This project **must be run locally through [DDEV](https://ddev.com/)** — the
`.ddev/` directory holds the local environment config. Do not run `php`,
`composer`, or `craft` directly on the host; run them inside the DDEV web
container so they use the project's configured PHP version and database.

Common commands:

```bash
ddev start              # start the containers (do this first)
ddev composer install   # install PHP dependencies
ddev composer update    # update dependencies
ddev php <args>         # run PHP inside the container
ddev craft <args>       # run Craft console commands
ddev describe           # show project URLs and service status
```

Environment (see `.ddev/config.yaml`): PHP 8.3, nginx-fpm, MariaDB 10.11.

## About this plugin

`apt/craft-google-location` is a Craft CMS plugin that adds Google Maps
location and area fields to the control panel. It depends on
`apt/craft-countries-module` (resolved from the `git1.apt.no` VCS repo
declared in `composer.json`).
