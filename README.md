xsltbridge
==========

XSLTBridge for Laravel 4 views  
[![Build Status](https://travis-ci.org/iwyg/xsltbridge.png?branch=master)](https://travis-ci.org/iwyg/xsltbridge)


## Installation

Add thapp\xsltbridge as a requirement to composer.json:

```json
{
    "require": {
        "thapp/xsltbridge": "0.1.*"
    }
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

`$ php artisan config:publish thapp/xsltbridge`

## Working with data

### Adding parameters

A convenient way of adding xslt parameters is to listen to the `xsltbridge.addparameters` event.  
Parametes will be also reflected within the `<data><param/></data>` node; 

```php
Event::listen('xsltbridge.addparameters', function ($engine) 
{
	$engine->addGlobalData(array('parameter' => 'value');
});
```
