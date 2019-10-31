<?php

use frontend\models\PostProvider;

/**
 * @var $model PostProvider
 */
?>
<?php if (is_array($model->gallery) && count($model->gallery)): $lang = Yii::$app->language == \common\components\Config::LANGUAGE_UZBEK ? 'uz' : 'oz' ?>
    <div class="entry-media">
        <div class="entry-gallery justified-gallery">
            <?php foreach ($model->gallery as $item):
                if (!isset($item['path'])) continue;

                $item['path'] = preg_replace('/[\d]{2,4}_[\d]{2,4}_/', '', $item['path']);
                $imagePath    = Yii::getAlias('@static') . DS . 'uploads' . DS . $item['path'];
                if (file_exists($imagePath)):
                    $size = getimagesize($imagePath);
                    $width    = isset($size[0]) ? $size[0] : 800;
                    $height   = isset($size[1]) ? $size[1] : 533; ?>
                    <div class="gallery-item">
                        <a href="<?= PostProvider::getCropImage($item, 1200, null) ?>"
                           data-width="<?= $width ?>"
                           data-height="<?= $height ?>"
                        >
                            <img src="<?= PostProvider::getCropImage($item, 320, 240) ?>"
                                 alt="<?= isset($item['caption']) && isset($item['caption'][$lang])?$item['caption'][$lang]:'' ?>">
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php if (is_array($model->tags) && count($model->tags)): ?>
    <div class="entry-tags">
        <?php foreach ($model->tags as $tag): ?>
            <a href="<?= $tag->getViewUrl() ?>" rel="tag"><?= $tag->name ?></a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="entry-action">
    <?php
    $urlEnCode = urlencode($model->getShortViewUrl());
    ?>
    <div class="action-share">
        <a class="facebook" target="_blank"
           href="<?= 'https://www.facebook.com/sharer.php?u=' . $urlEnCode ?>">
            <i class="mdi mdi-facebook"></i>
        </a>
        <a class="twitter" target="_blank"
           href="https://twitter.com/intent/tweet?url=<?= $urlEnCode ?>">
            <i class="mdi mdi-twitter"></i>
        </a>
        <a class="vk" target="_blank"
           href="http://vk.com/share.php?url=<?= $urlEnCode ?>">
            <i class="mdi mdi-vk"></i>
        </a>
        <a class="telegram" target="_blank"
           href="https://t.me/share/url?url=<?= $urlEnCode ?>">
            <i class="mdi mdi-telegram"></i>
        </a>
        <input type="text" readonly class="select_text"
               value="<?= preg_replace('/https?:\/\//', '', $model->getShortViewUrl()) ?>">
    </div>
</div>


<?php if ($model->hasAuthor() && 0): ?>
    <div class="author-box">
        <div class="author-image">
            <img alt="<?= $model->author->full_name ?>" height="140" width="140"
                 src="<?= $model->author->getCroppedImage(140, 140) ?>"
                 class='avatar avatar-140 photo'/>
        </div>

        <div class="author-info">
            <h4 class="author-name">
                <a href="<?= $model->author->getViewUrl() ?>"><?= $model->author->full_name ?></a>
            </h4>

            <div class="author-bio">
                <?= $model->author->description ?>
            </div>

            <?php if (0): ?>
                <div class="author-meta">
                    <a class="website" href="#" target="_blank">
                        <i class="mdi mdi-web"></i></a>
                    <a class="facebook" href="#" target="_blank">
                        <i class="mdi mdi-facebook-box"></i></a>
                    <a class="twitter" href="#" target="_blank">
                        <i class="mdi mdi-twitter-box"></i></a>
                    <a class="instagram" href="#" target="_blank">
                        <i class="mdi mdi-instagram"></i></a>
                    <a class="google" href="#" target="_blank">
                        <i class="mdi mdi-google-plus-box"></i></a>
                    <a class="linkedin" href="#" target="_blank">
                        <i class="mdi mdi-linkedin-box"></i></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
