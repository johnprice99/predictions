<?php

namespace EatSleepCode\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class APIController extends Controller {

    protected $entityManager;

    public function __construct(ContainerInterface $container, $entityManager) {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    protected function _JSONResponse($json, $responseCode = 200) {
        $response = new JsonResponse($json);
        $response->setStatusCode($responseCode);
        return $response;
    }


    protected function _badRequest($message = "Bad request") {
        return $this->_JSONResponse(array('message' => $message), 400);
    }
    protected function _accessDenied($message = "Access denied") {
        return $this->_JSONResponse(array('message' => $message), 403);
    }
    protected function _notFound($message = "Not found") {
        return $this->_JSONResponse(array('message' => $message), 404);
    }
    protected function _internalServer($message = "Internal server error") {
        return $this->_JSONResponse(array('message' => $message), 500);
    }

}
