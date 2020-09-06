<?php
namespace App\Controllers;

use App\Models\{Job, Project}; 

class AdminController extends BaseController {
    
  public function getIndex($request) { 
      return $this->renderHTML('admin.twig');
  }


}