<?php
namespace App\Controllers;

use App\Models\{Job, Project}; 

class IndexController extends BaseController {
    public function indexAction()
    { 
        $jobs = Job::all();
        for($i=0;$i<count($jobs);$i++){
            $jobs[$i]->durationJob = $jobs[$i]->getDurationAsString();
            if ($jobs[$i]->image == NULL || $jobs[$i]->image == '') { 
                $jobs[$i]->image = 'https://ui-avatars.com/api/?name=John+Doe&size=255';
            } 
        }

        $projects = Project::all();
        for($i=0;$i<count($projects);$i++){
            $projects[$i]->durationProject = $projects[$i]->getDurationAsString();
            if ($projects[$i]->image == NULL || $projects[$i]->image == '') { 
                $projects[$i]->image = 'https://ui-avatars.com/api/?name=John+Doe&size=255';
            } 
        }

        $name = 'Jhonatan Guerrero'; 
        $skill = 'PHP Developer';
        $email = 'jhonatanguerrero@outlook.com';
        $photo = 'https://pbs.twimg.com/profile_images/1273859910732742657/INw2zGhx_400x400.jpg';
        $phone = '3138227185';
        $linkedin = "jaguemo24";
        $twitter = "jaguemo24";

        // include '../views/index.php';
        return $this->renderHTML('index.twig', [
            'name' => $name,
            'skill' => $skill,
            'email' => $email,
            'photo' => $photo,
            'phone' => $phone,
            'linkedin' => $linkedin,
            'twitter' => $twitter,
            'jobs' => $jobs,
            'projects' => $projects,
        ]);
    }

    public function page404Action()
    {
        return $this->renderHTML('page404.twig');
    }
}