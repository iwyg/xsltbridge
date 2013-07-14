<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | File extension
    |--------------------------------------------------------------------------
    | Xslt file extension.
     */

    'extension' => 'xsl',

    /*
    |--------------------------------------------------------------------------
    | XML Conversion settings
    |--------------------------------------------------------------------------
     */

    'xml' => array(
        'rootname'      => 'data',
        'encoding'      => 'UTF-8'
    ),

    /*
    |--------------------------------------------------------------------------
    | XSLTProcessor settings
    |--------------------------------------------------------------------------
     */

    'xsl' => array(
        'phpfunctions'  => true,
        'profiling'     => true,
        // directory relative to `app`
        'profilingdir'  => 'storage/profile'
    ),

    /*
    |--------------------------------------------------------------------------
    | Automatic attribute conversion
    |--------------------------------------------------------------------------
    | You may declare certain array keys to be set as an xml attribute. `*` is
    | a wildcard to match every key name. Note that key names refer to their
    | normalized values, e.g. `foo_bar` would become `foo-bar`, `fooBar` becomes
    | `foo-bar` etc.
    */

    'attributes' => array(
        '*' => array('id', 'created-at', 'updated-at', 'relation-id')
    ),

    /*
    |--------------------------------------------------------------------------
    | Normalizer Settings
    |--------------------------------------------------------------------------
    | Prevent circular references by excluding `app` and `env` in the laravel
    | context
     */

    'normalizer' => array(
        'ignoredattributes' => array('env', 'app')
    ),

    /*
    |--------------------------------------------------------------------------
    | Global Parameters
    |--------------------------------------------------------------------------
     */

    'params' => array(
    )
);

