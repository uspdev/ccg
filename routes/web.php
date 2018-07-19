<?php

# index
Route::get('/', 'IndexController@index');

# rotas para a senha única
Route::get('login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('callback', 'Auth\LoginController@handleProviderCallback');
Route::post('logout', 'Auth\LoginController@logout'); 

# secretaria
# busca
Route::get('busca', 'GraduacaoController@busca');
Route::post('busca', 'GraduacaoController@buscaReplicado');
Route::get('busca/{parteNome}', 'GraduacaoController@buscaAlunos');

# aluno
# meus créditos
Route::get('creditos', 'GraduacaoController@creditos');

# curriculo
Route::resource('curriculos', 'CurriculoController');

# disciplinas obrigatórias
# Route::resource('disciplinasObrigatorias', 'DisciplinasObrigatoriaController');
Route::get('disciplinasObrigatorias/create/{curriculo}', 'DisciplinasObrigatoriaController@create');
Route::post('disciplinasObrigatorias/create/{curriculo}', 'DisciplinasObrigatoriaController@store');
Route::delete('disciplinasObrigatorias/{disciplinasObrigatoria}', 'DisciplinasObrigatoriaController@destroy');

# disciplinas optativas eletivas
# Route::resource('disciplinasOptativasEletivas', 'DisciplinasOptativasEletivaController');
Route::get('disciplinasOptativasEletivas/create/{curriculo}', 'DisciplinasOptativasEletivaController@create');
Route::post('disciplinasOptativasEletivas/create/{curriculo}', 'DisciplinasOptativasEletivaController@store');
Route::delete('disciplinasOptativasEletivas/{disciplinasOptativasEletiva}', 'DisciplinasOptativasEletivaController@destroy');