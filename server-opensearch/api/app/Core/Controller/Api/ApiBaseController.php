<?php
 
 namespace SoloSearch\Core\Controller\Api;
 
 use Psr\Http\Message\ResponseInterface;
 use SoloSearch\Core\Controller\BaseController;
 
 class ApiBaseController extends BaseController
 {
     protected function jsonResponse(ResponseInterface $response, array $data, int $status = 200): ResponseInterface
    {
        return $this->responseFactory->json($response, $data, $status);
    }
 }
