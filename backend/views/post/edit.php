<?php

use backend\assets\TinyMceAsset;
use backend\components\View;
use backend\widgets\checkbo\CheckBo;
use backend\widgets\filekit\Upload;
use backend\widgets\media\EmbedMedia;
use common\components\Config;
use common\models\Admin;
use common\models\Blogger;
use common\models\Category;
use common\models\Post;
use dosamigos\selectize\SelectizeTextInput;
use trntv\yii\datetime\DateTimeWidget;
use wbraganca\fancytree\FancytreeWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this View */
/* @var $model common\models\Post */
/* @var $type string */
/* @var $user Admin */


$this->title                   = $model->getTitleView();
$this->params['breadcrumbs'][] = ['url' => ['post/index'], 'label' => __('Manage Posts')];
$this->params['breadcrumbs'][] = $this->title;
$user                          = $this->context->_user();


$label = Html::a('<i class="fa fa-external-link"></i>', $model->status == Post::STATUS_PUBLISHED ? $model->getFrontViewUrl() : $model->getFrontPreviewUrl(), ['data-pjax' => 0, 'class' => 'pull-right', 'target' => '_blank']);

?>
<div class="post-create <?= $locked || !$canEdit ? 'post_locked' : '' ?>">
    <div class="lock_area"></div>
    <div class="post-form">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'enableClientValidation' => true, 'validateOnSubmit' => false, 'options' => ['id' => 'post_form']]); ?>
        <div class="row">
            <div class="col col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?= __('Post Information') ?> </h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'placeholder' => __('Post Link')])->label(false) ?>
                        <?= $form->field($model, 'info', ['template' => '{label}{input}{error}<span class="counter"></span>'])->textarea(['maxlength' => true, 'rows' => 4, 'placeholder' => __('Short Information')])->label(false) ?>
                        <?php if ($file = $model->getFileUrl('audio')) {
                            echo EmbedMedia::widget([
                                                        'file'    => $file,
                                                        'type'    => 'audio',
                                                        'width'   => 760,
                                                        'height'  => 40,
                                                        'title'   => $model->audio['name'],
                                                        //'modal' => true,
                                                        'options' => ['class' => 'mb15'],
                                                    ]);
                            echo "<p>$model->audio_duration_formatted</p>";
                        } ?>
                        <?= $this->renderFile("@backend/views/post/_$type.php", ['form' => $form, 'model' => $model]) ?>
                        <?= $form->field($model, '_tags')->widget(SelectizeTextInput::className(), [
                            'options' => [],
                            'loadUrl' => Url::to(['post/tag']),

                            'clientOptions' => [
                                'maxItems'     => 100,
                                'maxOptions'   => 10,
                                'hideSelected' => true,
                                'create'       => true,
                                'valueField'   => 'v',
                                'labelField'   => 't',
                                'searchField'  => 's',
                                'options'      => $model->getTagsData(),
                                'plugins'      => ['remove_button', 'drag_drop'],
                            ],
                        ]) ?>
                        <?= $form->field($model, 'content_source')->textInput(['maxlength' => true, 'placeholder' => __('Content Source')])->label(__('Content Source')) ?>
                    </div>
                </div>
            </div>
            <div class="col col-md-3 " id="post_settings">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><?= __('Settings') . ' ' . $label ?></h4>
                    </div>
                    <div class="panel-body">


                        <div class="btn-group btn-group-justified">
                            <?php if ($user->canAccessToResource('post/share')): ?>
                                <div class="btn-group" role="group">
                                    <button type="button" id="telegram" class="btn btn-primary"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i>"
                                            onclick="shareTo(this, 'telegram')"><i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php if ($user->canAccessToResource('post/notification')): ?>
                                <?php if ($model->isPushNotificationExpired() && Config::get(Config::CONFIG_PUSH_TO_ANDROID)): ?>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-success"
                                           href="<?= Url::to(['post/notification', 'id' => $model->getId()]) ?>"><i
                                                    class="fa fa-android"></i></a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <hr>


                        <?= $form->field($model, 'is_main')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Is Main')) ?>
                        <?= $form->field($model, 'is_bbc')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('BBC Post')) ?>
                        <?= $form->field($model, 'is_tagged')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Is Tagged')) ?>
                        <?= $form->field($model, 'template')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Hide Sidebar')) ?>

                        <table style="width: 100%">
                            <?php if ($model->created_at): ?>
                                <tr>
                                    <td><label class="control-label"><?= __('Created At') ?></label></td>
                                    <td class="text-right"><?= Yii::$app->formatter->asDatetime($model->created_at->getTimestamp()) ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($model->published_on instanceof \MongoDB\BSON\Timestamp): ?>
                                <tr>
                                    <td><label class="control-label"><?= __('Published On') ?></label></td>
                                    <td class="text-right"><?= Yii::$app->formatter->asDatetime($model->published_on->getTimestamp()) ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($model->updated_on): ?>
                                <tr>
                                    <td><label class="control-label"><?= __('Updated On') ?></label></td>
                                    <td class="text-right"><?= Yii::$app->formatter->asDatetime($model->updated_on->getTimestamp()) ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        <hr>

                        <?= $this->renderFile('@backend/views/layouts/_convert.php', ['link' => Url::to(['post/edit', 'id' => $model->getId(), 'convert' => 1])]) ?>
                        <?php
                        $authors = Blogger::getArrayOptions();
                        ?>
                        <?php if (count($authors)): ?>
                            <?= $form->field($model, '_author')->widget(ChosenSelect::className(), [
                                'items'         => $authors,
                                'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                            ])->label() ?>
                        <?php endif; ?>

                        <?php if ($user->canAccessToResource('post/creator')): ?>
                            <?= $form->field($model, '_creator')->widget(ChosenSelect::className(), [
                                'items'         => Admin::getArrayOptions(),
                                'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                            ])->label() ?>
                        <?php endif; ?>

                        <?php if ($model->getId() && $model->status != Post::STATUS_IN_TRASH): ?>
                            <?php if ($user->canAccessToResource('post/publish')): ?>
                                <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                                    'items'         => Post::getStatusArray(),
                                    'options'       => ['onchange' => 'statusChanged(this.value)'],
                                    'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                                ]) ?>


                                <?php $time = $model->getPublishedOnSeconds() ?>

                                <div id="widget_published_on_wrapper"
                                     class="form-group  <?= !($model->status == Post::STATUS_PUBLISHED || $model->status == Post::STATUS_DRAFT) ? 'hidden' : '' ?>">

                                    <?= $form->field($model, 'published_on', [
                                        'options' => [
                                            'value'    => $model->getPublishedOnSeconds(),
                                            'id'       => 'hidden_published_on',
                                            'disabled' => !($model->status == Post::STATUS_PUBLISHED || $model->status == Post::STATUS_DRAFT),
                                        ],
                                    ])->hiddenInput(['id' => 'published_on_time', 'value' => $time]) ?>
                                    <?= DateTimeWidget::widget([
                                                                   'id'               => 'widget_published_on',
                                                                   'locale'           => Yii::$app->language == Config::LANGUAGE_UZBEK ? 'uz-latn' : (Yii::$app->language == Config::LANGUAGE_CYRILLIC ? 'uz' : 'ru'),
                                                                   'model'            => $model,
                                                                   'name'             => 'date_published_on_time',
                                                                   'value'            => $time ? Yii::$app->formatter->asDatetime($time, 'dd.MM.yyyy, HH:mm') : null,
                                                                   'containerOptions' => [],
                                                                   'clientEvents'     => [
                                                                       'dp.change' => new JsExpression('function(d){
                                                               time = d.date._d.getTime() / 1000;
                                                               $("#published_on_time").val(Math.round(time))
                                                            }'),
                                                                   ],
                                                               ]) ?>
                                </div>

                                <div id="widget_auto_publish_wrapper"
                                     class="form-group <?= $model->status != Post::STATUS_AUTO_PUBLISH ? 'hidden' : '' ?>">
                                    <?php $time = $model->getAutoPublishTimeSeconds() ?>
                                    <?= $form->field($model, 'auto_publish_time', [
                                        'options' => [
                                            'id'       => 'hidden_auto_publish',
                                            'disabled' => $model->status != Post::STATUS_AUTO_PUBLISH ? 'disabled' : false,
                                        ],
                                    ])->hiddenInput(['id' => 'publish_time', 'value' => $time]) ?>

                                    <?= DateTimeWidget::widget([
                                                                   'locale'           => Yii::$app->language == Config::LANGUAGE_UZBEK ? 'uz-latn' : (Yii::$app->language == Config::LANGUAGE_CYRILLIC ? 'uz' : 'ru'),
                                                                   'model'            => $model,
                                                                   'name'             => 'auto_publish_time',
                                                                   'value'            => $time ? Yii::$app->formatter->asDatetime($time, 'dd.MM.yyyy, HH:mm') : null,
                                                                   'containerOptions' => ['class' => ''],
                                                                   'clientEvents'     => [
                                                                       'dp.change' => new JsExpression('function(d){
                                                               time = d.date._d.getTime() / 1000;
                                                               $("#publish_time").val(Math.round(time))
                                                            }'),
                                                                   ],
                                                               ]) ?>
                                </div>
                            <?php else: ?>
                                <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                                    'items'         => Post::getStatusArray(),
                                    'options'       => ['disabled' => true],
                                    'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                                ]) ?>
                            <?php endif; ?>
                        <?php endif; ?>


                        <div class="form-group">
                            <?= $form->field($model, '_categories', ['options' => ['class' => '']])->hiddenInput(['id' => '_categories']); ?>
                            <?php echo FancytreeWidget::widget([
                                                                   'options' => [
                                                                       'checkbox'   => true,
                                                                       'selectMode' => 2,
                                                                       'source'     => Category::getCategoryTreeAsArray(explode(",", $model->_categories), Config::getRootCatalog()),
                                                                       'extensions' => ['dnd'],
                                                                       'select'     => new JsExpression('function(event, data) {
                                                                                    var selNodes = data.tree.getSelectedNodes();
                                                                                    var selKeys = $.map(selNodes, function(node){
                                                                                            return node.key;
                                                                                        });
                                                                                    $("#_categories").val(selKeys.join(","));
                                                                                    console.log(data);
                                                                                }'),
                                                                   ],
                                                               ]); ?>
                        </div>
                        <?php if (0): ?>
                            <?= $form->field($model, 'audio')->widget(Upload::className(), [
                                'url'              => ['file-storage/upload', 'type' => 'audio'],
                                'acceptFileTypes'  => new JsExpression('/(\.|\/)(mp3|wav)$/i'),
                                'sortable'         => true,
                                'maxFileSize'      => 50 * 1024 * 1024, // 15 MiB
                                'maxNumberOfFiles' => 1,
                                'multiple'         => false,
                                'clientOptions'    => [],
                            ])->label(); ?>
                        <?php endif; ?>

                        <?= $form->field($model, 'image')->widget(
                            Upload::className(),
                            [
                                'url'              => ['file-storage/upload', 'type' => 'post-image'],
                                'acceptFileTypes'  => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
                                'sortable'         => true,
                                'maxFileSize'      => 10 * 1024 * 1024, // 10 MiB
                                'maxNumberOfFiles' => 1,
                                'multiple'         => false,
                                'useCaption'       => true,
                                'languages'        => Config::getLanguageCodes(),
                                'clientOptions'    => [],
                            ]
                        )->label(); ?>

                        <?= $form->field($model, 'hide_image')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Hide Image')) ?>

                        <?php if ($user->canAccessToResource('system/trash')): ?>
                            <?= $form->field($model, 'short_id')->textInput()->label(__('Short URL')) ?>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer">
                        <div class="pull-left">
                            <?php if ($model->getId() && $model->status != Post::STATUS_IN_TRASH && $this->_user()->canAccessToResource('post/trash')): ?>
                                <?= Html::a("<i class='fa fa-trash-o'></i>", ['post/trash', 'id' => $model->getId()], ['class' => 'btn btn-danger btn-trash', 'data-confirm' => __('Are you sure move to trash?')]) ?>
                            <?php endif; ?>

                        </div>
                        <div class="pull-right">
                            <?php if ($model->status != Post::STATUS_IN_TRASH): ?>
                                <?= Html::submitButton('&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> ' . __('Save') . '&nbsp;&nbsp;&nbsp;', ['class' => 'btn btn-success']) ?>
                            <?php endif; ?>
                            <?php if ($model->status == Post::STATUS_IN_TRASH && $this->_user()->canAccessToResource('post/restore')): ?>
                                <?= Html::a('&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> ' . __('Restore'), ['post/restore', 'id' => $model->getId()], ['class' => 'btn btn-success btn-trash', 'data-confirm' => __('Are you sure to restore?')]) ?>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
TinyMceAsset::register($this);
$this->registerJs('initPostEditor();');
?>
<script>
    var focused = false;
    var postUpdated =<?=$model->updated_on ? $model->updated_on->getTimestamp() : 0?>;
    var postEditor = '<?=(string)$model->_editor?>';
    var postLocked =<?=$locked ? 'true' : 'false'?>;
    var canEdit =<?=$canEdit ? 'true' : 'false'?>;

    function statusChanged(status) {
        if (status == 'auto_publish') {
            $('#widget_auto_publish_wrapper').removeClass('hidden');
            $('#hidden_auto_publish').attr('disabled', false);
        } else {
            $('#widget_auto_publish_wrapper').addClass('hidden');
            $('#hidden_auto_publish').attr('disabled', 'disabled');
        }

        if (status == 'published' || status == 'draft') {
            $('#widget_published_on_wrapper').removeClass('hidden');
            $('#hidden_published_on').attr('disabled', false);
        } else {
            $('#widget_published_on_wrapper').addClass('hidden');
            $('#hidden_published_on').attr('disabled', true);
        }
    }

    function initPostEditor() {
        setCounter();
        initStatus();

        $('#post-info').on('keydown', setCounter);
        $('#post-info').on('blur', setCounter);
        $('#post-info').on('focus', setCounter);

        $('#post_form input, #post_form textarea').on("keydown", function (e) {
            initAutoSave();
        });

        $('#post_form input, #post_form select, #post_form textarea').on('change', function (e) {
            initAutoSave();
        });

        $('#post-title').blur(function () {
            if ($('#post-url').val().length < 2) $('#post-url').val(convertToSlug($(this).val()));
        });

        $('#post_settings').theiaStickySidebar({
            additionalMarginTop: 70,
            additionalMarginBottom: 20
        });

        var $loading = $('#loader').hide();

        $(document).ajaxStart(function () {
            $loading.hide();
        });
    }

    var timeout;

    function initStatus() {
        timeout = setTimeout(function () {
            tinymce.triggerSave();
            var form = $('#post_form');

            $.post(
                form.attr('action') + "?status=1",
                form.serialize(),
                function (data, status) {
                    if (postUpdated != data.updated || postEditor != data.editor) {
                        if (confirm('<?=addslashes(__('Post has changed, do you want to reload it?'))?>')) {
                            document.location.href = document.location.href;
                        } else {
                            postUpdated = data.updated;
                            initStatus();
                        }
                    } else {
                        initStatus();
                    }
                }
            );
        }, 5000);
    }

    function initAutoSave() {
        if (!postLocked && canEdit) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                tinymce.triggerSave();
                var form = $('#post_form');

                $.post(
                    form.attr('action') + "?save=1&updated_on=" + postUpdated,
                    form.serialize(),
                    function (data, status) {
                        postUpdated = data.updated;
                        postEditor = data.editor;

                        if (data.reload != undefined && data.reload == 1) {
                            if (confirm('<?=addslashes(__('Post has changed, do you want to reload it?'))?>')) {
                                document.location.href = document.location.href;
                            }
                        }
                    }
                );
            }, 3000);
        }
    }

    function switchState(input) {
        if (input.is(':checked')) {
        }
    }

    function shareTo(button, social) {
        var btn = $(button);
        var data = {};
        console.log(btn.html());
        btn.button('loading');
        data.sharer = social;
        $.post(
            '<?= Url::to(['post/share', 'id' => $model->getId()]) ?>',
            data,
            function (res) {
                btn.button("reset");
            })
    }

    function setCounter() {
        $('#post-info').parent().find('.counter').text($('#post-info').val().length);
    }
</script>
