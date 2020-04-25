<?php

use backend\assets\TinyMceAsset;
use backend\components\View;
use backend\widgets\checkbo\CheckBo;
use backend\widgets\filekit\Upload;
use common\components\Config;
use common\models\Admin;
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

use dosamigos\tinymce\TinyMce;

/* @var $this View */
/* @var $model common\models\Post */
/* @var $type string */
/* @var $user Admin */


$this->title                   = $model->getTitleView();
$this->params['breadcrumbs'][] = ['url' => ['post/index'], 'label' => __('Manage Posts')];
$this->params['breadcrumbs'][] = $this->title;
$user                          = $this->context->_user();

$label = Html::a('<i class="fa fa-external-link"></i>', $model->getPreviewUrl(), ['data-pjax' => 0, 'class' => 'pull-right', 'target' => '_blank']);
?>
<div class="post-create <?= $locked || !$canEdit ? '' : '' ?>">
    <div class="lock_area"></div>
    <div class="post-form">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'enableClientValidation' => true, 'validateOnSubmit' => true, 'options' => ['id' => 'post_form']]); ?>
        <?= $form->errorSummary($model) ?>
        <div class="row">
            <div class="col col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?= __('Post Information') . ' ' . $label ?> </h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'placeholder' => __('Post Link')])->label(false) ?>
                        <?= $form->field($model, 'info', ['template' => '{label}{input}{error}<span class="counter"></span>'])->textarea(['maxlength' => true, 'rows' => 4, 'placeholder' => __('Short Information')])->label(false) ?>


                        <?= $form->field($model, 'content')->widget(TinyMce::className(), [
                            'language'      => 'ru',
                            'clientOptions' => [
                                'plugins'                       => [
                                    "advlist autolink lists link imagetools image charmap print hr anchor pagebreak",
                                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                                    "insertdatetime media nonbreaking save table contextmenu directionality",
                                    "emoticons template paste textcolor colorpicker textpattern cw myembed paste",
                                ],
                                'image_title'                   => true,
                                'image_class_list'              => [
                                    ['title' => '', 'value' => ''],
                                    ['title' => 'Full', 'value' => 'size-full'],
                                    ['title' => 'Half Left', 'value' => 'size-half-left'],
                                    ['title' => 'Half Right', 'value' => 'size-half-right']
                                ],
                                'image_dimensions'              => false,
                                'automatic_uploads'             => true,
                                'extended_valid_elements'       => "script[src|async|defer|type|charset]",
                                'paste_data_images'             => true,
                                'image_caption'                 => true,
                                'fix_list_elements'             => true,
                                'image_advtab'                  => false,
                                'default_link_target'           => '_blank',
                                'entity_encoding'               => 'raw',
                                'init_instance_callback'        => new \yii\web\JsExpression("
                                    function(editor){
                                        editor.on('Change', function (e) {
                                          initAutoSave();
                                        });
                                    }
                                "),
                                'paste_webkit_styles'           => 'none',
                                'paste_remove_styles_if_webkit' => true,
                                'paste_enable_default_filters'  => true,
                                "paste_word_valid_elements"     => "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,blockquote,img,ul,ol,li,table,td,tr,thead,tbody,tfoot",
                                'content_style'                 => 'body {max-width: 768px; margin: 5px auto;}.mce-content-body img{width:98%; height:98%}figure.image{margin:0px;width:100%}.custom_card{padding:5px 20px;border:1px solid #e3e3e3;text-align:center;margin:10px 20px;}.cw-wrapper{display:block;padding:20px;background-color:#e3e3e3;}',
                                'images_upload_url'             => Url::to(['file-storage/upload', 'type' => 'content-image', 'fileparam' => 'file']),
                                'preview_url'                   => Url::to('@frontendUrl/preview/' . $model->getId()),
                                'toolbar1'                      => "undo redo | styleselect blockquote superscript subscript cw | alignleft aligncenter alignright alignjustify | bullist numlist | link image table myembed| bold italic underline | code fullscreen",
                            ],
                            'options'       => ['rows' => 30],
                        ]) ?>


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


                        <?= $form->field($model, 'gallery')->widget(Upload::className(), [
                            'url'              => ['file-storage/upload', 'type' => 'gallery-image'],
                            'acceptFileTypes'  => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
                            'maxFileSize'      => 15 * 1024 * 1024, // 10 MiB
                            'multiple'         => true,
                            'sortable'         => true,
                            'maxNumberOfFiles' => 100,
                            'languages'        => Config::getLanguageCodes(),
                            'clientOptions'    => [],
                            'value'            => $model->gallery,
                        ]) ?>

                    </div>
                </div>
            </div>
            <div class="col col-md-3 " id="post_settings">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><?= __('Settings') ?></h4>
                    </div>
                    <div class="panel-body">
                        <table style="width: 100%">
                            <?php if ($model->created_at): ?>
                                <tr>
                                    <td><label class="control-label"><?= __('Created At') ?></label></td>
                                    <td class="text-right"><?= Yii::$app->formatter->asDatetime($model->created_at->getTimestamp()) ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($model->published_on): ?>
                                <tr>
                                    <td><label class="control-label"><?= __('Published On') ?></label></td>
                                    <td class="text-right"><?= Yii::$app->formatter->asDatetime($model->published_on->getTimestamp()) ?></td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($model->auto_publish_time): ?>
                                <tr>
                                    <td><label class="control-label"><?= __('Auto Published On') ?></label></td>
                                    <td class="text-right"><?= Yii::$app->formatter->asDatetime($model->auto_publish_time->getTimestamp()) ?></td>
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
                        <?php if ($user->canAccessToResource('post/schedule')): ?>
                            <div class="form-group">
                                <div class="btn-group btn-group-justified">
                                    <div class="btn-group" role="group">
                                        <button type="button" id="twitter" class="btn btn-info"
                                                data-loading-text="<i class='fa fa-spin fa-spinner'></i>"
                                                onclick="shareTo(this, 'twitter')"><i class="fa fa-twitter"></i>
                                        </button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button type="button" id="telegram" class="btn btn-primary"
                                                data-loading-text="<i class='fa fa-spin fa-spinner'></i>"
                                                onclick="shareTo(this, 'telegram')"><i class="fa fa-paper-plane"></i>
                                        </button>
                                    </div>

                                    <?php if ($model->isPushNotificationExpired()): ?>
                                        <div class="btn-group" role="group">
                                            <button type="button" id="android" class="btn btn-success"
                                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i>"
                                                    onclick="shareTo(this, 'android')"><i class="fa fa-android"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <a href="<?= Url::to(['post/schedule', 'p' => $model->id]) ?>"
                                   id="schedule" class="btn btn-default btn-block" data-pjax="0">
                                    <i class="fa fa-plus"></i> <?= __('Schedule') ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <hr>

                        <?= $form->field($model, 'is_main')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Is Main')) ?>
                        <?= $form->field($model, 'has_video')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Videoxabar')) ?>
                        <?= $form->field($model, 'has_gallery')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Fotoxabar')) ?>
                        <?= $form->field($model, 'is_sidebar')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Hide Sidebar')) ?>
                        <?= $form->field($model, 'is_ad')->widget(CheckBo::className(), ['type' => 'switch'], ['onchange' => 'showAdTime()'])->label(__('PR Maqola')) ?>

                        <div id="ad_time" style="<?= !$model->is_ad ? 'display:none' : '' ?>">
                            <?= $form->field($model, 'ad_time')->textInput(['placeholder' => __('Reklama muddati')])->label(false) ?>
                        </div>
                        <hr>

                        <?= $this->renderFile('@backend/views/layouts/_convert.php', ['link' => Url::to(['post/edit', 'id' => $model->getId(), 'convert' => 1])]) ?>
                        <?php $authors = Admin::getArrayOptions(); ?>
                        <?php if (count($authors)): ?>
                            <?= $form->field($model, '_author')
                                     ->widget(ChosenSelect::class, [
                                         'items'         => $authors,
                                         'pluginOptions' => [
                                             'width'                 => '100%',
                                             'allow_single_deselect' => true,
                                             'disable_search'        => true
                                         ],
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
                                <?= $form->field($model, 'status')
                                         ->widget(ChosenSelect::className(), [
                                             'items'         => Post::getStatusArray(),
                                             'options'       => ['onchange' => 'statusChanged(this.value)'],
                                             'pluginOptions' => [
                                                 'width'                 => '100%',
                                                 'allow_single_deselect' => true,
                                                 'disable_search'        => true
                                             ],
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
                                                                           $("#publish_time").val(Math.round(time));
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
                        <?= $form->field($model, 'image')
                                 ->widget(Upload::className(), [
                                     'url'              => ['file-storage/upload', 'type' => 'post-image'],
                                     'acceptFileTypes'  => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
                                     'sortable'         => true,
                                     'maxFileSize'      => 10 * 1024 * 1024, // 10 MiB
                                     'maxNumberOfFiles' => 1,
                                     'multiple'         => false,
                                     'useCaption'       => true,
                                     'languages'        => Config::getLanguageCodes(),
                                     'clientOptions'    => [],
                                 ])->label(); ?>

                        <?= $form->field($model, 'hide_image')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Hide Image')) ?>

                        <?php if ($user->canAccessToResource('system/trash')): ?>
                            <?= $form->field($model, 'short_id')->textInput()->label(__('Short URL')) ?>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer">
                        <div class="pull-left">
                            <?php if ($model->getId() && $model->status != Post::STATUS_IN_TRASH && $this->_user()->canAccessToResource('post/trash')): ?>
                                <?= Html::a("<i class='fa fa-trash-o'></i>", ['post/trash', 'id' => $model->getId()], ['class' => 'btn btn-danger', 'data-confirm' => __('Are you sure move to trash?')]) ?>
                            <?php endif; ?>
                            <?php if ($model->status != Post::STATUS_IN_TRASH && $user->canAccessToResource('post/publish')): ?>
                                <?= Html::submitButton("<i class='fa fa-save'></i>", ['class' => 'btn btn-success']) ?>
                            <?php endif; ?>
                        </div>
                        <div class="pull-right">
                            <?php if ($model->status != Post::STATUS_IN_TRASH): ?>
                                <?= Html::submitButton('<i class="fa fa-check"></i> ' . __('Chop etish'), ['class' => 'btn btn-info', 'name' => 'publish', 'value' => 1]) ?>

                            <?php endif; ?>
                            <?php if ($model->status == Post::STATUS_IN_TRASH && $this->_user()->canAccessToResource('post/restore')): ?>
                                <?= Html::a('&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> ' . __('Restore'), [
                                    'post/restore',
                                    'id' => $model->getId()
                                ], ['class' => 'btn btn-success', 'data-confirm' => __('Are you sure to restore?')]) ?>
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
    var postUpdated =<?=$model->updated_on instanceof \MongoDB\BSON\Timestamp ? $model->updated_on->getTimestamp() : 0?>;
    var postEditor = '<?=(string)$model->_author?>';
    var postLocked =<?=$locked ? 'true' : 'false'?>;
    var canEdit =<?=$canEdit ? 'true' : 'false'?>;

    function showAdTime() {
        if ($('#post-is_ad').is(':checked')) {
            $('#ad_time').slideDown();
        } else {
            $('#ad_time').slideUp();
        }
    }

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
        //initStatus();

        $('#post-info').on('keydown', setCounter);
        $('#post-info').on('blur', setCounter);
        $('#post-info').on('focus', setCounter);
        $('#post-is_ad').on('change', showAdTime);

        $('#post_form input, #post_form textarea').on("keydown", function (e) {
            initAutoSave();
        });

        $('#post_form input, #post_form select, #post_form textarea').on('change', function (e) {
            initAutoSave();
        });

        $('#post-title').blur(function () {
            if ($('#post-slug').val().length < 2) $('#post-slug').val(convertToSlug($(this).val()));
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
        /*timeout = setTimeout(function () {
            tinymce.triggerSave();
            var form = $('#post_form');

            $.post(
                form.attr('action') + "?status=1",
                form.serialize(),
                function (data, status) {
                    console.log(postUpdated !== data.updated);
                    console.log(data);
                    if (postEditor !== data.author || postUpdated !== data.updated) {
                        if (confirm('<?php //echo addslashes(__('Post has changed, do you want to reload it?'))?>')) {
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
        }, 5000);*/
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
                        postEditor = data.author;

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
        btn.button('loading');
        data.sharer = social;
        $.post(
            '<?= Url::to(['post/schedule', 'p' => $model->getId()]) ?>',
            data,
            function (res) {
                btn.button("reset");
                console.log(res);
            })
    }

    function setCounter() {
        $('#post-info').parent().find('.counter').text($('#post-info').val().length);
    }

    function checkAutoPublishStatus() {
        return $('#post-status').val() == 'auto_publish';
    }
</script>
