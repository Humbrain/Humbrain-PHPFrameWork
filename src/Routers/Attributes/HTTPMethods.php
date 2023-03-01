<?php

namespace Steodec\SteoFrameWork\Routers\Attributes;

enum HTTPMethods: string {
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}