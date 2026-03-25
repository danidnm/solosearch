<?php
 
 namespace SoloSearch\Core\Controller\Web;
 
 use Psr\Http\Message\ResponseInterface;
 use SoloSearch\Core\Controller\BaseController;
 use SoloSearch\Core\Http\ResponseFactory;
 use Slim\Views\Twig;
 
 class WebBaseController extends BaseController
 {
     protected Twig $view;
     
     public function __construct(
         ResponseFactory $responseFactory,
         Twig $view
     ) {
         parent::__construct($responseFactory);
         $this->view = $view;
     }
     
     /**
      * Render Twig template
      * 
      * @param ResponseInterface $response
      * @param string $template
      * @param array $data
      * @return ResponseInterface
      */
     protected function render(ResponseInterface $response, string $template = 'default.twig', array $data = []): ResponseInterface
     {
         return $this->view->render($response, $template, $data);
     }
     
     protected function htmlResponse(ResponseInterface $response, string $html, int $status = 200): ResponseInterface
    {
        return $this->responseFactory->html($response, $html, $status);
    }
 }
