<?php

Route::get('/sso/endpoints/metadata', 'Elipce\SSO\Http\Controllers\MetadataController@index');
Route::post('/sso/endpoints/acs', 'Elipce\SSO\Http\Controllers\AttributeConsumerServiceController@index');
Route::get('/sso/endpoints/sls', 'Elipce\SSO\Http\Controllers\SingleLogoutServiceController@index');