<?php
use common\components\Config;
use common\models\Admin;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title                   = $model->isNewRecord ? __('Create User') : $model->getFullname();
$this->params['breadcrumbs'][] = ['url' => ['user/index'], 'label' => __('Manage users')];
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="row">
        <div class="col col-md-8">
            <div class="panel">
                <?php $form = ActiveForm::begin(['enableAjaxValidation' => true,]); ?>
                <div class="panel-heading border ">
                    <h4><?= __('User Information') ?></h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col col-md-7">
                            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true,])->label() ?>
                        </div>
                        <div class="col col-md-5">
                            <div class="form-group">
                                <label class="control-label" for="user-fullname"><?= __('Type') ?></label>
                                <input type="button" class="form-control" value="<?= $model->authClient ? $model->authClient->source : __('Default')?>">

                                <div class="help-block"></div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-7">
                            <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                                'items'         => Admin::getStatusOptions(),
                                'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                            ]) ?>
                        </div>
                        <div class="col col-md-5">
                            <?= $form->field($model, 'language')->widget(ChosenSelect::className(), [
                                'items'         => Config::getLanguageOptions(),
                                'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-7">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col col-md-5">
                            <?= $form->field($model, 'telephone')->textInput(['maxlength' => true, 'class' => 'mobile-phone form-control']) ?>
                        </div>
                    </div>
                    <?php if ($model->isNewRecord): ?>
                        <div class="row">
                            <div class="col col-md-7">
                                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col col-md-5">
                                <?= $form->field($model, 'confirmation', ['labelOptions' => ['class' => 'invisible']])->passwordInput(['maxlength' => true])->label() ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php $label = '<label class="control-label cb-checkbox">' . Html::checkbox("User[change_password]", false, ['id' => 'change_password']) . ' ' . __('Change Password') . '</label>' ?>
                        <div class="row checkbo">
                            <div class="col col-md-7">
                                <?= $form->field($model, 'password', ['template' => "$label{input}\n{error}"])->passwordInput(['maxlength' => true, 'value' => '', 'disabled' => 'disabled', 'placeholder' => __('New Password')])->label($label) ?>
                            </div>
                            <div class="col col-md-5">
                                <?= $form->field($model, 'confirmation', ['template' => "<label class=\"control-label cb-checkbox\">&nbsp;</label>{input}\n{error}"])->passwordInput(['maxlength' => true, 'value' => '', 'disabled' => 'disabled', 'placeholder' => __('Password Confirmation')]) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="panel-footer text-right">
                    <?= Html::submitButton(__('Save'), ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

<?php
if (!$model->isNewRecord)
    $this->registerJs('
    $("#change_password").on("change", function () {
        $("input[name=\'User[password]\'],input[name=\'User[confirmation]\']").attr("disabled", !this.checked);
    });
')
?>