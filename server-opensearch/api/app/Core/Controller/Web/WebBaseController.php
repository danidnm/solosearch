<?php
 
 namespace SoloSearch\Core\Controller\Web;
 
 use Psr\Http\Message\ResponseInterface;
 use SoloSearch\Core\Controller\BaseController;
 use SoloSearch\Core\Http\ResponseFactory;
 use SoloSearch\Core\Model\Layout;
 
 /**
 * Class WebBaseController
 * Base controller for all web endpoints. It injects the Layout model
 * so child controllers can simply call $this->render('handle_name').
 */
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
