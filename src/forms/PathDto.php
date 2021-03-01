<?php


namespace grigor\generator\forms;


class PathDto
{
    public $path = '.';

    /**
     * PathDto constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

}