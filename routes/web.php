<?php

# index
Route::get('/', 'IndexController@index');

# rotas para a senha única
Route::get('login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('callback', 'Auth\LoginController@handleProviderCallback');
Route::post('logout', 'Auth\LoginController@logout'); 

# busca
Route::get('busca', 'GraduacaoController@busca');
Route::post('busca', 'GraduacaoController@buscaReplicado');

