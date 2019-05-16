<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/* @var $this View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use frontend\components\View;

$this->title = $exception->getMessage();
?>

<div class="main-wrap">
    <div class="main wrap">

        <div class="ts-row cf">
            <div class="col-12 main-content cf">
                <div class="the-post the-page page-404 cf">
                    <div class="col-12 text-404 main-color">500</div>
                    <header class="post-title-alt">
                        <h1 class="main-heading"><?= __('Tizimda qandaydir xatolik yuz berdi :(') ?></h1>
                    </header>
                    <div class="post-content error-page row">

                        <div class="col-3"></div>
                        <div class="col-6 post-content text-center">
                            <p>
                                <?= __('Uzur, saytda nomalum xatolik yuz berdi. Tizim ayni kunlarda texnik ishlab chiqish bosqichida va ushbu xatolik  tez kunlarda bartaraf etiladi.') ?>
                            </p>
                            <ul class="links">
                                <li><a href="#" class="go-back"
                                       onclick="window.history.back();return false">
                                        <?= __('Avvalgi sahifaga qaytish') ?>
                                    </a>
                                </li>
                                <li><a href="<?= linkTo(['/']) ?>"><?= __('Bosh sahifaga qaytish') ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>