<?php

use common\models\Page;
use frontend\components\View;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:48 AM
 */

/**
 * @var $this  View
 * @var $model Page
 */

$this->title = $model->title;

if ($this instanceof View) {
    $this->_canonical = $model->getViewUrl();
    $this->addBodyClass('post-template-default single single-post no-sidebar ');
    $this->addBodyClass('page-' . $model->url);
}
$comments = false;
?>

<div class="ts-row cf">
    <div class="col-8 main-content cf">
        <article class="the-post-modern the-post post">
            <header class="post-header the-post-header cf">
                <div class="post-meta post-meta-b the-post-meta">
                    <h1 class="post-title-alt">
                        <?= $model->title ?>
                    </h1>
                </div>
            </header>
            <div class="post-content description cf entry-content content-spacious-full">
                <?= $model->content ?>
            </div>
            <?php if ($model->url == 'loyiha-haqida'): ?>

                <?php
                $title = __('Tahririyat jamoasi');
                $posts = \common\models\Blogger::getRedaction();
                $count = count($posts);
                ?>
                <?php if ($count > 1): ?>
                    <div class="the-post-foot cf team-grid">
                        <section class="related-posts grid-3 ">
                            <h4 class="section-head">
                                <span class="title"><?= $title ?></span>
                            </h4>
                            <div class="ts-row posts cf">
                                <?php foreach ($posts as $post): ?>
                                    <article class="post col-3 col-sm-6 col-md-4">

                                        <a href="<?= $post->getViewUrl() ?>" class="image-link">
                                            <img class="image"
                                                 src="https://api.adorable.io/avatars/570x430/<?= $post->id ?>.png"
                                            //src="<?php $post->getImageUrl(570, 330) ?>"
                                            title="<?= $post->fullname ?>">
                                        </a>
                                        <div class="content">
                                            <h3 class="post-title">
                                                <a href="<?= $post->getViewUrl() ?>"
                                                   class="post-link">
                                                    <?= $post->fullname ?>
                                                </a>
                                            </h3>
                                            <div class="post-meta">
                                                <time class="post-date">
                                                    <?= $post->job ?>
                                                </time>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    </div>
                <?php endif; ?>
                <header class="post-header the-post-header cf">
                    <div class="featured"></div>
                    <h1 class="post-title-alt the-page-title"><?= __('Biz bilan bog\'lanish') ?></h1>
                </header>
                <div class="post-content description cf entry-content content-spacious-full">
                    <p>
                        <?= __('Ushbu {bot} orqali tahririyat bilan bog\'langan holda bizga taklif-mulohazalar va xabarlaringizni yuborishingiz mumkin.', ['bot' => \yii\helpers\Html::a(__('Telegram bot'), 'https://t.me/minbar_bot')]) ?>
                    </p>
                    <p>
                        <a class="button-link" href="https://t.me/minbar_bot">
                            <i class="ui-paper-plane"></i> <?= __('Xabar jo\'natish') ?>
                        </a>
                    </p>
                    <p>
                        <strong><?= __('Manzil') ?>
                            :</strong> <?= __('100031, Тошкент шаҳри, Яккасарой тумани, Миробод кўчаси, 14-уй') ?>
                        <br>
                        <strong><?= __('Email') ?>:</strong> <a
                                href="mailto:<?= getenv('CONTACT_EMAIL') ?>"><?= getenv('CONTACT_EMAIL') ?></a>
                    </p>
                </div>
            <?php endif; ?>
        </article>
    </div>
</div>