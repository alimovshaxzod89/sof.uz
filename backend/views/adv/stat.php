<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

use backend\components\View;
use common\models\Stat;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii2mod\chosen\ChosenSelect;

use kartik\daterange\DateRangePicker;

/* @var $this View */
/* @var $model common\models\Ad */

$this->title                   = $model->isNewRecord ? __('Ad Statistics') : $model->title;
$this->params['breadcrumbs'][] = ['url' => ['adv/index'], 'label' => __('Manage Advertising')];
$this->params['breadcrumbs'][] = $this->title;
$user                          = $this->context->_user();

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

?>
<?php Pjax::begin(['id' => 'admin-grid', 'timeout' => false, 'options' => ['data-pjax' => false], 'enablePushState' => false]) ?>
<div class="user-form">
    <div class="row">
        <div class="col col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row" id="data-grid-filters">
                        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => 1]]); ?>
                        <div class="col col-sm-3 col-md-4">
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
                                'items'         => array_merge([''], Stat::getGroupOptions()),
                                'options'       => [
                                    'data-placeholder' => __('Grouping'),
                                ],
                                'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                            ])->label(false) ?>
                        </div>
                        <div class="col col-sm-3 col-md-2">
                            <?= $form->field($searchModel, 'view', ['labelOptions' => ['class' => 'invisible']])->widget(ChosenSelect::className(), [
                                'items'         => array_merge([''], Stat::getViewOptions()),
                                'options'       => [
                                    'data-placeholder' => __('Type'),
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
                <div class="panel-body">
                    <canvas id="myChart" width="100%" height="50px"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs('initChart()')
?>

<script>
    function initChart() {
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: <?=json_encode($dataProvider)?>,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }

</script>

<?php Pjax::end() ?>


