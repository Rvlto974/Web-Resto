<?php
class Router {
    private $defaultController = 'HomeController';
    private $defaultMethod = 'index';
    private $params = [];

    public function route() {
        $url = $this->getUrl();
        $urlArray = explode('/', $url);
        
        if (empty($urlArray[0])) {
            $controllerName = $this->defaultController;
        } else {
            $controllerName = ucfirst($urlArray[0]) . 'Controller';
        }
        
        $controllerFile = __DIR__ . '/../../Controllers/' . $controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();
        } else {
            $this->notFound();
            return;
        }
        
        if (isset($urlArray[1]) && !empty($urlArray[1])) {
            $method = $urlArray[1];
            if (method_exists($controller, $method)) {
                // OK
            } else {
                $this->notFound();
                return;
            }
        } else {
            $method = $this->defaultMethod;
        }
        
        $this->params = array_slice($urlArray, 2);
        call_user_func_array([$controller, $method], $this->params);
    }

    private function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return $url;
        }
        return '';
    }

    private function notFound() {
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        echo "<p>La page que vous cherchez n'existe pas.</p>";
        echo "<a href='/'>Retour à l'accueil</a>";
    }

    public static function url($path = '') {
        return '/' . ltrim($path, '/');
    }
}