<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PostProvider;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:38 AM
 */

/**
 * @var $post PostProvider
 */
?>
<div class="sidebar__listen clickable-block">
    <p class="icon">
        <img src="<?= $this->getImageUrl('headset-icon.svg') ?>">
    </p>

    <p class="title">
        <?= $post->title ?>
    </p>

    <p class="continue">
        <a href="<?= $post->getViewUrl($post->category) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="7"
                 viewBox="0 0 19 22" fill="#fff">
                <defs>
                    <path id="rdjoa" d="M829 1095v-22l19 11z"></path>
                </defs>
                <g>
                    <g transform="translate(-829 -1073)">
                        <use xlink:href="#rdjoa"></use>
                    </g>
                </g>
            </svg>
            <span><?= __('Tinglash') ?></span>
        </a>
    </p>
</div>