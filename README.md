# Cloudflare

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
