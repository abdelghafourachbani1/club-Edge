<?php

namespace App\Controllers;

use Controller;
use App\Repositories\EventRepository;
class ClubController extends Controller{

    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->eventRepository = new EventRepository();
    
    }


}