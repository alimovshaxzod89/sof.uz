<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\components\View;
use frontend\models\PostProvider;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:48 AM
 */

/**
 * @var $this  View
 * @var $model PostProvider
 */
\frontend\assets\LightBoxAsset::register($this);
$this->registerJs("$('a.gallery-item').simpleLightbox({});");
$galleryItems = $model->getGalleryItemsModel();
?>
<?php if (count($galleryItems)): ?>
    <?php foreach ($galleryItems as $item): ?>
        <?php if ($big = $item->getImageCropped(960, null)): ?>
            <?php if ($image = $item->getImageCropped(440, 280)): ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <a href="<?= $big ?>" class="image gallery-item">
                    <figure class="image" contenteditable="false">
                        <img src="<?= $image ?>" alt="">
                        <?php if ($caption = $item->caption): ?>
                            <figcaption>
                                <?= $caption ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                </a>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach ?>

<?php endif; ?>
