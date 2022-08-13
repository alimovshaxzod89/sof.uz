<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $mainPosts PostProvider[]
 * @var $photoPosts PostProvider[]
 */
$this->_canonical = Yii::$app->getHomeUrl();
$this->addBodyClass('home page-template page-template-page-modular page-template-page-modular-php page sidebar-none modular-title-1');
$limit = 10;
$photoPosts = PostProvider::getTopPhotos(10);
$videoPosts = PostProvider::getTopVideos(10);

$mahalliyCategory = \frontend\models\CategoryProvider::findOne(['slug' => 'ozbekiston']);
$mahalliyPosts = PostProvider::getPostsByCategory($mahalliyCategory, 5, false);
$mahalliyPost = array_shift($mahalliyPosts);
$mainPost = false;

$recommendedPosts = PostProvider::getPopularPosts(3);
$recommendedPosts = PostProvider::getTopPost(3);


?>

<style>
    
    .custom-btn[disabled="true"]{
        background-color: #581c88 !important;
        color: white;
    }

</style>
<div class="news_block">

    <div class="latest_news">

        <?php if ($mahalliyPost instanceof PostProvider): ?>
            <article class="post post-large type-post status-publish format-video has-post-thumbnail hentry category-food post_format-post-format-video">
                <div class="latest_block">

                    <div class="latest_title">
                        <div class="icon"></div>
                        <h4 class="title_con"><?= $mahalliyPost->category->name ?></h4>
                    </div>

                    <div class="whole_post">
                        <div class="latest_img" style='background-image: url("<?= $mahalliyPost->getCroppedImage(500, 350, 1) ?>")'>
                            <a href="<?= $mahalliyPost->getViewUrl() ?>" target="_blank">
                                <div class="first"></div>
                            </a>
                            <div class="second">
                                <?php
                                $urlEnCode = urlencode($mahalliyPost->getShortViewUrl());
                                ?>
                                <div class="share" style="cursor:pointer;" id="myBtn"></div>
                                <div class="social">
                                    <a href="https://t.me/share/url?url=<?= $urlEnCode ?>" target="_blank">
                                        <div class="tg"></div>
                                    </a>
                                    <a  href="<?= 'https://www.facebook.com/sharer.php?u=' . $urlEnCode ?>" target="_blank">
                                        <div class="fc"></div>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url=<?= $urlEnCode ?>" target="_blank">
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
                                    <a href="http://vk.com/share.php?url=<?= $urlEnCode ?>" target="_blank">
                                        <div class="vk-icon-modal"></div>
                                        <div class="icon-text">VKontakte</div>
                                    </a>
                                </div>
                                <div class="sm-modal">
                                    <a href="https://twitter.com/intent/tweet?url=<?= $urlEnCode ?>" target="_blank">
                                        <div class="tw-icon-modal"></div>
                                        <div class="icon-text">Twitter</div>
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
                                    <input type="text" name="postValue"  id="postValue" style="width:700px; border:none;overflow: hidden;text-overflow: ellipsis;" disabled value="<?= $mahalliyPost->getViewUrl()?>">
                                
                                    <button style="cursor:pointer;" class="share-btn custom-btn" onclick="myFunction(); checkingBtn()">Nusxa olish</button>
                                </div>
                            </div>

                        </div>

                        <div class="content">
                            <a href="<?= $mahalliyPost->getViewUrl() ?>">
                                <div class="title">
                                    <?= $mahalliyPost->title ?>
                                </div>
                            </a>
                            <div class="date_post">
                                <div class="calendar_icon"></div>
                                <div class="date_text"><?= $mahalliyPost->getShortFormattedDate() ?>, &nbsp;</div>
                                <div class="eye_icon"></div>
                                <div class="date_text"><?= $mahalliyPost->getViewLabel() ?></div>
                            </div>
                            <div class="paragraph">
                                <?= $mahalliyPost->info ?>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        <?php endif; ?>

        <div class="mini_news">

            <div class="st_block">
                <?php foreach ($mahalliyPosts as $i => $post): ?>
                    <article class="post post-list type-post status-publish format-standard has-post-thumbnail hentry category-food">
                        <div class="mini_post">
                            <a href="<?= $post->getViewUrl() ?>">
                                <div class="img_mini"
                                    style='background-image: url("<?= $post->getCroppedImage(190, 100, 1) ?>")'>
                                    <div></div>
                                    <div class="tag"><?= $post->category->name ?></div>
                                </div>
                            </a>
                            <div class="text_mini">
                                <div class="date_post">
                                    <div class="calendar_icon"></div>
                                    <div class="date_text"><?= $post->getShortFormattedDate() ?>, &nbsp;</div>
                                    <div class="eye_icon"></div>
                                    <div class="date_text"><?= $post->getViewLabel() ?></div>
                                </div>
                                <a href="<?= $post->getViewUrl() ?>"><p class="title_mini"><?= $post->title ?></p></a>
                            </div>
                        </div>
                        <?php if ($i == 1): ?>
                </div>
                <div class="st_block">
                    <?php endif; ?>
                    </article>
                <?php endforeach; ?>
                    
            </div>
        </div>

        <div class="mini_news_nd">

            <div class="st_block">
                <div class="latest_title_nd">
                    <div class="icon"></div>
                    <h4 class="title_con"><?= __('Тавсия этамиз') ?></h4>
                </div>
            </div>

            <div class="nd_block">

                <?php foreach ($recommendedPosts as $i => $post): ?>
                    <article
                            class="post type-post status-publish format-standard has-post-thumbnail hentry category-design">
                        <div class="<?= $i == 0 ? 'block_news_third' : ($i == 1 ? 'block_news_second' : 'block_news_fourth') ?>">
                            <a href="<?= $post->getViewUrl() ?>">
                                <div class="block_image"
                                    style='background-image: url("<?= $post->getCroppedImage(500, 350, 1) ?>")'>
                                    <div></div>
                                    <div class="tag_bigger"><?= $post->category->name ?></div>
                                </div>
                            </a>
                            <div class="date_post_bold">
                                <div class="calendar_icon"></div>
                                <div class="date_text"><?= $post->getShortFormattedDate() ?>, &nbsp;</div>
                                <div class="eye_icon"></div>
                                <div class="date_text"><?= $post->getViewLabel() ?></div>
                            </div>

                            <div class="paragraph_bold">
                                <a href="<?= $post->getViewUrl() ?>">
                                    <?= $post->title ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <?= $this->render('partials/_index_right_side') ?>

</div>

<?= $this->render('partials/_index_dolzarb') ?>
<script>
    var copyText = document.getElementById("postValue");
    function myFunction() {
        /* Get the text field */
       

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*Долзарб For mobile devices */

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
<?= $this->render('partials/_index_videos') ?>
