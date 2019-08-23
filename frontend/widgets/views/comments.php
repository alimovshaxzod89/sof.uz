<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

use yii\helpers\Url;

/**
 * @var $post     \common\models\Post
 * @var $model    \common\models\Comment
 * @var $comment  \common\models\Comment
 */
?>

<?php if ($comment->replies && count($comment->replies)): ?>
    <?php foreach ($comment->replies as $reply): ?>
            <div class="media-info reply-item">
                <div class="media is_left">
                    <img src="<?= $this->getImageUrl('avatars/sevara.png') ?>" width="50" height="50"
                         alt="<?= $reply->user->fullname ?>">
                </div><!-- End of media-->
                <div class="info auto">
                    <div class="article__comments-item-title">
                        <i class="icon reply-comment-icon"></i>
                        <h2><?= $reply->user->fullname ?></h2>
                        <span class="date-time"><i
                                    class="icon clock-icon"></i><?= Yii::$app->formatter->asRelativeTime($reply->created_at->sec) ?></span>
                    </div><!-- End of article__comments-item-title-->

                    <p><?= $reply->text ?></p>

                    <div class="article__comments-controls">
                        <span class="counters">
                                            <a href="#"><i class="icon thumbs-up-icon"></i><?= mt_rand(1, 100) ?></a>
                                            <span class="h-space half"></span>
                                            <a href="#"><i class="icon thumbs-down-icon"></i><?= mt_rand(1, 100) ?></a>
                                        </span>
                    </div><!-- End of article__comments-controls-->

                </div><!-- End of info-->
            </div><!-- End of media-info-->
    <?php endforeach; ?>
<?php endif; ?>
<div class="article__comments-commentate reply-item">
    <div class="article__comments-commentate-title">
        <h2><?= !is_null($this->getUser()) ? $this->getUser()->fullname : '' ?></h2>
    </div><!-- End of article__comment-commentate-title-->

    <div class="media-info">
        <div class="media is_left">
            <img src="<?= $this->getImageUrl('avatars/odil.png') ?>" width="50" height="50" alt="Odil">
        </div><!-- End of media-->
        <div class="info auto">
            <?php $form = \yii\widgets\ActiveForm::begin(['action' => Url::to([$post->category->slug . '/' . $post->url, 'replyTo' => $comment->getId()]), 'class' => 'reply_comment']) ?>
            <?= $form->field($model, 'text')->textarea(['class' => 'article__comments-commentate-form form-control'])->label(false) ?>
            <?= \yii\helpers\Html::submitButton(__('Yuborish'), ['class' => 'article__comments-commentate-submit', 'data-user' => Yii::$app->user->isGuest ? 'false' : 'true']) ?>
            <?php \yii\widgets\ActiveForm::end() ?>
        </div><!-- End of info-->
    </div><!-- End of media-info-->
</div>
