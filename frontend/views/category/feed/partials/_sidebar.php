<?php
/**
 * Created by PhpStorm.
 * User: shavkat
 * Date: 2/26/19
 * Time: 7:20 PM
 */
?>
<aside class="col-4 sidebar sidebar-left mb-45" data-sticky="1">
    <div class="inner">
        <ul>
            <li class="widget widget-posts widget-card no-margins-md">
                <ul class="posts cf large">
                    <li class="post cf card_step_title <?= $type == 'yangiliklar' ? 'active' : '' ?>">
                        <div class="content">
                            <a href="<?= $model->getViewUrl() ?>" class="post-title">
                                <?= $model->name ?>
                            </a>
                        </div>
                    </li>
                    <li class="post cf card_step_title <?= $type == 'minbarda' ? 'active' : '' ?>">
                        <div class="content">
                            <a href="<?= linkTo(['/minbarda']) ?>" class="post-title">
                                <?= __('Minbarda') ?>
                            </a>
                        </div>
                    </li>
                    <li class="post cf card_step_title <?= $type == 'ommabop' ? 'active' : '' ?>">
                        <div class="content">
                            <a href="<?= linkTo(['/ommabop']) ?>" class="post-title">
                                <?= __('Ommabop') ?>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <?php if ($middle = \frontend\widgets\Banner::widget(['place' => 'sidebar_middle'])): ?>
                <li class="widget widget-a-wrap no-940">
                    <?= $middle ?>
                </li>
            <?php endif ?>
        </ul>
    </div>
</aside>
