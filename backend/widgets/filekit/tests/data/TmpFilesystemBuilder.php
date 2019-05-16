<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace backend\widgets\filekit\tests\data;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use backend\widgets\filekit\filesystem\FilesystemBuilderInterface;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class TmpFilesystemBuilder implements FilesystemBuilderInterface
{

    /**
     * @return mixed
     */
    public function build()
    {
        return new Filesystem(new Local(sys_get_temp_dir()));
    }
}
