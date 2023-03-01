<?php

namespace Steodec\SteoFrameWork\Controllers;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;
use SalernoLabs\PHPToXML\Convert;
use Steodec\SteoFrameWork\Orm\AbstractEntities;
use Steodec\SteoFrameWork\Routers\Attributes\RouteAttribute;
use Steodec\SteoFrameWork\Routers\Main;
use Steodec\SteoFrameWork\Routers\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

abstract class AbstractControllers
{
    /**
     * @param array|object $object
     * @param HttpStatus $httpStatusCode
     *
     * @return void
     * @throws Exception
     */
    public function renderXML(array|object $object, HttpStatus $httpStatusCode = HttpStatus::OK): void
    {
        $converter = new Convert();
        header('Content-type: application/xml');
        http_response_code($httpStatusCode->value);
        $xml = $converter->setObjectData($object);
        echo $xml->convert();
    }

    /**
     * @param array|object $object
     * @param HttpStatus $httpStatusCode
     *
     * @return void
     */
    public function renderJSON(array|object $object, HttpStatus $httpStatusCode = HttpStatus::OK): void
    {
        header('Content-type: application/json');
        http_response_code($httpStatusCode->value);
        echo json_encode($object);
    }

    private function CallingFunctionName()
    {

        $ex = new Exception();
        $trace = $ex->getTrace();
        return $trace[2]['function'];
    }

    private function CallingClassName()
    {

        $ex = get_class($this);
        $trace = explode(DIRECTORY_SEPARATOR, $ex);
        return $trace[count($trace) - 1];
    }

    /**
     * @param string|null $templatePath
     * @param array|null $params
     * @return void
     */
    public function renderHTML(?string $templatePath, ?array $params = []): void
    {
        $params['titre'] = $params['titre'] ?? "";
        $params['session'] = $_SESSION;
        $loader = new FilesystemLoader(Constants::TEMPLATE_PATH);
        $twig = new Environment($loader, ['debug' => $_ENV['debug']]);
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new StringExtension());
        $twig->addFunction(new TwigFunction('getRoute', fn(?string $path = NULL, ?array $params = NULL) => $this->returnRoute($path, $params)));
        $twig->addGlobal('_host', $_SERVER['SERVER_NAME']);
        $twig->addGlobal('env', $_ENV);
        $twig->addGlobal('_get', $_GET);
        $twig->addGlobal('_post', $_POST);
        $twig->addFilter(new TwigFilter('json_decode', fn(string $a) => json_decode($a)));
        try {
            if (empty($templatePath)) {
                echo $twig->render($this->CallingClassName() . "/" . $this->CallingFunctionName() . ".twig", $params);
            } else {
                echo $twig->render($templatePath, $params);
            }
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            var_dump($e);
        }
    }

    /**
     * @param string $newPath
     *
     * @return void
     * @throws ReflectionException
     */
    #[NoReturn] protected function redirect(string $newPath): void
    {
        $route = $this->returnRoute($newPath);
        header('Location: ' . $route);
        exit;
    }

    /**
     * @param string|null $routeName
     * @param array|null $params
     *
     * @return array|string
     * @throws ReflectionException
     */
    public function returnRoute(?string $routeName = NULL, ?array $params = []): array|string
    {
        $routes = (new Main('App\\controllers'))->getRoute();
        if (is_null($routeName)):
            return $routes;
        else:
            $route = array_filter($routes, fn(RouteAttribute $el) => $el->getName() == $routeName);
            $path = array_values($route)[0]->getPath();
            if (str_contains($path, ":")):
                $re = '/:.+/m';
                preg_match_all($re, $path, $matches);
                foreach ($matches[0] as $match):
                    $path = str_replace($match, $params[substr($match, 1)], $path);
                endforeach;
            endif;
            return $path;
        endif;
    }

    /**
     * @param AbstractEntities $entity
     * @param array $form
     * @return AbstractEntities
     */
    public function ArrayToEntity(AbstractEntities $entity, array $form): AbstractEntities
    {
        foreach ($form as $key => $value):
            $entity->{$key} = $value;
        endforeach;

        return $entity;
    }
}