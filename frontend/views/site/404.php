<?php

/* @var $this View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use frontend\components\View;

$this->title = __('Sahifa mavjud emas');
?>
<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-area">
                    <main class="site-main">
                        <div class="_404">
                            <div class="_404-inner">
                                <h1 class="entry-title"><?= $this->title ?></h1>
                                <div class="entry-content">
                                    <?= __('Ushbu sahifa mavjud emas yoki vaqtinchalik tizimdan oʼchirilgan') ?>
                                </div>
                                <form method="get" class="search-form inline"
                                      action="<?= \yii\helpers\Url::to(['/search']) ?>">
                                    <input class="search-field inline-field"
                                           value="" name="q" required="required" type="search"
                                           placeholder="<?= __('Enter keyword...') ?>" autocomplete="off">
                                    <button type="submit" class="search-submit">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </form>
                                <br>
                                <br>
                                <div class="wp-block-button">
                                    <a class="wp-block-button__link alignleft"
                                       href="<?= Yii::$app->request->getReferrer() ?>">
                                        <?= __('Avvalgi sahifaga qaytish') ?></a>
                                    <a class="wp-block-button__link alignright"
                                       href="<?= Yii::$app->getHomeUrl() ?>">
                                        <?= __('Bosh sahifaga qaytish') ?></a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>