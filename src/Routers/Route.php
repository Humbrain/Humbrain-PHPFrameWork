<?php

namespace Steodec\SteoFrameWork\Routers;

class Route {
    /**
     * @var string
     */
    private string $_path;
    /**
     * @var callable
     */
    private mixed $_callable;
    /**
     * @var array
     */
    private array $_matches = [];
    /**
     * @var array
     */
    private array $_params = [];

    /**
     * @param string $_path
     * @param callable $_callable
     */
    public function __construct(string $_path, string $_callable) {
        $this->_path     = trim($_path, '/');
        $this->_callable = $_callable;
    }

    /**
     * @return mixed
     */
    public function call(): mixed {
        if (is_string($this->_callable)):
            $params     = explode('#', $this->_callable);
            $controller = $params[0];
            $controller = new $controller();
            return call_user_func_array([$controller, $params[1]], $this->_matches);
        else:
            return call_user_func_array($this->_callable, $this->_matches);
        endif;
    }

    /**
     * @param $url
     * @return bool
     */
    public function match($url): bool {
        $url   = trim($url, '/');
        $path  = preg_replace('#:([\w]+)#', '([^/]+)', $this->_path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches))
            return FALSE;
        array_shift($matches);
        $this->_matches = $matches;
        return TRUE;
    }


}