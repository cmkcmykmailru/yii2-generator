<?php

namespace grigor\generator\forms;

use yii\base\Model;

class DevDirectoriesForm extends Model
{
    public $paths;

    public function rules()
    {
        return [
            ['paths', 'required'],
            ['paths', 'each', 'rule' => ['string', 'max' => 255]],
            ['paths', function ($attribute, $params) {
                foreach ($this->paths as $path)
                    if (!is_dir($path)) {
                        $this->addError($attribute, 'The path is not a directory.');
                    }
            }]
        ];
    }

    public function exportDto(): DevDirectoriesDto
    {
        return new DevDirectoriesDto($this->paths);
    }
}