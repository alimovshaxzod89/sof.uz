<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/27/17
 * Time: 7:05 PM
 */

namespace frontend\widgets;


use common\models\Ad;
use common\models\Comment;
use common\models\Post;
use frontend\assets\CommentsAsset;
use frontend\assets\TextcompleteAsset;
use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\Widget;

class Comments extends Widget
{

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $view = $this->getView();
        CommentsAsset::register($view);
        TextcompleteAsset::register($view);
        echo Html::tag('div', '', $this->options);
        //$view->registerJs($this->getJs());
        $this->registerClientOptions('comments', $this->options['id']);
    }

    public function registerClientOptions($name, $id)
    {
        $js = "$(function() {
            var saveComment = function(data) {
    
                // Convert pings to human readable format
                $(data.pings).each(function(index, id) {
                    var user = usersArray.filter(function(user){return user.id == id})[0];
                    data.content = data.content.replace('@' + id, '@' + user.fullname);
                });
    
                return data;
            }
            $('#".$id."').comments(". Json::htmlEncode($this->clientOptions) .");
        });";
        $this->getView()->registerJs("$('#".$id."').comments(". Json::htmlEncode($this->clientOptions) .")");
    }

    protected function getJs()
    {
        $js = "$(function() {
            var saveComment = function(data) {
    
                // Convert pings to human readable format
                $(data.pings).each(function(index, id) {
                    var user = usersArray.filter(function(user){return user.id == id})[0];
                    data.content = data.content.replace('@' + id, '@' + user.fullname);
                });
    
                return data;
            }
            $('#".$this->options['id']."').comments({
                profilePictureURL: 'https://viima-app.s3.amazonaws.com/media/user_profiles/user-icon.png',
                currentUserId: 1,
                roundProfilePictures: true,
                getUsers: function(success, error) {
                    setTimeout(function() {
                        success(usersArray);
                    }, 500);
                },
                getComments: function(success, error) {
                    success(" . Json::htmlEncode($this->comments) . ");
                },
                postComment: function(data, success, error) {
                    setTimeout(function() {
                        success(saveComment(data));
                        console.log(data);
                    }, 500);
                },
                putComment: function(data, success, error) {
                    setTimeout(function() {
                        success(saveComment(data));
                    }, 500);
                },
                deleteComment: function(data, success, error) {
                    setTimeout(function() {
                        success();
                    }, 500);
                },
                upvoteComment: function(data, success, error) {
                    setTimeout(function() {
                        success(data);
                    }, 500);
                },
            });
        });";
        return $js;
    }
}