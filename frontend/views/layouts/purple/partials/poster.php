<?php if(file_exists(Yii::getAlias("@static/poster.png"))): ?>

<!---- Poster  -->
<div class="poster">
    <div class="addvert"></div>
    <div class="context">
        <div class="p-text">
            <h4><?= __('Ҳар доим хабардор бўлинг!') ?></h4>
            <p><?= __('carzone.uz мобил иловаларини кўчириб олинг
                        ва барча янгиликлар сиз билан') ?></p>
        </div>
        <div class="google-play"></div>
    </div>
</div>

<style>
    .poster {
        background-image: url('<?= Yii::getAlias("@staticUrl/poster.png") ?>') !important;
    }
</style>

<?php endif; ?>