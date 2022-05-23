<?php
/**
 * Created by PhpStorm.
 * User: shavkat
 * Date: 5/31/18
 * Time: 10:10 AM
 * @var $model Log
 */

use backend\widgets\AceEditorWidget;
use yii\widgets\ActiveForm;

$this->title                   = strip_tags($model->message);
$this->params['breadcrumbs'][] = ['url' => ['system/logs'], 'label' => __('System Logs')];
$this->params['breadcrumbs'][] = $this->title;

$data         = $model->getAttributes();

$model->ip    = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

?>
<?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'enableClientValidation' => true, 'validateOnSubmit' => false, 'options' => ['id' => 'post_form']]); ?>

<?= $form->field($model, 'ip')
         ->widget(AceEditorWidget::className(), ['options' => ['id' => 'dbg_content'], 'mode' => 'json', 'containerOptions' => ['style' => 'min-height:1000px']]) ?>
<?php ActiveForm::end(); ?>

