<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
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
            $codpesAdmins = explode(',', trim(config('ccg.codpesAdmins')));
            
            return Auth::check() && in_array(Auth::user()->id, $codpesAdmins); // Retornará true ou false
        }); 

        # Verifica se o usuário logado é aluno ativo de graduação na unidade
        Gate::define('alunos', function ($alunos) {
            return Graduacao::verifica(Auth::user()->id, config('ccg.codUnd')); // Retornará true ou false
        }); 
    }
}
