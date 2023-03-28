<?php declare(strict_types=1);

namespace System\Database\Adapters\Pdo;

use Contracts\Database\Statement as Contract;
use PDO;
use PDOStatement;

class Statement extends PDOStatement implements Contract
{

    private function __construct()
    { }


    public function bindParam($parameter, &$variable, $type = null, $length = null, $options = null)
    {
        return parent::bindParam($parameter, $variable, $this->getType($value), $length, $options);
    }


    public function bindValue($parameter, $value, $type = null)
    {
        return parent::bindValue($parameter, $value, $this->getType($value));
    }


    public function bindValues($values)
    {
        $isAssoc = count(array_filter(array_keys($values), 'is_string')) > 0;

        foreach ($values as $name => $value) {
            if (!is_array($value)) {
                $value = [$value, $this->getType($value)];
            }

            // If This Is An Indexed Array, We Need To Offset The Parameter Name
            // By 1 Because It's 1-Indexed
            if (!$isAssoc) {
                ++$name;
            }

            if (count($value) !== 2 || !$this->bindValue($name, $value[0], $value[1])) {
                return false;
            }
        }

        return true;
    }


    public function getType($value)
    {
        $type = PDO::PARAM_STR;

        if (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        }

        if (is_int($value)) {
            $type = PDO::PARAM_INT;
        }

        if (is_null($value)) {
            $type = PDO::PARAM_NULL;
        }

        return $type;
    }
}
