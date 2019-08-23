<?php

namespace common\components;

use Imagine\Image\Color;
use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Point;
use RuntimeException;
use Yii;
use yii\imagine\BaseImage;

class InterlacedImage extends BaseImage
{
    public static function thumbnailWithWatermark($filename, $width, $height, $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND)
    {
        $img = self::thumbnail($filename, $width, $height, $mode);

        $watermark = static::getImagine()->open(\Yii::getAlias('@frontend/assets/app/img/logo_politics.png'));
        $wSize     = $watermark->getSize();
        $size      = $img->getSize();

        $bottomRight = new \Imagine\Image\Point($size->getWidth() - $wSize->getWidth() - 10, $size->getHeight() - $wSize->getHeight() - 10);

        return $img->paste($watermark, $bottomRight);
    }

    public static function thumbnail($filename, $width, $height, $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND)
    {
        try {
            $img = static::getImagine()->open(Yii::getAlias($filename));
        } catch (RuntimeException $e) {
            return self::thumbnail(Yii::getAlias('@static/uploads/img-placeholder.png'), $width, $height);
        }
        $img->interlace(ImageInterface::INTERLACE_PARTITION);
        $sourceBox    = $img->getSize();
        $thumbnailBox = static::getThumbnailBox($sourceBox, $width, $height);

        if (($sourceBox->getWidth() <= $thumbnailBox->getWidth() && $sourceBox->getHeight() <= $thumbnailBox->getHeight()) || (!$thumbnailBox->getWidth() && !$thumbnailBox->getHeight())) {
            return $img->copy();
        }

        $img = $img->thumbnail($thumbnailBox, $mode);

        if ($mode == ManipulatorInterface::THUMBNAIL_OUTBOUND) {
            return $img;
        }

        $size = $img->getSize();

        if ($size->getWidth() == $width && $size->getHeight() == $height) {
            return $img;
        }

        // create empty image to preserve aspect ratio of thumbnail
        $thumb = static::getImagine()->create($thumbnailBox, new Color(static::$thumbnailBackgroundColor, static::$thumbnailBackgroundAlpha));

        // calculate points
        $startX = 0;
        $startY = 0;
        if ($size->getWidth() < $width) {
            $startX = ceil($width - $size->getWidth()) / 2;
        }
        if ($size->getHeight() < $height) {
            $startY = ceil($height - $size->getHeight()) / 2;
        }

        $thumb->paste($img, new Point($startX, $startY));

        return $thumb;
    }
}