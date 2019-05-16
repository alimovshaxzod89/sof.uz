<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Weather;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var $weather Weather
 */

?>
<?php Pjax::begin(['enablePushState' => false, 'timeout' => 10000, 'options' => ['class' => 'sidebar__weather']]) ?>
    <div class="sidebar__weather-title">
        <a href="#" class="dropdown">
            <?= __(Weather::getCityOptions()[$this->context->city]) ?>
            <i class="icon arrow-down-dropdown-icon"></i>
        </a>

        <div class="controls">
            <a class="prev"
               href="<?= Url::current(['wd' => $this->context->day - 2, 'wc' => $this->context->city]) ?>">
                <i class="icon carousel-prev-icon"></i>
            </a>
            <a class="next "
               href="<?= Url::current(['wd' => $this->context->day + 2, 'wc' => $this->context->city]) ?>">
                <i class="icon carousel-next-icon"></i></a>
        </div>
    </div><!-- End of sidebar__weather-title-->
    <div class="sidebar__weather-cities">
        <ul>
            <?php foreach (Weather::getCityOptions() as $code => $label): ?>
                <li <?= $this->context->city == $code ? 'class="is_active"' : '' ?>>
                    <a href="<?= Url::current(['wc' => $code]) ?>"><?= __($label) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="sidebar__weather-body">
        <?php foreach ($this->context->weathers as $weather): ?>
            <div class="day <?= date('d') == date('d', $weather->date) ? 'is_active' : '' ?>">
                <div class="day-media">
                    <img class="weather__status" src="<?= $this->getImageUrl('weather/' . $weather->getIcon()) ?>"
                         alt="<?= $weather->info ?>">
                </div>

                <div class="day-feel"><?= __($weather->info) ?></div>

                <div class="day-degree"><span><?= $weather->getTemp() ?></span></div>

                <div class="day-name">
                    <?php if ($weather->day == Weather::getCurrentDay()): ?>
                        <?= __('Bugun{br} {date}', ['date' => Yii::$app->formatter->asDatetime($weather->date, "php:d-F"), 'br' => '<br>']) ?>
                    <?php else: ?>
                        <?= str_replace('#', '<br>', Yii::$app->formatter->asDatetime($weather->date, "php:l # d-F")) ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div><!-- End of sidebar__weather-body-->
<?php Pjax::end() ?>