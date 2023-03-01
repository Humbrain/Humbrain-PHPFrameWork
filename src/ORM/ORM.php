<?php

namespace Steodec\SteoFrameWork\ORM;

use PDO;
use ReflectionClass;
use ReflectionProperty;

abstract class ORM
{
    protected string $TABLE_NAME;
    private PDO $_pdo;

    public function __construct()
    {
        $this->_pdo = Connection::getInstance();
    }

    /**
     *
     * @return Entity[]
     * @throws ORMException
     *
     * @author steodec
     * @version 0.0.1
     */
    public function find(array|string $options): iterable
    {

        $result = [];
        $query = 'SELECT * FROM ' . $this->TABLE_NAME;
        $logique = 'AND';
        $whereClause = '';
        $whereConditions = [];
        if (is_array($options) && !empty($options)):
            if (array_key_exists('conditions', $options)):
                $conditions = $options['conditions'];
                if (is_array($conditions))
                    if (array_key_exists('logique', $conditions)):
                        $logique = $conditions['logique'];
                        unset($conditions['@logique']);
                    endif;
                foreach ($conditions as $key => $value)
                    $whereConditions[] = '`' . $key . '` = :' . $key;
                $whereClause = " WHERE " . implode(' ' . $logique . ' ', $whereConditions);
            endif;
            if (array_key_exists('order', $options)):
                $whereClause .= ' ORDER BY ' . $options['oder'];
            endif;
            if (array_key_exists('limit', $options)):
                $whereClause .= ' LIMIT ' . $options['limit'];
            endif;
        elseif (is_string($options) && !empty($options)):
            $whereClause = " WHERE " . $options;
        else:
            throw new ORMException('Wrong parameter type of options');
        endif;

        $query .= $whereClause;
        $sql = $this->_pdo->prepare($query);
        if (is_array($options) && !empty($options)):
            if (array_key_exists('conditions', $options)):
                foreach ($options['conditions'] as $key => $value):
                    $propertyValue = (is_array($value) or is_object($value)) ? json_encode($value) : $value;
                    $sql->bindParam(':' . $key, $propertyValue, $this->getType(gettype($propertyValue)));
                    var_dump(':' . $key);
                endforeach;
            endif;
        endif;
        if (!$sql->execute())
            throw new ORMException("Une erreur c'est produit: " . $sql->queryString);
        return $sql->fetchAll(PDO::FETCH_CLASS, get_class($this)) ?? [];
    }

    /**
     * Return type of property
     * @param string $property
     * @return int
     *
     * @author steodec
     * @version 0.0.1
     */
    private function getType(string $property): int
    {
        return match ($property) {
            'int', 'float', 'integer', 'double' => PDO::PARAM_INT,
            'null' => PDO::PARAM_NULL,
            'bool', 'boolean' => PDO::PARAM_BOOL,
            default => PDO::PARAM_STR,
        };
    }

    /**
     * Create or Update data table
     *
     * @return bool|int
     *
     * @throws ORMException
     * @author steodec
     * @version 0.0.1
     */
    public function save(): bool|int
    {
        $class = new ReflectionClass($this);
        $tableName = $this->TABLE_NAME;

        $propsToImplode = [];
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property):
            if (!$property->isInitialized() or empty($property)) continue;
            $propertyName = $property->getName();
            $propsToImplode[] = '`' . $propertyName . '` = ":' . $propertyName . '"';
        endforeach;

        $setClause = implode(',', $propsToImplode); // glue all key value pairs together
        $sqlQuery = '';
        $sqlQuery = $this->id > 0 ? sprintf("UPDATE `%s` SET %s WHERE id = %d", $tableName, $setClause, $this->id) : sprintf("INSERT INTO `%s` SET %s", $tableName, $setClause);

        $sql = $this->_pdo->prepare($sqlQuery);
        foreach ($properties as $property):
            $propertyName = $property->getName();
            $propertyValue = (is_array($this->{$propertyName}) or is_object($this->{$propertyName})) ? json_encode($this->{$propertyName}) : $this->{$propertyName};
            $sql->bindParam(":$propertyName", $propertyValue, $this->getType($property->getType()->getName()));
        endforeach;
        $result = $sql->execute();
        if ($this->_pdo->errorCode()) {
            throw new ORMException($this->_pdo->errorInfo()[2]);
        }
        return $result;
    }
}