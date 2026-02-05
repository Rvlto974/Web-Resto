<?php
class HomeController extends Controller {
    
    public function index() {
        echo "<h1>Bienvenue sur Vite & Gourmand !</h1>";
        echo "<p>Le système MVC fonctionne parfaitement !</p>";
        echo "<p>Version PHP : " . phpversion() . "</p>";
    }
}