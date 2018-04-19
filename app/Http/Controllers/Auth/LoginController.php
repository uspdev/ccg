<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('senhaunica')->redirect();
    }

    public function handleProviderCallback()
    {
        $userSenhaUnica = Socialite::driver('senhaunica')->user();
        
        // aqui vc pode inserir o usuário no banco de dados local, fazer o login etc.
        
		# busca o usuário local
        $user = User::find($userSenhaUnica->id);
        
		# restrição só para admins/secretaria e aluno ativo de graduação na unidade
        $secretaria = explode(',', trim(env('CODPES_ADMINS')));

        $replicado = new Connection(env('REPLICADO_HOST'), env('REPLICADO_PORT'), env('REPLICADO_DATABASE'), env('REPLICADO_USERNAME'), env('REPLICADO_PASSWORD'));
        env('REPLICADO_SGBD') == 'sybase' ? $replicado->setSybase() : $replicado->setMssql();
        $graduacao = new Graduacao($replicado->conn);
        
		if ( (!in_array($userSenhaUnica->id, $secretaria)) && (!$graduacao->verifica($userSenhaUnica->id, env('REPLICADO_CODUND'))) ) {
            # exibir mensagem flash de restrição...
            $msg = "Acesso restrito a secretaria do Serviço de Graduação e alunos ativos de Graduação desta unidade."; 
            # dd('ACESSO RESTRITO');
            session()->flash('alert-danger', $msg);
            return redirect('/');
        }    
        
		# se o usuário local NÃO EXISTE, cadastra
        if (is_null($user)) {
            $user = new User;
            $user->id = $userSenhaUnica->id;
            $user->email = $userSenhaUnica->email;
            $user->name = $userSenhaUnica->name;
            $user->save();
        } else {
            # se o usuário EXISTE local
            # atualiza os dados
            $user->id = $userSenhaUnica->id;
            $user->email = $userSenhaUnica->email;
            $user->name = $userSenhaUnica->name;
            $user->save(); 
        }
        
		# faz login
        Auth::login($user, true);
        
		# redireciona
        return redirect('/');  
    }
    
	public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
