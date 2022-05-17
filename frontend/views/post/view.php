<?php

use common\models\Comment;
use frontend\components\View;
use frontend\models\PostProvider;

/**
 * @var $this    View
 * @var $model   PostProvider
 * @var $post    PostProvider
 * @var $comment Comment
 */

$category = isset($this->params['category']) ? $this->params['category'] : $model->category;
$this->_canonical = $model->hasAuthor() ? $model->getViewUrl() : $model->getViewUrl();
if (count($model->tags)) {
    $tags = [];
    foreach ($model->tags as $tag) {
        $tags[] = $tag->name;
    }
    $this->addKeywords($tags);
}

$this->title = $model->title;
$this->params['category'] = $category;
$this->params['post'] = $model;

$this->addDescription([$model->info]);
?>

<?php if ($model->checkImageFileExists()) : ?>
    <div class="latest_img_post" style='background-image: url("<?= $model->getCroppedImage(826, null) ?>")'>
        <div class="first"></div>
        <div class="second">
            <a href="">
                <div class="share"></div>
            </a>
            <div class="social">
                <a href="">
                    <div class="tg"></div>
                </a>
                <a href="">
                    <div class="fc"></div>
                </a>
                <a href="">
                    <div class="insta"></div>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="left-m">
    <div class="content">
        <div class="title_post">
            <?= $model->title ?>
        </div>
        <div class="date_post_whole">
            <div class="calendar_icon"></div>
            <div class="date_text"><?= $model->getShortFormattedDate() ?></div>
        </div>
        <div class="paragraph_whole">
            <?= $model->content; ?>
        </div>
    </div>
</div>

<? //= $this->renderFile('@frontend/views/post/_footer.php', ['model' => $model]) ?>
