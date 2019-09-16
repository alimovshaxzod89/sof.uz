<?php
/**
 *
 */

use common\components\Config;

?>
<?php if (Config::isLatinCyrill()): ?>
    <?php if (Yii::$app->language == Config::LANGUAGE_UZBEK): ?>
        <div class="form-group">
            <a class="btn btn-default btn-block" href="<?= $link ?>">
                <i class="fa fa-refresh"></i>&nbsp; <?= __('Convert to cyrillic') ?>
            </a>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->language == Config::LANGUAGE_CYRILLIC): ?>
        <div class="form-group">
            <a class="btn btn-default btn-block" href="<?= $link ?>">
                <i class="fa fa-refresh"></i>&nbsp; <?= __('Convert to latin') ?>
            </a>
        </div>
    <?php endif; ?>
<?php endif; ?>
