<?php

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Stat;
use yii2mod\chosen\ChosenSelect;
use kartik\daterange\DateRangePicker;


$this->title                   = __('Muallif statistikasi');
$this->params['breadcrumbs'][] = ['url' => ['post/index'], 'label' => __('Manage Posts')];
$this->params['breadcrumbs'][] = $this->title;

$searchModel = new \common\models\Stat();

$data = $searchModel->getAdminStatistics(Yii::$app->request->post());

$this->registerJs("
    var ranges={
        '" . addslashes(__('Today')) . "': [moment(), moment()],
        '" . addslashes(__('Yesterday')) . "': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '" . addslashes(__('Last 7 days')) . "': [moment().subtract(6, 'days'), moment()],
        '" . addslashes(__('Last 30 days')) . "': [moment().subtract(29, 'days'), moment()],
        '" . addslashes(__('This Month')) . "': [moment().startOf('month'), moment().endOf('month')],
        '" . addslashes(__('Last Month')) . "': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    };
", \yii\web\View::POS_HEAD);

$art  = 0;
$news = 0;
$all  = 0;
?>

<?php Pjax::begin(['id' => 'post-grids', 'options' => ['data-pjax' => false], 'timeout' => false, 'enablePushState' => false]) ?>
<div class="panel panel-default data-grid">
    <div class="panel-heading">
        <div class="row" id="data-grid-filters">
            <?php $form = ActiveForm::begin(); ?>
            <div class="col col-md-4">
                <?= DateRangePicker::widget([
                                                'model'         => $searchModel,
                                                'attribute'     => 'range',
                                                'convertFormat' => true,
                                                'pluginOptions' => [
                                                    'timePicker'          => false,
                                                    'timePickerIncrement' => 30,
                                                    'ranges'              => new \yii\web\JsExpression('ranges'),
                                                    'locale'              => \common\components\Config::getDateRangeLocale(),
                                                ],
                                            ]); ?>
            </div>
            <div class="col col-sm-3 col-md-2">
                <?= $form->field($searchModel, 'group', ['labelOptions' => ['class' => 'invisible']])->widget(ChosenSelect::className(), [
                    'items'         => array_merge([''], Stat::getAuthorGroupOptions()),
                    'options'       => [
                        'data-placeholder' => __('Grouping'),
                    ],
                    'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                ])->label(false) ?>
            </div>
            <div class="col col-sm-3 col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> OK</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?= __('Sana') ?></th>
            <th><?= __('Muallif') ?></th>
            <th><?= __('Maqolalar') ?></th>
            <th><?= __('Yangiliklar') ?></th>
            <th><?= __('Barchasi') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $day): ?>
            <?php foreach ($day['auth'] as $id => $item):
                $art += $item['art'];
                $news += $item['news']; ?>
                <tr>
                    <td><?= $day['date'] ?></td>
                    <td><?= $item['author']->getFullName() ?></td>
                    <td><?= $item['art'] ?></td>
                    <td><?= $item['news'] ?></td>
                    <td><?= $item['all'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>
            <th colspan="2" align="right"><?= __('Jami') ?></th>
            <th><?= $art ?></th>
            <th><?= $news ?></th>
            <th><?= ($art + $news) ?></th>
        </tr>
        </tfoot>
    </table>
</div>

<?php Pjax::end() ?>
