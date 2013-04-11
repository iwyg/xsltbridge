<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
     */

    'extension' => 'xsl',

	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
     */

    'xsl' => array(
        'rootname'      => 'data',
        'optimizations' => -1,
        'phpfunctions'  => true,
        'profiling'     => true,
    ),


	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
	*/

    'attributes' => array(
        '*' => array('id', 'created-at')
    ),

	/*
	|--------------------------------------------------------------------------
	| Normalizer Settings
	|--------------------------------------------------------------------------
	|
	| 
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
     */

    'normalizer' => array(
        'ignoredattributes' => array('env', 'app')
    ),

	/*
	|--------------------------------------------------------------------------
	| Global Parameters
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
     */

    'params' => array(
        'foo' => 'bar'
    )
);
