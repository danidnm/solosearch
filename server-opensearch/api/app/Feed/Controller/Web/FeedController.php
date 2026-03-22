<?php
 
 namespace SoloSearch\Feed\Controller\Web;
 
 use Psr\Http\Message\ResponseInterface;
 use Psr\Http\Message\ServerRequestInterface;
 
 use SoloSearch\Core\Controller\Web\WebBaseController;
 
 class FeedController extends WebBaseController
 {
     public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
     {
         return $this->htmlResponse($response, '<h1>Feed Browser View</h1><p>Esta es la vista para el navegador.</p>');
     }
 }
