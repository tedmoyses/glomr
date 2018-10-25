# Glomr

A simple static site builder in pure PHP7

* Light weight, few dependencies
* Fast, simple, extendable
* Uses <a href="https://laravel-guide.readthedocs.io/en/latest/blade/">Blade</a> templates
* Asset pipeline

Easy to use simple command line interface designed to be installed using composer:

```bash
$ composer require tedmoyses/glomr
```

usage:

```bash
$ ./vendor/bin/glomr init
$ ./vendor/bin/glomr watch
```

## Commands

Init command will generate a config file and offer a choice of templated sites, then run a build.
Source files will be available to edit ./src and all ouput lives in ./build
Most paths are configurable.
*Be warned, this command is destructive, it will wipe out all existing files in ./src and ./build*

Watch will start the built in PHP server with configurable options, you built site will be served and file changes will cause a new build on each save.

Build triggers a one off build of the source files

## Options
Commands support the following options:
* -h --help see help text on each command and options
* -d --debug see debug messages
* -e --env allows you to switch from development (default) to production environments. Production will produce single files for CSS and JS with cache busted file names per build
* -c --compression none (default) only applies in producion env, leaves all CSS and JS as is, low will strip comments, high will strip comments, whitespace and perform other minification tasks

## Disclaimer
This was built as a personal project, feedback is welcome but use at your own risk

## Todo
Possibly build support for markdown with frontmatter
Build and publish docker image that can be used if you have docker but don't want to install a PHP runtime
Might build on the md/frontmatter to implement some kind of paginated template content like a blog
