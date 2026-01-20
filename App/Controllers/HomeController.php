<?php

namespace App\Controllers;

use Controller;

class HomeController extends Controller
{
    public function home()
    {
        
        $this->renderWithLayout('home/index'); 
    }
}
