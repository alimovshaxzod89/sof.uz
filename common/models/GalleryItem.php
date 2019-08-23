<?php

namespace common\models;

use common\components\Config;
use Imagine\Image\ManipulatorInterface;

class GalleryItem
{
    public $caption;
    public $path;
    public $image;

    /**
     * GalleryItem constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->caption = $data['caption'][Config::getLanguageCode()];
        $this->image   = $data;
    }

    public function getImageCropped($width = 642, $height = 340)
    {
        return MongoModel::getCropImage($this->image, $width, $height, ManipulatorInterface::THUMBNAIL_OUTBOUND, false, 80);
    }
}