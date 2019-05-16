<?php

/* @var $this View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use frontend\components\View;

$this->title = __('Sahifa mavjud emas');
?>

<div class="main-wrap">
    <div class="main wrap">

        <div class="ts-row cf">
            <div class="col-12 main-content cf">
                <div class="the-post the-page page-404 cf">
                    <div class="col-12 text-404 main-color">404</div>
                    <header class="post-title-alt">
                        <h1 class="main-heading"><?= $this->title  ?></h1>
                    </header>
                    <div class="post-content error-page row">

                        <div class="col-3"></div>
                        <div class="col-6 post-content text-center">
                            <p>
                                <?= __('We\'re sorry, but we can\'t find the page you were looking for. It\'s probably some thing we\'ve done wrong but now we know about it and we\'ll try to fix it. In the meantime, try one of these options:') ?>
                            </p>
                            <ul class="links">
                                <li><a href="#" class="go-back"
                                       onclick="window.history.back();return false">
                                        <?= __('Avvalgi sahifaga qaytish') ?>
                                    </a>
                                </li>
                                <li><a href="<?= linkTo(['/']) ?>"><?= __('Bosh sahifaga qaytish') ?></a></li>
                            </ul>
                            <form method="get" class="search-form" action="<?= linkTo(['/search']) ?>">

                                <span class="screen-reader-text"><?= __('Search for:') ?></span>
                                <input type="search"
                                       class="search-field input"
                                       placeholder="<?= __('Qidiruv...') ?>"
                                       name="q"
                                       title="<?= __('Search for:') ?>">

                                <button type="submit" class="search-submit"><i class="ui-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
