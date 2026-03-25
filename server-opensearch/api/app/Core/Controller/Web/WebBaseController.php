<?php
 
 namespace SoloSearch\Core\Controller\Web;
 
 use Psr\Http\Message\ResponseInterface;
 use SoloSearch\Core\Controller\BaseController;
 use SoloSearch\Core\Http\ResponseFactory;
 use SoloSearch\Core\Model\Layout;
 
 class WebBaseController extends BaseController
 {
     protected Layout $layout;
     
     public function __construct(
         ResponseFactory $responseFactory,
         Layout $layout
     ) {
         parent::__construct($responseFactory);
         $this->layout = $layout;
     }
     
     protected function render(ResponseInterface $response, string $handle = 'default', int $status = 200): ResponseInterface
     {
         $html = $this->layout->render($handle);
         return $this->htmlResponse($response, $html, $status);
     }
     
     protected function htmlResponse(ResponseInterface $response, string $html, int $status = 200): ResponseInterface
    {
        return $this->responseFactory->html($response, $html, $status);
    }
 }
