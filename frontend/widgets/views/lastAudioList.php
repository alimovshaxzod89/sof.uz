<?php

use frontend\models\PostProvider;
use frontend\widgets\LastAudioList;

/**
 * @var $this    LastAudioList
 * @var $news    PostProvider[]
 * @var $audios  PostProvider[]
 * @var $photo   PostProvider
 */
?>
<?php if ($title != null): ?>
    <div class="latest__news-audio-title">
        <h2><?= $title ? $title : __('Audio xabarlar') ?></h2>
    </div>
<?php endif; ?>
<?php if ($player): ?>
    <div class="audio__player" id="audio_player">
        <div class="audio__player-controls controls-top">
            <a href="#" id="audio_prev"><i class="icon play-prev-icon"></i></a>
            <a href="#" id="audio_play" class="player-play-btn "><i class="icon play-icon"></i><i
                    class="icon play-pause-icon"></i></a>
            <a href="#" id="audio_next"><i class="icon play-next-icon"></i></a>
        </div><!-- End of audio__player-controls-->

        <div class="audio__player-progress-bar" id="audio_progress_parent">
            <div class="fill">
                <a href="#" class="progress" id="audio_progress" style="width: 50%;"></a>
            </div>
        </div>
        <!-- End of audio__Player-progress-bar-->

        <div class="audio__player-controls controls-bottom">
            <a href="#" id="audio_share"><i class="icon share-icon"></i></a>

            <div class="audio__player-volume-controls auto">
                <a href="#" id="audio_volume"><i class="icon play-volume-icon"></i><i
                        class="icon play-volume-off-icon"></i></a>

                <div class="audio__player-volume-progress-bar" id="audio_volume_bar_parent">
                    <div class="fill">
                        <a href="#" class="progress" id="audio_volume_bar" style="width: 50%;"></a>
                    </div>
                </div>
            </div>
            <a href="#" id="audio_download"><i class="icon download-icon"></i></a>
        </div><!-- End of audio__player-controls-->
    </div><!-- End of audio__player-->
<?php endif; ?>
<div class="news__items <?= count($items) == 1 ? 'hidden' : '' ?>" >
    <?php if ($player && count($items) > 1): ?>
        <a href="<?= linkTo(['/audio']) ?>"><?= __('Barcha audioxabarlar') ?></a>
    <?php endif; ?>

    <?php if (count($items)): ?>
        <?php foreach ($items as $n => $audio): ?>
            <?php if ($audioLink = $audio->getFileUrl('audio')): ?>
                <div class="news__item ">
                        <span class="count">
                            <?= $n + 1 ?>
                            <a href="#"><i class="icon play-playlist-pause-icon"></i>
                            </a>
                        </span>
                    <a href="<?=$audio->getViewUrl($audio->category)?>" id=""
                       data-pos="<?= $n ?>"
                       data-id="<?= $audio->getId() ?>"
                       class="audio_track track_<?= $n ?>"
                       data-img="<?= $audio->getImage(36, 36) ?>"
                       data-track="<?= $audioLink ?>">
                        <?= $audio->title ?>
                    </a>
                    <span class="date-time"><?= $audio->audio_duration_formatted ?></span>
                </div><!-- End of news__item-->
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div><!-- End of news__items-->
