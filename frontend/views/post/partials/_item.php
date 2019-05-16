<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Created by PhpStorm.
 * User: rustam
 * Date: 1/21/18
 * Time: 10:04 PM
 *
 * @var $model       \common\models\Comment
 * @var $comment     \common\models\Comment
 * @var $showReplies bool
 */

?>

<div class="article__comments-item">
    <div class="media-info" id="<?= $model->getId() ?>">
        <div class="media is_left hidden-xs">
            <img src="<?= $this->getImageUrl('avatars/sevara.png') ?>" width="50" height="50"
                 alt="<?= $model->user->fullname ?>">
        </div><!-- End of media-->
        <div class="info auto">
            <div class="article__comments-item-title">
                <h2><?= $model->user->fullname ?></h2>
                <span class="date-time"><i
                            class="icon clock-icon"></i><?= Yii::$app->formatter->asRelativeTime($model->created_at->sec) ?></span>
            </div><!-- End of article__comments-item-title-->

            <p><?= $model->text ?></p>

            <div class="article__comments-controls">
                <a href="<?= Url::current(['replies' => $model->getId()]) ?>" id="comment_replies"
                   data-id="<?= $model->getId() ?>" aria-expanded="false">
                    <i class="icon answer-icon"></i><?= count($model->replies) ? __('{count} ta javobni ko\'rish', ['count' => count($model->replies)]) : __('Javob berish') ?>
                </a>

                <span class="counters">
                    <a href="#"><i class="icon thumbs-up-icon"></i>230</a>
                    <span class="h-space half"></span>
                    <a href="#"><i class="icon thumbs-down-icon"></i>15</a>
                </span>
            </div><!-- End of article__comments-controls-->

        </div><!-- End of info-->
    </div><!-- End of media-info-->
    <?php if ($showReplies && $showReplies == $model->getId()): ?>

        <?php if ($model->replies && count($model->replies)): ?>
            <?php foreach ($model->replies as $reply): ?>
                <div class="media-info reply-item">
                    <div class="media is_left hidden-xs">
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
                <div class="media is_left hidden-xs">
                    <img src="<?= $this->getImageUrl('avatars/odil.png') ?>" width="50" height="50" alt="Odil">
                </div><!-- End of media-->
                <div class="info auto">
                    <?php $form = ActiveForm::begin(['action' => Url::current(['replyTo' => $model->getId()]), 'class' => 'reply_comment']) ?>
                    <?= $form->field($comment, 'text')->textarea(['id' => 'reply-text','class' => 'article__comments-commentate-form form-control'])->label(false) ?>
                    <?= Html::submitButton(__('Yuborish'), ['class' => 'article__comments-commentate-submit', 'data-user' => Yii::$app->user->isGuest ? 'false' : 'true']) ?>
                    <?php ActiveForm::end() ?>
                </div><!-- End of info-->
            </div><!-- End of media-info-->
        </div>
    <?php endif; ?>
</div><!-- End of comments-item-->