<?php

namespace Steodec\SteoFrameWork\ORM;

use PDO;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Base of entity/models
 * @author steodec
 * @version 0.0.1
 */
abstract class Entity extends ORM
{
    protected string $TABLE_NAME;
    /**
     * @var int
     */
    public int $id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(mixed $id): void
    {
        $this->id = $id;
    }


}