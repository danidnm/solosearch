<?php
 
 namespace SoloSearch\Core\Http;
 
 use Psr\Http\Message\ResponseInterface;
 
 class ResponseFactory
 {
     public function json(ResponseInterface $response, array $data, int $status = 200): ResponseInterface
     {
         $response->getBody()->write(json_encode($data));
         return $response
             ->withHeader('Content-Type', 'application/json')
             ->withStatus($status);
     }
 
     public function html(ResponseInterface $response, string $html, int $status = 200): ResponseInterface
     {
         $response->getBody()->write($html);
         return $response
             ->withHeader('Content-Type', 'text/html')
             ->withStatus($status);
     }
 }
