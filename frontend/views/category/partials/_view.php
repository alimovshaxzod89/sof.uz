<?php

/**
 * @var $this \frontend\components\View
 * @var $model \frontend\models\PostProvider
 */
?>

<div class="mini_post_search">
    <a href="<?= $model->getViewUrl() ?>">
        <div class="img_search" style='background-image: url("<?= $model->getCroppedImage(500, 350, 1) ?>")'>
            <div></div>
            <div class="tag"><?= $model->category->name ?></div>
        </div>
    </a>
    <div class="text_mini">
        <div class="date_post">
            <div class="calendar_icon"></div>
            <div class="date_text"><?= $model->getShortFormattedDate() ?></div>
        </div>
        <a href="<?= $model->getViewUrl() ?>"><p class="title_search"><?= $model->title ?></p>
        </a>
        <p class="paragraph_search"><?= $model->info ?></p>
    </div>
</div>