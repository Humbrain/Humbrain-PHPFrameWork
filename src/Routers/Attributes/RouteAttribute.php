<?php

namespace Steodec\SteoFrameWork\Routers\Attributes;

use Attribute;

abstract class RouteAttribute {
    private string|bool $_path;
    private string      $_callable;
    private string      $_method;
    private string|bool $_name;
    private string|bool $_isGranted;

    public function __construct(HTTPMethods $_method = HTTPMethods::GET, string|bool $_path = FALSE, string|bool $_name = FALSE, string|bool $_isGranted = FALSE) {
        $this->_path      = $_path;
        $this->_isGranted = $_isGranted;
        $this->_method    = $_method->value;
        $this->_name      = $_name;
    }

    /**
     * @return bool|string
     */
    public function getPath(): bool|string {
        return $this->_path;
    }

    /**
     * @param bool|string $path
     */
    public function setPath(bool|string $path): void {
        $this->_path = $path;
    }

    /**
     * @return string
     */
    public function getCallable(): string {
        return $this->_callable;
    }

    /**
     * @param string $callable
     */
    public function setCallable(string $callable): void {
        $this->_callable = $callable;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->_method;
    }

    /**
     * @param HTTPMethods $method
     */
    public function setMethod(HTTPMethods $method): void {
        $this->_method = $method->value;
    }


    /**
     * @return bool|string
     */
    public function getName(): bool|string {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->_name = $name;
    }

    /**
     * @return bool|string
     */
    public function getIsGranted(): bool|string {
        return $this->_isGranted;
    }

    /**
     * @param string $isGranted
     */
    public function setIsGranted(string $isGranted): void {
        $this->_isGranted = $isGranted;
    }


}