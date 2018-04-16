<?php

Route::get('/busca', 'GraduacaoController@busca');
Route::post('/busca', 'GraduacaoController@buscaReplicado');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
