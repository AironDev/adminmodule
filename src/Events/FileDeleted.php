<?php

namespace Modules\Admin\Events;

class FileDeleted
{
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }
}
