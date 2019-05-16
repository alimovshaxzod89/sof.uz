<?php
use common\components\Config;
use common\models\Faq;
use common\models\Category;
use marqu3s\summernote\Summernote;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;



/* @var $this yii\web\View */
/* @var $model common\models\Faq */


$this->title                   = $model->isNewRecord ? __('Create Question') : $model->question;
$this->params['breadcrumbs'][] = ['url' => ['faq/index'], 'label' => __('Manage Questions')];
$this->params['breadcrumbs'][] = $this->title;
$user                          = $this->context->_user();

?>

<div class="user-create">
    <div class="user-form">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
        <div class="row">
            <div class="col col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?= __('Question Information') ?></h4>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'answer')->widget(Summernote::className(), []) ?>
                    </div>
                </div>
            </div>
            <div class="col col-md-3 page_settings">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><?= __('Settings') ?></h4>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                            'items'         => Faq::getStatusOptions(),
                            'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                        ]) ?>
                        <?= __('Category') ?>
                        <?php
                        echo $form->field($model, '_category')->hiddenInput(['id' => '_category'])->label(false);

                        echo FancytreeWidget::widget([
                            'options' => [
                                'checkbox'   => true,
                                'selectMode' => 2,
                                'source'     => Category::getCategoryTreeAsArray(explode(',', $model->_category),Config::get(Config::CONFIG_CATALOG_PRODUCT_ROOT)),
                                'extensions' => ['dnd'],
                                'select'     => new JsExpression('function(event, data) {
                                                                                    var selNodes = data.tree.getSelectedNodes();
                                                                                    var selKeys = $.map(selNodes, function(node){
                                                                                            return node.key;
                                                                                        });
                                                                                    $("#_category").val(selKeys.join(","));
                                                                                }'),
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="panel-footer">
                        <div class="text-right">
                            <?php if ($model->getId()): ?>
                                <?= Html::a(__('Delete'), ['question/delete', 'id' => $model->getId()], ['class' => 'btn btn-danger btn-delete']) ?>
                            <?php endif; ?>
                            <?= Html::submitButton(__('Save'), ['class' => 'btn btn-primary']) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
