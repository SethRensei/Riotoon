<?php

namespace Riotoon\Controller;

use AltoRouter;

/**
 * Class Router
 * A simple router class for routing HTTP requests to controller actions and views.
*/
class Router
{
    private string $view_path;

    private AltoRouter $router;

    /**
     * Constructor
     * Initializes the Router instance
     * @param string $view_path The base path to the views directory
     */
    public function __construct(string $view_path)
    {
        $this->view_path = $view_path;
        $this->router = new AltoRouter();
    }

    /**
     * Register a GET route
     * @param string $url The URL pattern to match.
     * @param string $view The view associated with the route.
     * @param string|null $name Optional. The name of the route.
     * @return \Riotoon\Controller\Router The Router instance for method chaining.
     */
    public function get(string $url, string $view, string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    /**
     * Register a POST route
     * @param string $url The URL pattern to match.
     * @param string $view The view associated with the route.
     * @param string|null $name Optional. The name of the route.
     * @return \Riotoon\Controller\Router The Router instance for method chaining.
     */
    public function post(string $url, string $view, string $name = null): self
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    /**
     * Register a route for both GET and POST methods.
     * @param string $url The URL pattern to match.
     * @param string $view The view associated with the route.
     * @param string $name Optional. The name of the route.
     * @return \Riotoon\Controller\Router The Router instance for method chaining.
     */
    public function fallOver(string $url, string $view, string $name = null): self
    {
        $this->router->map('GET|POST', $url, $view, $name);
        return $this;
    }

    /**
     * Execute the router and render the associated view.
     * @return \Riotoon\Controller\Router the Router instance for method chaining.
     */
    public function run(): self
    {
        $match = $this->router->match();
        $router = $this;
        // $view = 'index'; // Vue par défaut, permet également de rester sur la page d'accueil pour des url inexistant
        if(is_array($match)) {
            $view = $match['target'];
            $params = $match['params'];
        } else
            $view = 'error404';

        $layout = 'base';
        try {
            ob_start();
            require $this->view_path . DIRECTORY_SEPARATOR . $view . '.php';
            $pg_content = ob_get_clean();
            require $this->view_path . DIRECTORY_SEPARATOR . $layout . '.php';
        } catch (Security $security) {
            $_SESSION['error401'] = $security->getMessage();
            header('Location:' . $router->url('error'));
        }
        catch (\Exception $e) {
            $_SESSION['error404'] = $e->getMessage();
            header('Location:' . $router->url('error'));
        } 

        return $this;
    }

    /**
     * Generate a URL for a named route.
     * @param string $name The name of the route.
     * @param array $params Optional. Associative array of route parameters
     * @return string The generated URL.
     */
    public function url(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}