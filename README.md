# Cloudflare

[![license](https://img.shields.io/github/license/Nearata/flarum-ext-cloudflare?style=flat)](https://github.com/Nearata/flarum-ext-cloudflare/blob/main/UNLICENSE)
[![packagist](https://img.shields.io/packagist/v/nearata/flarum-ext-cloudflare?style=flat)](https://packagist.org/packages/nearata/flarum-ext-cloudflare)
[![changelog](https://img.shields.io/github/release-date/nearata/flarum-ext-cloudflare?label=last%20release%20date)](https://github.com/Nearata/flarum-ext-cloudflare/blob/main/CHANGELOG.md)

> Cloudflare integration.

## Install

```sh
composer require nearata/flarum-ext-cloudflare:"*"
```

## Update

```sh
composer update nearata/flarum-ext-cloudflare:"*"
php flarum cache:clear
```

## Remove

Disable the extension, click purge then execute:

```sh
composer remove nearata/flarum-ext-cloudflare
php flarum cache:clear
```

## How to use R2 driver

You can try to follow this guide [here](https://docs.flarum.org/extend/filesystem/#gui-and-admin-configuration)

or you can add this to your local `config.php`


```config
# Adding driver to Flarum Assets filesystem

<?php return array (
  'debug' => false,
  ...
  'disk_driver.flarum-assets' => 'r2',
  'disk_driver.another-extension-filesystem' => 'r2'
);
```
