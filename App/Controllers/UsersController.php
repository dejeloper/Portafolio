<?php
namespace App\Controllers;

use App\Models\User; 
use Respect\Validation\Validator as validator;

class UsersController extends BaseController {
    public function getAddUserAction($request)
    {
        $responseMensaje ="";
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();

            $projectValidator = validator::key('firstNameUser', validator::stringType()->notEmpty())
            ->key('lastNameUser', validator::stringType()->notEmpty())
            ->key('emailUser', validator::email())
            ->key('passUser', validator::stringType()->length(8,20)->notEmpty());

            try {
                $projectValidator->assert($postData); 
                $postData = $request->getParsedBody(); 

                //$_FILES
                $files = $request->getUploadedFiles();
                $logo = $files['imageUser'];
                $routeLogo = '';

                if ($logo->getError() == UPLOAD_ERR_OK){
                    $fileName = $logo->getClientFilename();
                    $logo->moveTo("uploads/users/$fileName");
                    $routeLogo = "uploads/users/$fileName";
                }
                
                $user = new User();
                $user->first_name = $postData['firstNameUser'];
                $user->last_name = $postData['lastNameUser'];
                $user->email = $postData['emailUser'];
                $user->password = password_hash($postData['passUser'], PASSWORD_DEFAULT);
                $user->image = $routeLogo;
                $user->save(); 

                $responseMensaje ="Saved";
                //var_dump($user);
                //$responseMensaje = "first_name: " . $postData['firstNameUser'] . " - last_name: " . $postData['lastNameUser'] . " - email: " . $postData['emailUser'] . " - password: " . $postData['passUser'] . " - Logo: " .  $routeLogo;

            } catch (\Exception $ex) {
                $responseMensaje = $ex->getMessage();
            }
        }

        return $this->renderHTML('AddUser.twig', [
            "responseMensaje" => $responseMensaje
        ]);
    }
}