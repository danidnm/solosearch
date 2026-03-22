<?php
 
 namespace SoloSearch\Core\Controller\Web;
 
 use Psr\Http\Message\ResponseInterface;
 use SoloSearch\Core\Controller\BaseController;
 
 class WebBaseController extends BaseController
 {
     protected function htmlResponse(ResponseInterface $response, string $html, int $status = 200): ResponseInterface
     {
         $response->getBody()->write($html);
         return $response
             ->withHeader('Content-Type', 'text/html')
             ->withStatus($status);
     }
 }
