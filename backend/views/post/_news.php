<?php

use common\models\Post;
use dosamigos\tinymce\TinyMce;
use yii\helpers\Url;
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
            "emoticons template paste textcolor colorpicker textpattern cw myembed",
        ],
        'image_title'             => true,
        'image_class_list'        => [
            ['title' => '', 'value' => ''],
            ['title' => 'Full', 'value' => 'size-full'],
            ['title' => 'Half Left', 'value' => 'size-half-left'],
            ['title' => 'Half Right', 'value' => 'size-half-right']
        ],
        'image_dimensions'        => false,
        'automatic_uploads'       => true,
        'extended_valid_elements' => "script[src|async|defer|type|charset]",
        'paste_data_images'       => true,
        'image_caption'           => true,
        'fix_list_elements'       => true,
        'image_advtab'            => false,
        'default_link_target'     => '_blank',
        'entity_encoding'         => 'raw',
        'init_instance_callback'  => new \yii\web\JsExpression("
            function(editor){
                editor.on('Change', function (e) {
                  initAutoSave();
                });
            }
        "),
        //"paste_word_valid_elements" => "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,blockquote,img,ul,ol,li,table,td,tr,thead,tbody,tfoot",
        'content_style'           => 'body {max-width: 768px; margin: 5px auto;}.mce-content-body img{width:98%; height:98%}figure.image{margin:0px;width:100%}.custom_card{padding:5px 20px;border:1px solid #e3e3e3;text-align:center;margin:10px 20px;}.cw-wrapper{display:block;padding:20px;background-color:#e3e3e3;}',
        'images_upload_url'       => Url::to(['file-storage/upload', 'type' => 'content-image', 'fileparam' => 'file']),
        'preview_url'             => Url::to('@frontendUrl/preview/' . $model->getId()),
        'toolbar1'                => "undo redo | styleselect blockquote superscript subscript cw | alignleft aligncenter alignright alignjustify | bullist numlist | link image table myembed| bold italic underline | code fullscreen",
    ],
    'options'       => ['rows' => 30],
]) ?>
