<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        # Verifica se id do usuário logado está na env CODPES_ADMINS
        Gate::define('secretaria', function ($secretaria) {
            if (Auth::check()) {
                return true;
            }
            return false;
        }); 

        # Verifica se o usuário logado é aluno ativo de graduação na unidade
        Gate::define('alunos', function ($alunos) {
            $replicado = new Connection(env('REPLICADO_HOST'), env('REPLICADO_PORT'), env('REPLICADO_DATABASE'), env('REPLICADO_USERNAME'), env('REPLICADO_PASSWORD'));
            env('REPLICADO_SGBD') == 'sybase' ? $replicado->setSybase() : $replicado->setMssql();
            $graduacao = new Graduacao($replicado->conn);
			if ($graduacao->verifica(Auth::user()->id, env('REPLICADO_CODUND'))) {
                return true;
            }
            return false;
        }); 
    }
}
