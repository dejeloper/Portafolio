<?php
namespace App\Controllers;

use App\Models\User; 
use Respect\Validation\Validator as validator;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController {
    
    public function getLoginAction($request) {  
        return $this->renderHTML('login.twig');
    }

    public function authAction($request) {        
        $responseMensaje ="";
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();

            $user = User::where('email', $postData['emailUser'])->first();
            if ($user){
                if (password_verify($postData['passUser'], $user->password)) {
                    $_SESSION['userId'] = $user->id;
                    return new RedirectResponse('/Portafolio/admin');
                } else {
                    $responseMensaje = "The information is incorrect. Try again.";
                } 
            } else {
                $responseMensaje = "The information is incorrect. Try again.";
            }
        }

        return $this->renderHTML('login.twig', [
            "responseMensaje" => $responseMensaje
        ]);
    }

    public function getLogoutAction($request) { 
        unset($_SESSION['userId']);
        return new RedirectResponse('/Portafolio/login');
    }
}