<?php
 
 namespace SoloSearch\Feed\Controller\Web;
 
 use Psr\Http\Message\ResponseInterface;
 use Psr\Http\Message\ServerRequestInterface;
 
 use SoloSearch\Core\Controller\Web\WebBaseController;
 
 /**
  * Class FeedController
  * Handles the web endpoints for the Feed module.
  */
 class FeedController extends WebBaseController
 {
     /**
      * Renders the feed index page using the 'feed_index' layout handle.
      */
     public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
     {
         return $this->render($response, 'feed_index');
     }
 }
