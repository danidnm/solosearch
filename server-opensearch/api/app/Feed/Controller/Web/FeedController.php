<?php
 
 namespace SoloSearch\Feed\Controller\Web;
 
 use Psr\Http\Message\ResponseInterface;
 use Psr\Http\Message\ServerRequestInterface;
 
 class FeedController extends \SoloSearch\Core\Controller\BaseController
 {
     public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
     {
         $response->getBody()->write('<h1>Feed Browser View</h1><p>Esta es la vista para el navegador.</p>');
         return $response->withHeader('Content-Type', 'text/html');
     }
 }
