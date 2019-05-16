<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use backend\components\View;
use backend\widgets\AceEditorWidget;
use common\models\Comment;
use common\models\Page;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this View */
/* @var $model Comment */
?>

<div class="user-create">
    <div class="user-form">
        <?php $form = ActiveForm::begin(['id' => 'comment_form', 'action' => ['comment/index', 'save' => $model->id]]); ?>
        <div class="row">
            <div class="col col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <?= $form->field($model, 'text', [])->textarea(['rows' => 6]) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
