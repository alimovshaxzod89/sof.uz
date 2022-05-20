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
                <div class="share" id="myBtn"></div>
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

    <!-- The Modal -->
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Ўлашиш</p>
            <div class="share_modal">
                <div class="sm-modal">
                    <a href="">
                        <div class="tg-icon-modal"></div>
                        <div class="icon-text">Telegram</div>
                    </a>
                </div>
                <div class="sm-modal">
                    <a href="">
                        <div class="ig-icon-modal"></div>
                        <div class="icon-text">Instagram</div>
                    </a>
                </div>
                <div class="sm-modal">
                    <a href="">
                        <div class="fc-icon-modal"></div>
                        <div class="icon-text">Facebook</div>
                    </a>
                </div>
            </div>

            <div class="link-modal">
                <div class="mt-4"><span class="post-link">https://new.sof.uz/uz/post/haydovchilik-guvohnomasini-almashtirish-muddati-uzaytirilishi-dxmlarga-haydovchilar-oqimini-10-barobardan-koproq-kamaytirdi</span>
                </div>
                <button class="share-btn">Нусха олиш</button>
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
