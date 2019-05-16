<?php
use frontend\models\PostProvider;


?>
<div class="inner">
    <ul>
        <li id="bunyad-posts-widget-4" class="widget widget-posts">
            <h5 class="widget-title">
                <span><?= __('Ommabop') ?></span>
            </h5>
            <ul class="posts cf large">
                <?php foreach (PostProvider::getPopularPosts(6, $exclude) as $i => $item): ?>
                    <?php $img = $item->getImage(124, 103) ?>
                    <li class="post cf">
                        <?php if ($i == -1): ?>
                            <a href="<?= $url = $item->getViewUrl() ?>" class="image-link">
                                <img src="<?= $img ?>"
                                     class="size-contentberg-thumb-alt lazyloaded"
                                     title="<?= $item->title ?>">
                            </a>
                        <?php endif; ?>
                        <div class="content">
                            <?php if ($item->is_bbc): ?>
                                <span class="bbc-tag">BBC</span>
                            <?php endif; ?>
                            <a href="<?= $item->getViewUrl() ?>" class="post-title" title="<?= $item->title ?>">
                                <?= $item->title ?>
                            </a>
                            <div class="post-meta post-meta-a">
                                <time class="post-date">
                                    <?= $item->getShortFormattedDate() ?>
                                </time>
                                <span class="meta-sep"></span>
                                <span class="meta-item read-time"><?= $item->getReadMinLabel() ?></span>

                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li class="widget widget-a-wrap">
            <?= \frontend\widgets\Banner::widget(['place' => 'sidebar_middle']) ?>
        </li>
        <li class="widget widget-subscribe">
            <div class="fields">
                <p class="message">
                    <?= __('Maqolalarni Telegram kanalimizda kuzatib boring') ?>
                </p>
                <p class="text-center">
                    <a class="action-link tg-color" href="<?= getenv('TG_CHANNEL') ?>"><i
                            class="ui-paper-plane" target="_blank"></i> <?= __('Subscribe') ?></a>
                </p>
                <p></p>
            </div>
        </li>
    </ul>
</div>