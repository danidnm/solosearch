<?php
 
 namespace SoloSearch\Feed\Controller\Web;
 
 use Psr\Http\Message\ResponseInterface;
 use Psr\Http\Message\ServerRequestInterface;
 
 use SoloSearch\Core\Controller\Web\WebBaseController;
 
 class FeedController extends WebBaseController
 {
      public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
     {
         return $this->render($response, 'Feed/view/feed.twig', [
             'message' => '¡Hola desde el sistema de plantillas Twig!'
         ]);
     }
 }
