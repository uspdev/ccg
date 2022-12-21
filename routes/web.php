<?php
use App\Http\Controllers\AlunosDispensasController;
use App\Http\Controllers\AlunosObservacoesController;
use App\Http\Controllers\CurriculoController;
use App\Http\Controllers\DisciplinasLicenciaturaController;
use App\Http\Controllers\DisciplinasLicenciaturasEquivalenteController;
use App\Http\Controllers\DisciplinasObrigatoriaController;
use App\Http\Controllers\DisciplinasObrigatoriasEquivalenteController;
use App\Http\Controllers\DisciplinasOptativasEletivaController;
use App\Http\Controllers\GraduacaoController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

# index
Route::get('/', [IndexController::class, 'index']);

# rotas para a senha única
// Route::get('/login', 'Auth\LoginController@redirectToProvider')->name('login');
// Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
// Route::post('/logout', 'Auth\LoginController@logout');

# secretaria
# busca
Route::get('/busca', [GraduacaoController::class, 'search']);
Route::post('/busca', [GraduacaoController::class, 'dadosAcademicos']);
Route::get('/busca/{parteNome}', [GraduacaoController::class, 'searchAlunos']);

# aluno
# meus créditos
Route::get('/creditos', [GraduacaoController::class, 'creditos']);
Route::get('/creditos/{aluno}', [GraduacaoController::class, 'aluno']);
Route::get('/creditos/{aluno}/pdf', [GraduacaoController::class, 'pdf'])->name('aluno.pdf');

Route::post('/creditos', [AlunosObservacoesController::class, 'store']);
Route::post('/creditos/{aluno}', [AlunosObservacoesController::class, 'store']);

Route::post('/dispensas', [AlunosDispensasController::class, 'store']);
Route::post('/dispensas/livres', [AlunosDispensasController::class, 'store']);

# curriculo
Route::resource('/curriculos', CurriculoController::class);
Route::get('/curriculos/{curriculo}', [CurriculoController::class, 'show']);
Route::get('/curriculos/{curriculo}/alunos', [CurriculoController::class, 'alunos']);
Route::get('/curriculos/{curriculo}/print', [CurriculoController::class, 'print']);
Route::get('/curriculos/{curriculo}/copy', [CurriculoController::class, 'copy']);
Route::post('/curriculos/{curriculo}/copy', [CurriculoController::class, 'storeCopy']);
Route::get('/curriculos/{curriculo}/gradeCurricularJupiter', [CurriculoController::class, 'gradeCurricularJupiter']);

# disciplinas obrigatórias
# Route::resource('disciplinasObrigatorias', 'DisciplinasObrigatoriaController');
Route::get('/disciplinasObrigatorias/create/{curriculo}', [DisciplinasObrigatoriaController::class, 'create']);
Route::post('/disciplinasObrigatorias/create/{curriculo}', [DisciplinasObrigatoriaController::class, 'store']);
Route::delete('/disciplinasObrigatorias/{disciplinasObrigatoria}', [DisciplinasObrigatoriaController::class, 'destroy']);

# disciplinas optativas eletivas
# Route::resource('disciplinasOptativasEletivas', [DisciplinasOptativasEletivaController::class, '');
Route::get('/disciplinasOptativasEletivas/create/{curriculo}', [DisciplinasOptativasEletivaController::class, 'create']);
Route::post('/disciplinasOptativasEletivas/create/{curriculo}', [DisciplinasOptativasEletivaController::class, 'store']);
Route::delete('/disciplinasOptativasEletivas/{disciplinasOptativasEletiva}', [DisciplinasOptativasEletivaController::class, 'destroy']);

# disciplinas licenciaturas
# Route::resource('disciplinasLicenciaturas', 'DisciplinasLicenciaturaController');
Route::get('/disciplinasLicenciaturas/create/{curriculo}', [DisciplinasLicenciaturaController::class, 'create']);
Route::post('/disciplinasLicenciaturas/create/{curriculo}', [DisciplinasLicenciaturaController::class, 'store']);
Route::delete('/disciplinasLicenciaturas/{disciplinasLicenciatura}', [DisciplinasLicenciaturaController::class, 'destroy']);

# disciplinas obrigatórias equivalentes
# Route::resource('disciplinasObrEquivalentes', [DisciplinasObrigatoriasEquivalenteController::class,  '');
Route::get('/disciplinasObrEquivalentes/create/{disciplinasObrigatoria}', [DisciplinasObrigatoriasEquivalenteController::class, 'create']);
Route::post('/disciplinasObrEquivalentes/create/{disciplinasObrigatoria}', [DisciplinasObrigatoriasEquivalenteController::class, 'store']);
Route::delete('/disciplinasObrEquivalentes/{disciplinasObrEquivalente}', [DisciplinasObrigatoriasEquivalenteController::class, 'destroy']);
Route::get('/disciplinasObrEquivalentes/{disciplinasObrigatoria}', [DisciplinasObrigatoriasEquivalenteController::class, 'show']);
Route::get('/disciplinasObrEquivalentes/{disciplinasObrEquivalente}/edit', [DisciplinasObrigatoriasEquivalenteController::class, 'edit']);
Route::post('/disciplinasObrEquivalentes/{disciplinasObrEquivalente}', [DisciplinasObrigatoriasEquivalenteController::class, 'update']);

# disciplinas licenciaturas equivalentes
# Route::resource('disciplinasLicEquivalentes', 'DisciplinasLicenciaturasEquivalenteController');
Route::get('/disciplinasLicEquivalentes/create/{disciplinasLicenciatura}', [DisciplinasLicenciaturasEquivalenteController::class, 'create']);
Route::post('/disciplinasLicEquivalentes/create/{disciplinasLicenciatura}', [DisciplinasLicenciaturasEquivalenteController::class, 'store']);
Route::delete('/disciplinasLicEquivalentes/{disciplinasLicEquivalente}', [DisciplinasLicenciaturasEquivalenteController::class, 'destroy']);
Route::get('/disciplinasLicEquivalentes/{disciplinasLicenciatura}', [DisciplinasLicenciaturasEquivalenteController::class, 'show']);
Route::get('/disciplinasLicEquivalentes/{disciplinasLicEquivalente}/edit', [DisciplinasLicenciaturasEquivalenteController::class, 'edit']);
Route::post('/disciplinasLicEquivalentes/{disciplinasLicEquivalente}', [DisciplinasLicenciaturasEquivalenteController::class, 'update']);
