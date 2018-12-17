<?php

namespace App\controllers;

use JsonSchema\Validator;
use ProjetoMvc\render\Action;
use App\model\Usuario;
use App\model\Retorno;
use App\Validator\Data_Validator;

if (! defined('ABSPATH')){
    header("Location: /");
    exit();
}

class HomeController extends Action
{
    public function __construct()
    {
        parent::__construct();
        /**
         * caminho com o arquivo do layout padrão que todasas paginas dessa controller poderá usar
         */
        $this->layoutPadrao = PATH_VIEWS."shared/layoutPadrao";
    }

    /**
     * Chama a view de tela principal.
     */
    public function pageIndex()
    {
        if (isset($_SESSION['usuario-codigo'])) :
            header('Location: '. URL .'/Area-Administrativa/Dashboard/');
        else :
            $this->dados->title = "Página de login";
            header('Location: '. URL .'login');
        endif;
        exit();
    }

    /**
     * chama a view dashboard, passando o titulo da página
     * @return void
     */
    public function pageDashboard()
    {
        $this->layoutPadrao = PATH_VIEWS."shared/admin/layoutPadraoAdmin";
        $this->dados->title = "Dashboard";
        $this->css = "partial-dashboard";
        $this->render('dashboard');
    }

    /**
     * chama a view de pagina nao encontrada
     */
    public function pageError404()
    {
            $this->dados->title = "Página Não Encontrada";
            http_response_code(404);
            $this->render('error404');
    }

    public function pageRecuperarSenha()
    {
            $this->dados->title = "Recuperar Senha";
            $this->css = "partial-login";
            $this->render('recuperar-senha');
    }

    public function pageLogin() {

        if (!empty($_SESSION["_usuariocodigo"])) {
            header('Location: /Area-Administrativa/Dashboard/');
            exit();
        } else {
            $this->dados->title = "Página de login";
            $this->render('login.php', false);
        }

    }

    public function login()
    {
        $usu = new Usuario();
        $retorno = new Retorno();
        $validate = new Data_Validator();

        if (filter_has_var(INPUT_POST, 'btnLogar')) :
            $login = trim(filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS));
            $senha = trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS));
            $token = trim(filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS));

            $login = $senha = $token = "";

            $validate
                ->set("login", $login)->is_required()
                ->set("senha", $senha)->is_required()
                ->set("token", $token)->is_required();

            if ($validate->validate()) {

                /*if (!empty($usu->login($usuario, $senha, $token))) :
                    $retorno->setRetorno(0,1,"OK");
                    echo json_encode($retorno->toArray());
                else :
                    $retorno->setRetorno($usu->getRetorno()->getCodigo(),$usu->getRetorno()->getTipo(),$usu->getRetorno()->getMensagem());
                    echo json_encode($retorno->toArray());
                endif;*/

            } else {

                var_dump($validate->get_errors());

            }

            /*if (filter_var($login, FILTER_VALIDATE_EMAIL))
                $usu->setEmail($login);
            else
                $usu->setLogin($login);*/

            //validando entradas


        else :
            header('Location: '. URL .'login');
            exit();
        endif;
    }

    public function logout()
    {
        if (isset($_SESSION['usuario-codigo'])) :
            unset($_SESSION['usuario-codigo'], $_SESSION['usuario-nome'], $_SESSION['usuario-status'], $_SESSION['usuario-token']);
        endif;

        header('Location: /Area-Administrativa/Fazer-Login/');
        exit();
    }
}
