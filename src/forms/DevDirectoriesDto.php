<?php


namespace grigor\generator\forms;


class DevDirectoriesDto
{
    public $directories = [];

    /**
     * DevDirectoriesDto constructor.
     * @param array $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

}