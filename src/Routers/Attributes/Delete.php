<?php

namespace Steodec\SteoFrameWork\Routers\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Delete extends RouteAttribute {
    public function __construct(bool|string $_path = FALSE, bool|string $_name = FALSE, bool|string $_isGranted = FALSE) { parent::__construct(HTTPMethods::DELETE, $_path, $_name, $_isGranted); }
}