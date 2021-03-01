<?php

namespace grigor\generator\entities;

use yii\base\BaseObject;

class FileSetting extends BaseObject implements Setting
{

    public $service;
    public $context;
    public $response;
    public $serializer;
    public $permissions;
    public $identity;
    public $path;

    private function exportSetting(): string
    {
        $textSetting = "<?php return [";

        $textSetting .= "'service' => [";
        $textSetting .= "'class' => '" . $this->service['class'] . "',";
        $textSetting .= "'method' => '" . $this->service['method'] . "',";
        $textSetting .= '],';

        if (!empty($this->permissions)) {
            $textSetting .= "'permissions' => ['" . implode("', '", $this->permissions) . "'],";
        }

        if (!empty($this->serializer)) {
            $textSetting .= "'serializer' => '" . $this->serializer . "',";
        }

        if (!empty($this->response)) {
            $textSetting .= "'response' => $this->response,";
        }

        if (!empty($this->context)) {
            $textSetting .= "'context' => '" . $this->context . "',";
        }

        $textSetting .= "];";
        return $textSetting;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        try {
            file_put_contents($this->path . $this->identity . '.php', $this->exportSetting());
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public static function instance($refresh = false)
    {

    }

    public function populateRelation($name, $records)
    {

    }

    public static function getDb()
    {

    }

    public static function primaryKey()
    {
        // TODO: Implement primaryKey() method.
    }

    public function attributes()
    {
        // TODO: Implement attributes() method.
    }

    public function getAttribute($name)
    {
        // TODO: Implement getAttribute() method.
    }

    public function setAttribute($name, $value)
    {
        // TODO: Implement setAttribute() method.
    }

    public function hasAttribute($name)
    {
        // TODO: Implement hasAttribute() method.
    }

    public function getPrimaryKey($asArray = false)
    {
        // TODO: Implement getPrimaryKey() method.
    }

    public function getOldPrimaryKey($asArray = false)
    {
        // TODO: Implement getOldPrimaryKey() method.
    }

    public static function isPrimaryKey($keys)
    {
        // TODO: Implement isPrimaryKey() method.
    }

    public static function find()
    {
        // TODO: Implement find() method.
    }

    public static function findOne($condition)
    {
        // TODO: Implement findOne() method.
    }

    public static function findAll($condition)
    {
        // TODO: Implement findAll() method.
    }

    public static function updateAll($attributes, $condition = null)
    {
        // TODO: Implement updateAll() method.
    }

    public static function deleteAll($condition = null)
    {
        // TODO: Implement deleteAll() method.
    }


    public function insert($runValidation = true, $attributes = null)
    {
        // TODO: Implement insert() method.
    }

    public function update($runValidation = true, $attributeNames = null)
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function getIsNewRecord()
    {
        // TODO: Implement getIsNewRecord() method.
    }

    public function equals($record)
    {
        // TODO: Implement equals() method.
    }

    public function getRelation($name, $throwException = true)
    {
        // TODO: Implement getRelation() method.
    }

    public function link($name, $model, $extraColumns = [])
    {
        // TODO: Implement link() method.
    }

    public function unlink($name, $model, $delete = false)
    {
        // TODO: Implement unlink() method.
    }
}