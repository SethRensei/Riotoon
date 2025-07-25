<?php

namespace Riotoon\Router;

use AltoRouter;

class Router {
    private string $path;
    private AltoRouter $router;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->router = new AltoRouter();
    }

    /**
     * Summary of route
     * @param string $uri The URL pattern to match
     * @param string $view The view associeted to route
     * @param string $method Route call method
     * @param mixed $name The route's name
     * @return Router
     */
    public function route(string $uri, string $view, string $method = "GET", ?string $name = null): self
    {
        $this->router->map($method, $uri, $view, $name);
        return $this;
    }

    public function run(): self
    {
        $match = $this->router->match();
        $router = $this;

        if(!$match)
            die("Router doesn't work");

        $view = $match['target'];
        $params = $match['params'];

        // 1) locate the AJAX call (fetch / XHR)
        $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            if ($is_ajax) {
                // if AJAX, we just return the view (HTML fragment)
                header('Content-Type: text/html; charset=UTF-8');
                require $this->path . DIRECTORY_SEPARATOR . $view . ".php";
                return $this;
            }

            // otherwise, full page
            ob_start();
            require $this->path . DIRECTORY_SEPARATOR . $view . ".php";
            $pg_content = ob_get_clean();
            require $this->path . DIRECTORY_SEPARATOR . 'base.php';
        } catch (\Exception $e) {
            dd($e);
            // header('Location:' . $router->url('error'));
            // exit();
        }

        return $this;
    }

    /**
     * Generate a URL for a named route
     * @param string $name Route's name
     * @param array $params Optional. Associative array of route parameters
     * @return string The generated URL
     */
    public function url(string $name, array $params = []): string
    {
            $base_url = $_ENV['APP_URL'] ?? 'http://localhost';
            $path = $this->router->generate($name, $params);
            return rtrim($base_url, '/') . $path;
    }
}