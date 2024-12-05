<?php

namespace App\Http;

class Request {

  private $httpMethod;
  private $uri;
  private $queryParams = [];
  private $postvars = [];
  private $headers = [];

  public function __construct() {
    $this->queryParams = $_GET ?? [];
    $this->postvars = $_POST ?? [];
    $this->headers = getallheaders();
    $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? [];
    $this->uri = $_SERVER['REQUEST_URI'] ?? [];    
  }

  public function getHttp() {
    return $this->httpMethod;
  }
  public function getUri() {
    return $this->uri;
  }
  public function getHeaders() {
    return $this->headers;
  }
  public function getQueryParams() {
    return $this->queryParams;
  }
  public function getPostVars() {
    return $this->postvars;
  }
}