<?php

# index
Route::get('/', 'IndexController@index');

# rotas para a senha única
Route::get('/login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
Route::post('/logout', 'Auth\LoginController@logout'); 

# secretaria
# busca
Route::get('/busca', 'GraduacaoController@search');
Route::post('/busca', 'GraduacaoController@dadosAcademicos');
Route::get('/busca/{parteNome}', 'GraduacaoController@searchAlunos');

Route::get('/creditos/{aluno}', 'GraduacaoController@aluno');
Route::get('/creditos/{aluno}/pdf', 'GraduacaoController@pdf')->name('aluno.pdf');
Route::post('/creditos', 'AlunosObservacoesController@store');
Route::post('/creditos/{aluno}', 'AlunosObservacoesController@store');

Route::post('/dispensas', 'AlunosDispensasController@store');

# aluno
# meus créditos
Route::get('/creditos', 'GraduacaoController@creditos');

# curriculo
Route::resource('/curriculos', 'CurriculoController');
Route::get('/curriculos/{curriculo}', 'CurriculoController@show');
Route::get('/curriculos/{curriculo}/alunos', 'CurriculoController@alunos');
Route::get('/curriculos/{curriculo}/print', 'CurriculoController@print');
Route::get('/curriculos/{curriculo}/copy', 'CurriculoController@copy');
Route::post('/curriculos/{curriculo}/copy', 'CurriculoController@storeCopy');

# disciplinas obrigatórias
# Route::resource('disciplinasObrigatorias', 'DisciplinasObrigatoriaController');
Route::get('/disciplinasObrigatorias/create/{curriculo}', 'DisciplinasObrigatoriaController@create');
Route::post('/disciplinasObrigatorias/create/{curriculo}', 'DisciplinasObrigatoriaController@store');
Route::delete('/disciplinasObrigatorias/{disciplinasObrigatoria}', 'DisciplinasObrigatoriaController@destroy');

# disciplinas optativas eletivas
# Route::resource('disciplinasOptativasEletivas', 'DisciplinasOptativasEletivaController');
Route::get('/disciplinasOptativasEletivas/create/{curriculo}', 'DisciplinasOptativasEletivaController@create');
Route::post('/disciplinasOptativasEletivas/create/{curriculo}', 'DisciplinasOptativasEletivaController@store');
Route::delete('/disciplinasOptativasEletivas/{disciplinasOptativasEletiva}', 'DisciplinasOptativasEletivaController@destroy');

# disciplinas licenciaturas
# Route::resource('disciplinasLicenciaturas', 'DisciplinasLicenciaturaController');
Route::get('/disciplinasLicenciaturas/create/{curriculo}', 'DisciplinasLicenciaturaController@create');
Route::post('/disciplinasLicenciaturas/create/{curriculo}', 'DisciplinasLicenciaturaController@store');
Route::delete('/disciplinasLicenciaturas/{disciplinasLicenciatura}', 'DisciplinasLicenciaturaController@destroy');

# disciplinas obrigatórias equivalentes
# Route::resource('disciplinasObrEquivalentes', 'DisciplinasObrigatoriasEquivalenteController');
Route::get('/disciplinasObrEquivalentes/create/{disciplinasObrigatoria}', 'DisciplinasObrigatoriasEquivalenteController@create');
Route::post('/disciplinasObrEquivalentes/create/{disciplinasObrigatoria}', 'DisciplinasObrigatoriasEquivalenteController@store');
Route::delete('/disciplinasObrEquivalentes/{disciplinasObrEquivalente}', 'DisciplinasObrigatoriasEquivalenteController@destroy');
Route::get('/disciplinasObrEquivalentes/{disciplinasObrigatoria}', 'DisciplinasObrigatoriasEquivalenteController@show');
Route::get('/disciplinasObrEquivalentes/{disciplinasObrEquivalente}/edit', 'DisciplinasObrigatoriasEquivalenteController@edit');
Route::post('/disciplinasObrEquivalentes/{disciplinasObrEquivalente}', 'DisciplinasObrigatoriasEquivalenteController@update');

# disciplinas licenciaturas equivalentes
# Route::resource('disciplinasLicEquivalentes', 'DisciplinasLicenciaturasEquivalenteController');
Route::get('/disciplinasLicEquivalentes/create/{disciplinasLicenciatura}', 'DisciplinasLicenciaturasEquivalenteController@create');
Route::post('/disciplinasLicEquivalentes/create/{disciplinasLicenciatura}', 'DisciplinasLicenciaturasEquivalenteController@store');
Route::delete('/disciplinasLicEquivalentes/{disciplinasLicEquivalente}', 'DisciplinasLicenciaturasEquivalenteController@destroy');
Route::get('/disciplinasLicEquivalentes/{disciplinasLicenciatura}', 'DisciplinasLicenciaturasEquivalenteController@show');
Route::get('/disciplinasLicEquivalentes/{disciplinasLicEquivalente}/edit', 'DisciplinasLicenciaturasEquivalenteController@edit');
Route::post('/disciplinasLicEquivalentes/{disciplinasLicEquivalente}', 'DisciplinasLicenciaturasEquivalenteController@update');