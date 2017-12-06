<?php

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/register', function (){ return redirect()->route('login'); })->name('register');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('ms-admin', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

//dropzone
Route::post('upload', ['as' => 'upload-post', 'uses' =>'ImageController@postUpload']);
Route::post('upload/delete', ['as' => 'upload-remove', 'uses' =>'ImageController@deleteUpload']);

Route::resource('admin/themes', 'ThemesController');
Route::get('admin/themes/{id}/delete', 'ThemesController@delete');
Route::get('admin/themes/{id}/deleteimg', 'ThemesController@deleteimg');
Route::get('admin/themes/{id}/activate', 'ThemesController@activate');
Route::get('admin/themes/{id}/deactivate', 'ThemesController@deactivate');

Route::resource('admin/blocks', 'BlocksController');
Route::get('admin/blocks/{id}/delete', 'BlocksController@delete');
Route::get('admin/blocks/publish/{id}', 'BlocksController@publish');

Route::post('admin/boxes/{id}/updateLang', 'BoxesController@updateLang');
Route::resource('admin/boxes', 'BoxesController');
Route::get('admin/boxes/{id}/delete', 'BoxesController@delete');
Route::get('admin/boxes/publish/{id}', 'BoxesController@publish');

Route::get('admin/categories/sortable', 'CategoriesController@sortable');
Route::post('admin/categories/search', 'CategoriesController@search');
Route::post('admin/categories/{id}/updateLang', 'CategoriesController@updateLang');
Route::get('admin/categories/{id}/properties', 'CategoriesController@properties');
Route::post('admin/categories/{id}/propertiesPost', 'CategoriesController@propertiesPost');
Route::resource('admin/categories', 'CategoriesController');
Route::get('admin/categories/{id}/delete', 'CategoriesController@delete');
Route::post('admin/categories/{id}/deleteimg', 'CategoriesController@deleteImg');
Route::post('admin/categories/sortable', 'CategoriesController@sortableUpdate');
Route::get('admin/categories/publish/{id}', 'CategoriesController@publish');

Route::resource('admin/menus', 'MenusController');
Route::get('admin/menus/{id}/delete', 'MenusController@delete');

Route::resource('admin/carts', 'CartsController');
Route::get('admin/carts/{id}/delete', 'CartsController@delete');

Route::get('admin/menus/{id}/images', 'MenusController@images');
Route::get('admin/menus/{id}/createImage', 'MenusController@createImage');
Route::post('admin/menus/{id}/storeImage', 'MenusController@storeImage');
Route::get('admin/menus/{id}/editImage', 'MenusController@editImage');
Route::put('admin/menus/{id}/updateImage', 'MenusController@updateImage');
Route::post('admin/menus/{id}/updateLangImage', 'MenusController@updateLangImage');
Route::get('admin/menus/{id}/deleteImage', 'MenusController@deleteImage');
Route::get('admin/menus/images/publish/{id}', 'MenusController@publish');

Route::post('admin/menus/{id}/sortable', 'MenusController@editLinksUpdate');
Route::get('admin/menus/{id}/editLinks', 'MenusController@editLinks');
Route::post('admin/menus/{id}/editLinks', 'MenusController@editLinksUpdate');
Route::get('admin/menus/{id}/editLink', 'MenusController@editLink');
Route::post('admin/menus/{id}/editLink', 'MenusController@editLinkUpdate');
Route::get('admin/menus/publish/{id}', 'MenusController@publish');
Route::get('admin/menus/images/publish/{id}', 'MenusController@publishImage');

Route::post('admin/menuLinks/{id}/attributes', 'MenusController@menuLinksAttributesPost');

Route::post('admin/posts/{id}/updateLang', 'PostsController@updateLang');
Route::post('admin/posts/search', 'PostsController@search');
Route::resource('admin/posts', 'PostsController');
Route::get('admin/posts/{id}/delete', 'PostsController@delete');
Route::post('admin/posts/{id}/deleteimg', 'PostsController@deleteimg');
Route::get('admin/posts/publish/{id}', 'PostsController@publish');

