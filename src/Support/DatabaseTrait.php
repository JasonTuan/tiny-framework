<?php

namespace TinyFramework\Support;

use Carbon\Carbon;
use DateTime;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

trait DatabaseTrait
{
    public function save(): string|int|bool
    {
        $id = $this->getId();
        if ($id) {
            return $this->getModel()->update();
        } else {
            return $this->getModel()->insert();
        }
    }

    public function insert(): string|int
    {
        $fields = $this->getFields();
        $values = $this->getFieldValues();

        $sql = 'INSERT INTO `' . $this->getModel()::TABLE_NAME . '` (`' . implode('`, `', $fields) . '`) VALUES (' . implode(', ', $values) . ')';

        $insertedId = db()->queryInsert($sql);

        if ($this->getPrimaryKey() !== 'id') {
            return $this->getId();
        }

        return $insertedId;
    }

    public function update(): bool
    {
        $fields = $this->getFields();
        $values = $this->getFieldValues();

        $id = $this->getId();
        if (is_string($id)) {
            $id = "'" . $id . "'";
        }

        $sql = 'UPDATE `' . $this->getModel()::TABLE_NAME . '` SET ';
        $sql .= implode(', ', array_map(function ($field, $value) {
            return "`$field` = $value";
        }, $fields, $values));
        $sql .= ' WHERE `' . $this->getPrimaryKey() . '` = ' . $id;

        return db()->queryUpdate($sql);
    }

    public function delete(): bool
    {
        $id = $this->getId();
        if (is_string($id)) {
            $id = "'" . $id . "'";
        }
        $sql = 'DELETE FROM `' . $this->getModel()::TABLE_NAME . '` WHERE `' . $this->getPrimaryKey() . '` = ' . $id;

        return db()->queryDelete($sql);
    }

    public static function find(string|int $id): ?static
    {
        $model = new static();
        if (is_string($id)) {
            $id = "'" . $id . "'";
        }
        $sql = 'SELECT * FROM `' . $model->getModel()::TABLE_NAME . '` WHERE `' . $model->getPrimaryKey() . "` = " . $id;
        $records = db()->querySelect($sql);

        if (empty($records)) {
            return null;
        }

        static::autoMappingValues($model, $records[0]);

        return $model;
    }

    public static function findOrFail(string|int $id): static
    {
        $model = static::find($id);
        if (empty($model)) {
            throw new Exception('Model not found');
        }

        return $model;
    }

    public static function all(): array
    {
        $model = new static();
        $sql = 'SELECT * FROM `' . $model->getModel()::TABLE_NAME . '`';
        $records = db()->querySelect($sql);

        if (empty($records)) {
            return [];
        }

        return array_map(function ($record) use ($model) {
            $instance = new static();
            static::autoMappingValues($instance, $record);
            return $instance;
        }, $records);
    }

    public static function autoMappingValues(object $model, array $data): void
    {
        foreach ($data as $key => $value) {
            try {
                $rp = new ReflectionProperty($model::class, $key);
            } catch (ReflectionException $e) {
                continue;
            }
            $type = $rp->getType()->getName();
            if ($type === Carbon::class) {
                $model->getModel()->{$key} = new Carbon($value);
            } elseif (enum_exists($type)) {
                $model->getModel()->{$key} = $type::from($value);
            } elseif ($type === 'int') {
                $model->getModel()->{$key} = (int)$value;
            } elseif ($type === 'float') {
                $model->getModel()->{$key} = (float)$value;
            } elseif ($type === 'bool') {
                $model->getModel()->{$key} = (bool)$value;
            } else {
                $model->getModel()->{$key} = $value;
            }
        }
    }

    public function getModel(): mixed
    {
        return $this;
    }

    public function getPrimaryKey(): string
    {
        return $this->getModel()::PRIMARY_KEY;
    }

    public function getId(): int|string|null
    {
        return $this->getModel()->{$this->getPrimaryKey()};
    }

    private function getFields(): array
    {
        $reflect = new ReflectionClass($this->getModel());
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        return array_map(function ($prop) {
            return $prop->getName();
        }, $props);
    }

    private function getFieldValues(): array
    {
        $fields = $this->getFields();
        return array_map(function ($prop) {
            $value = $this->getModel()->{$prop};
            $quoted = true;
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            } elseif (is_bool($value)) {
                $quoted = false;
                $value = $value ? 1 : 0;
            } elseif (is_null($value)) {
                $quoted = false;
                $value = 'NULL';
            } elseif (is_object($value) && enum_exists($value::class)) {
                $value = $value->value;
            }

            return $quoted ? "'" . $value . "'" : $value;
        }, $fields);
    }
}
