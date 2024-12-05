<?php

namespace App\Http;

use \Closure;
use Exception;
use ReflectionFunction;

class Router {

  private $url = '';
  private $prefix = '';
  private $routes = [];
  private $request;

  public function __construct($url) {
    $this->request  = new Request(); 
    $this->url      = $url;
    $this->setPrefix();
  }

  private function setPrefix() {
    $parseUrl = parse_url($this->url);
    $this->prefix = $parseUrl['path'] ?? '';
  }

  private function addRoute($method, $route, $params = []) {
    foreach ($params as $key => $value) {
      if($value instanceof Closure) {
        $params['controller'] = $value; // creates key 'controller'
        unset($params[$key]); //removes numerical auto key
        continue;
      }
    }

    $params['variables'] = [];

    $variablesPattern = '/{(.*?)}/';
    if(preg_match_all($variablesPattern, $route, $matches)) {
      $route = preg_replace($variablesPattern, '(.*?)', $route);
      $params['variables'] = $matches[1];
    } 

    $validationPattern = '/^'.str_replace('/', '\/', $route).'$/';
    
    $this->routes[$validationPattern][$method] = $params;
  }

  // GET
  public function get($route, $params = []) {
    return $this->addRoute('GET', $route, $params);
  }
  // POST
  public function post($route, $params = []) {
    return $this->addRoute('POST', $route, $params);
  }
  // PUT
  public function put($route, $params = []) {
    return $this->addRoute('PUT', $route, $params);
  }
  // DELETE
  public function delete($route, $params = []) {
    return $this->addRoute('DELETE', $route, $params);
  }

  private function getUri() {
    $uri = $this->request->getUri();

    $splitUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
    return end($splitUri);
  }

  private function getRoute() {
    $uri = $this->getUri();

    $httpMethod = $this->request->getHttp();
    
    // validation
    foreach ($this->routes as $validationPattern => $methods) {
      // if expected pattern
      if(preg_match($validationPattern, $uri, $matches)) {
        if (isset($methods[$httpMethod])) {
          // matches:
          // [0] => /page/1  <- remove pos. 0
          // [1] => 1
          unset($matches[0]);

          // treated vars
          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;

          return $methods[$httpMethod];
        }
        throw new Exception("Method not allowed", 405);
      }
    }
    throw new Exception("URL not found", 404);
  }

  public function run() {
    try {
      $route = $this->getRoute();
      
      // echo '<pre>';
      // var_dump($route);
      // echo '<pre>';
      // exit;

      //Verify controller
      if(!isset($route['controller'])){
        throw new Exception("URL can not be processed!", 500);
      }
      // returns the Response
      $args = [];
      $reflection = new ReflectionFunction($route['controller']);
      foreach ($reflection->getParameters() as $parameter) {
        $name = $parameter->getName();
        $args[$name] = $route['variables'][$name] ?? '';
      }

      return call_user_func_array($route['controller'], $args);      
    } catch (Exception $e) {
      return new Response($e->getCode(),$e->getMessage());
    }
  }

}