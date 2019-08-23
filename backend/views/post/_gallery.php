<?php

use backend\widgets\filekit\Upload;
use common\components\Config;
use common\models\Post;
use dosamigos\tinymce\TinyMce;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var $model Post
 * @var $form  ActiveForm
 */

?>


<?= $form->field($model, 'content')->widget(TinyMce::className(), [
    'language'      => 'ru',
    'clientOptions' => [
        'plugins'                 => [
            "advlist autolink lists link imagetools image charmap print hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern",
        ],
        'extended_valid_elements' => 'script[language|type|src]',
        'image_title'             => true,
        'image_class_list'        => [['title' => '', 'value' => ''], ['title' => 'Full', 'value' => 'size-full'], ['title' => 'Half Left', 'value' => 'size-half-left'], ['title' => 'Half Right', 'value' => 'size-half-right']],
        'image_dimensions'        => false,
        'automatic_uploads'       => true,
        'image_advtab'            => false,
        'image_caption'           => true,
        'default_link_target'     => '_blank',
        'init_instance_callback'  => new \yii\web\JsExpression("
            function(editor){
                editor.on('Change', function (e) {
                  initAutoSave();
                });
            }
        "),
        'content_style'           => 'body {max-width: 768px; margin: 5px auto;}.mce-content-body img{width:98%; height:98%}figure.image{margin:0px;width:100%}',
        'images_upload_url'       => Url::to(['file-storage/upload', 'type' => 'content-image', 'fileparam' => 'file']),
        'preview_url'             => Url::to('@frontendUrl/preview/' . $model->getId()),
        'toolbar1'                => "undo redo | styleselect blockquote | alignleft aligncenter alignright alignjustify | bullist numlist | emoticons insertfile link image media table | bold italic underline | code fullscreen",
    ],
    'options'       => ['rows' => 20],
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
