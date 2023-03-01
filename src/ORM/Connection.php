<?php

namespace Steodec\SteoFrameWork\Orm;

use PDO;
use PDOException;

class Connection
{
    protected static PDO|null $_instance = NULL;

    private function __construct()
    {
        try {
            self::$_instance = new PDO($_ENV['HOST_URL'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], [PDO::ATTR_PERSISTENT => TRUE]);
        } catch (PDOException $e) {
            error_log($_ENV['HOST_URL'] . ' | Connection échouée: ' . $e->getMessage());
        }
    }

    public static function getInstance(): PDO
    {
        if (!self::$_instance) {
            new Connection();
        }
        return self::$_instance;
    }
}