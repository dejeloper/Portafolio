<?php

namespace App\Models; 

class BaseElement {
    public $title;
    public $description;
    public $image;
    public $visible = true;
    public $months;

    public function getDurationAsString() {
        $value  = '';
        if ($this->months > 0){
            if ($this->months < 12) {
                $value .= "$this->months month" . ($this->months>1? 's' : '');
            } else {
                $year = $this->months / 12;
                $residuo = $this->months % 12;
                if ($residuo == 0) {
                    $value .= "$year year" . ($year>1? 's' : '');
                } else if ($residuo > 0){
                    $year = floor($year); 
                    $value .= "$year year" . ($year>1? 's' : '') . " y $residuo month" . ($residuo>1? 's' : ''); 
                } else {
                    $value .= "None";
                }   
            }
        } else {
            $value .= "None";
        }
        
        return $value;
    }
}