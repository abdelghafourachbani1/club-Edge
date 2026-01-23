<?php

namespace App\Controllers;

use Controller;

class AdminController extends Controller
{
    public function index()
    {
        
        echo "Admin Dashboard";
    }

    public function example()
    {
        session_destroy();
        $data = [
            'title' => 'Admin Example Page',
            'message' => 'This is an example page in the Admin panel.'
        ];
        $this->renderWithLayout('admin/example', $data);
    }
    
   
}
