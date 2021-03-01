<?php

namespace grigor\generator\forms;

use yii\base\Model;

class PathForm extends Model
{
    public $path;

    public function rules()
    {
        return [
            ['path', 'required'],
            ['path', 'string', 'max' => 255],
            ['path', function ($attribute, $params) {
                if (empty($this->path)) {
                    $this->addError($attribute, 'The path must not be empty.');
                }
                if (!is_dir($this->path)) {
                    $this->addError($attribute, 'The path is not a directory.');
                }
            }]
        ];
    }

    public function exportDto(): PathDto
    {
        return new PathDto($this->path);
    }
}

