<?php
namespace App\Controllers;

use App\Models\Job; 
use Respect\Validation\Validator as validator;

class JobsController extends BaseController {
    public function getAddJobAction($request)
    {
        // var_dump($request->getMethod());
        // var_dump($request->getBody());
        // var_dump($request->getParsedBody());

        $responseMensaje ="";
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();

            $jobValidator = validator::key('titleJob', validator::stringType()->notEmpty())
                ->key('descriptionJob', validator::stringType()->notEmpty());

            try {
                $jobValidator->assert($postData); 
                $postData = $request->getParsedBody();
                
                //$_FILES
                $files = $request->getUploadedFiles();
                $logo = $files['imageJob'];
                $routeLogo = '';

                if ($logo->getError() == UPLOAD_ERR_OK){
                    $fileName = $logo->getClientFilename();
                    $logo->moveTo("uploads/jobs/$fileName");
                    $routeLogo = "uploads/jobs/$fileName";
                }

                $job = new Job();
                $job->title = $postData['titleJob'];
                $job->description = $postData['descriptionJob'];
                $job->image = $routeLogo;
                $job->save();
                
                $responseMensaje ="Saved";

            } catch (\Exception $ex) { 
                $responseMensaje = $ex->getMessage();
            }
        }

        //include '../views/addJob.php';
        return $this->renderHTML('addJob.twig', [
            "responseMensaje" => $responseMensaje
        ]);
    }
}