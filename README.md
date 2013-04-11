xsltbridge
==========

XSLTBridge for Laravel 4 views


## Installation

Add thapp\xsltbridge as a requirement to composer.json:

```json
{
    "require": {
        "thapp/xsltbridge": "dev-master"
    },
    "repositories": [
        {
        "type":"vcs",
        "url":"https://github.com/iwyg/xsltbridge"
        }
    ]
}
```

Then run `composer update` or `composer install`

Next step is to tell laravel to load the serviceprovider. In `app/config/app.php` add

```php
  // ...
  'Thapp\XsltBridge\XsltServiceProvider' 
  // ...
```
to the `providers` array.


## Configuration

`$ php artisan config:publish thapp\xsltbridge`
