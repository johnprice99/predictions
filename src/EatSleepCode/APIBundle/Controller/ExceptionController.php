<?php

namespace EatSleepCode\APIBundle\Controller;

use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseExceptionController;

class ExceptionController extends BaseExceptionController {

    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null) {

        // IF an API URL, show the result as JSON - otherwise show HTML format
        $format = (strncmp($request->getPathInfo(), '/api/', strlen('/api/')) == 0) ? 'json' : 'html';
        $request->setRequestFormat($format);

        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        $showException = $request->attributes->get('showException', $this->debug); // As opposed to an additional parameter, this maintains BC

        $code = $exception->getStatusCode();

        return new Response($this->twig->render(
            $this->findTemplate($request, $request->getRequestFormat(), $code, $showException),
            array(
                'status_code' => $code,
                'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception' => $exception,
                'logger' => $logger,
                'currentContent' => $currentContent,
            )
        ));
    }

}
