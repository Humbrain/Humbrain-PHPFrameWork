<?php

namespace Steodec\SteoFrameWork\Routers;

use Exception;

class RouterException extends Exception
{

    /**
     * RouterException constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}