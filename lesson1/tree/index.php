<?php

$path = $_GET['path'] . '/';

//if (is_dir($path)) {
//    $tree = new DirectoryIterator($path);
//
//    run($tree);
//}

class Tree extends DirectoryIterator
{
    protected $path;

    public function __construct($path)
    {
        parent::__construct($path);
        $this->path = $path;
    }

    public function run()
    {
        foreach ($this as $item) {
            if (is_dir($this->path . $item)) {
                echo "<a href=\"/tree/index.php?path={$this->path}{$item}\">DIR: {$item}</a><br/>";
            } else {
                echo "FILE: {$item}<br/>";
            }
        }
    }
}

(new Tree($path))->run();