<?php
 
 namespace SoloSearch\Core\Controller\Api;
 
 use Psr\Http\Message\ResponseInterface;
 use SoloSearch\Core\Controller\BaseController;
 
 class ApiBaseController extends BaseController
 {
     protected function jsonResponse(ResponseInterface $response, array $data, int $status = 200): ResponseInterface
     {
         $response->getBody()->write(json_encode($data));
         return $response
             ->withHeader('Content-Type', 'application/json')
             ->withStatus($status);
     }
 }
