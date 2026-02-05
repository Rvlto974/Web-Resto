<?php
class Controller {
    
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../../Views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once __DIR__ . '/../../Views/layouts/header.php';
            require_once $viewPath;
            require_once __DIR__ . '/../../Views/layouts/footer.php';
        } else {
            die("La vue {$view} n'existe pas.");
        }
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}