Route::post('admin/tags/{id}/updateLang', 'TagsController@updateLang');
Route::resource('admin/tags', 'TagsController');
Route::get('admin/tags/{id}/delete', 'TagsController@delete');

Route::resource('admin/languages', 'LanguagesController');
Route::get('admin/languages/{id}/delete', 'LanguagesController@delete');
Route::get('admin/languages/publish/{id}', 'LanguagesController@publish');

Route::resource('admin/settings', 'SettingsController');
Route::post('admin/settings/{id}/updateLang', 'SettingsController@updateLang');

// filemanager
Route::get('filemanager/show', 'FilemanagerController@index');

Route::resource('admin/users', 'UsersController');
Route::post('admin/users/search', 'UsersController@search');
Route::get('admin/users/{id}/delete', 'UsersController@delete');

Route::get('admin/pcategories/sortable', 'PCategoriesController@sortable');
Route::post('admin/pcategories/{id}/updateLang', 'PCategoriesController@updateLang');
Route::resource('admin/pcategories', 'PCategoriesController');
Route::post('admin/pcategories/sortable', 'PCategoriesController@sortableUpdate');
Route::get('admin/pcategories/{id}/delete', 'PCategoriesController@delete');
Route::get('admin/pcategories/{id}/deleteimage', 'PCategoriesController@deleteimg');
Route::get('admin/pcategories/publish/{id}', 'PCategoriesController@publish');

Route::get('admin/products/clear', 'ProductsController@clearSearch');
Route::post('admin/products/{id}/updateLang', 'ProductsController@updateLang');
Route::post('admin/products/{id}/updateaAttribute', 'ProductsController@updateaAttribute');
Route::resource('admin/products', 'ProductsController');
Route::post('admin/products/search', 'ProductsController@search');
Route::get('admin/products/publish/{id}', 'ProductsController@publish');
Route::get('admin/products/{id}/delete', 'ProductsController@delete');
Route::get('admin/products/{id}/clone', 'ProductsController@cloneProduct');
Route::get('admin/products/{id}/deleteimg', 'ProductsController@deleteimg');
Route::get('admin/products/{id}/deleteimgtmb', 'ProductsController@deleteimgtmb');
Route::get('admin/products/{id}/image', 'ProductsController@image');
Route::post('admin/products/{id}/deleteimage', 'ProductsController@deleteImage');

Route::post('admin/brands/{id}/updateLang', 'BrandsController@updateLang');
Route::get('admin/brands/{id}/attribute', 'BrandsController@attribute');
Route::post('admin/brands/{id}/attribute', 'BrandsController@attributeUpdate');
Route::resource('admin/brands', 'BrandsController');
Route::get('admin/brands/{id}/block', 'BrandsController@block');
Route::get('admin/brands/{id}/delete', 'BrandsController@delete');
Route::get('admin/brands/{id}/deleteimg', 'BrandsController@deleteimg');
Route::get('admin/brands/publish/{id}', 'BrandsController@publish');

Route::get('admin/attributes/removesearch', 'AttributesController@removeSearch');
Route::post('admin/attributes/search', 'AttributesController@search');
Route::get('admin/attributes/{id}/sortable', 'AttributesController@sortable');
Route::post('admin/attributes/{id}/updateLang', 'AttributesController@updateLang');
Route::resource('admin/attributes', 'AttributesController');
Route::post('admin/attributes/sortable', 'AttributesController@sortable_ajax');
Route::get('admin/attributes/{id}/delete', 'AttributesController@delete');
Route::get('admin/attributes/publish/{id}', 'AttributesController@publish');

Route::get('admin/properties/sortable', 'PropertiesController@sortable');
Route::post('admin/properties/{id}/updateLang', 'PropertiesController@updateLang');
Route::resource('admin/properties', 'PropertiesController');
Route::post('admin/properties/sortable', 'PropertiesController@sortable_ajax');
Route::get('admin/properties/{id}/delete', 'PropertiesController@delete');
Route::get('admin/properties/publish/{id}', 'PropertiesController@publish');