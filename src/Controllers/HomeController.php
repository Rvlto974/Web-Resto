<?php
class HomeController extends Controller {
    
    public function index() {
        $data = [
            'title' => 'Accueil'
        ];
        
        $this->view('home/index', $data);
    }
}