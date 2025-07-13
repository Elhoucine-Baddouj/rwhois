<?php

namespace Controllers;

class BaseController
{
    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            ob_start();
            include $viewFile;
            $content = ob_get_clean();
            
            // Inclure le layout principal
            include VIEWS_PATH . '/layouts/main.php';
        } else {
            echo "View not found: $view";
        }
    }
    
    protected function renderPartial($view, $data = [])
    {
        extract($data);
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "View not found: $view";
        }
    }
    
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
    
    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    protected function getPostData()
    {
        return $_POST;
    }
    
    protected function getQueryParam($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
} 