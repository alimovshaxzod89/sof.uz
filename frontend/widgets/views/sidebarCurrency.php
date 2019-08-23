<?php

use common\models\Currency;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * Created by PhpStorm.
 * Date: 12/17/17
 * Time: 12:45 AM
 * @var $rates Currency[]
 */
?>
<?php Pjax::begin(['enablePushState' => false, 'timeout' => 10000, 'options' => ['class' => 'sidebar__rate']]) ?>
    <h2 class="sidebar__rate-title"><?= __('Valyuta kurslari') ?></h2>
<?php if (count($rates)): ?>
    <?php $all = Yii::$app->request->get('call', false) ?>
    <?php foreach ($rates as $rate): ?>
        <?php foreach ($all ? Currency::getCurrencies() : Currency::$rates as $att): ?>
            <?php $ch = $rate->{$att . '_ch'}; ?>
            <div class="row-info">
                <span class="currency"><?= $att ?></span>
                <span class="value"><?= Yii::$app->formatter->asDecimal($rate->$att, 2) ?></span>
                <span
                    class="value-rate <?= $ch >= 0 ? 'is_up' : 'is_down' ?>"><?= ($ch > 0 ? '+' : '') . Yii::$app->formatter->asDecimal($ch, 2) ?></span>
            </div><!-- End of row-info-->
        <?php endforeach; ?>
        <div class="sidebar__rate-status">
            <?= $rate->getRateDateFormatted() ?>
        </div><!-- End of sidebar__rate-status-->
    <?php endforeach; ?>
    <?php if (!$all): ?>
        <p class="continue"><a href="<?= Url::current(['call' => 1]) ?>"><?= __('Barchasi') ?></a></p>
    <?php endif; ?>
<?php else: ?>
    <code><?= __('Valyuta kurslari haqida ma`lumot topilmadi') ?></code>
<?php endif; ?>
<?php Pjax::end() ?>