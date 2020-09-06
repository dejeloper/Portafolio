<?php
namespace App\Controllers;

use App\Models\Project; 
use Respect\Validation\Validator as validator;

class ProjectsController extends BaseController {
    public function getAddProjectAction($request)
    { 
        $responseMensaje ="";
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();

            $projectValidator = validator::key('titleProject', validator::stringType()->notEmpty())
                ->key('descriptionProject', validator::stringType()->notEmpty());

            try {
                $projectValidator->assert($postData); 
                $postData = $request->getParsedBody(); 

                //$_FILES
                $files = $request->getUploadedFiles();
                $logo = $files['imageProject'];
                $routeLogo = '';

                if ($logo->getError() == UPLOAD_ERR_OK){
                    $fileName = $logo->getClientFilename();
                    $logo->moveTo("uploads/projects/$fileName");
                    $routeLogo = "uploads/projects/$fileName";
                }

                $project = new Project();
                $project->title = $postData['titleProject'];
                $project->description = $postData['descriptionProject'];
                $project->image = $routeLogo;
                //$project->save();
                var_dump($project);
                $responseMensaje ="Saved";

            } catch (\Exception $ex) {
                $responseMensaje = $ex->getMessage();
            }
        }
 
        // include '../views/addProject.php';
        return $this->renderHTML('addProject.twig', [
            "responseMensaje" => $responseMensaje
        ]);
    }
}