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
<style>

    .custom-btn[disabled="true"]{
        background-color: #581c88 !important;
        color: white;
    }

</style>
    <div class="latest_img_post" style='background-image: url("<?= $model->getCroppedImage(826, null) ?>")'>
        <div class="first"></div>
        <div class="second">
            <?php
                $urlEnCode = urlencode($model->getShortViewUrl());
            ?>
            <div class="share" style="cursor:pointer;" id="myBtn"></div>
            <div class="social">
                <a href="https://t.me/share/url?url=<?= $urlEnCode ?>" target="_blank">
                    <div class="tg"></div>
                </a>
                <a href="<?= 'https://www.facebook.com/sharer.php?u=' . $urlEnCode ?>" target="_blank">
                    <div class="fc"></div>
                </a>
                <a href="<?= 'https://www.instagram.com/sharer.php?u=' . $urlEnCode ?>" target="_blank">
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
            <p>Ulashish</p>
            <div class="share_modal">
                <div class="sm-modal">
                    <a href="https://t.me/share/url?url=<?= $urlEnCode ?>" target="_blank">
                        <div class="tg-icon-modal"></div>
                        <div class="icon-text">Telegram</div>
                    </a>
                </div>
                <div class="sm-modal">
                    <a href="<?= 'https://www.facebook.com/sharer.php?u=' . $urlEnCode ?>" target="_blank">
                        <div class="fc-icon-modal"></div>
                        <div class="icon-text">Facebook</div>
                    </a>
                </div>
            </div>

            <div class="link-modal">
                <input type="text" name="postValue"  id="postValue" style="width:700px; border:none;overflow: hidden;text-overflow: ellipsis;" disabled value="<?= $model->getViewUrl()?>">
                
                <button style="cursor:pointer;" class="share-btn custom-btn" onclick="myFunction(); checkingBtn()">Nusxa olish</button>
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
            <?= $model->content ?>
        </div>
    </div>
</div>
<script>
    var copyText = document.getElementById("postValue");
    function myFunction() {
        /* Get the text field */
       

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.value);
    }

    function checkingBtn() {
        var btn = document.querySelector(".share-btn")
        if(copyText.value.length > 0){
            btn.setAttribute("disabled", "true");
            btn.innerHTML = "Nusxa olindi";           
        }else{
            btn.setAttribute("disabled", "false");
            
        }
    }
</script>
<? //= $this->renderFile('@frontend/views/post/_footer.php', ['model' => $model]) ?>
