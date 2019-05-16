<?php

namespace console\controllers;


use common\components\Config;
use common\models\Comment;
use common\models\Poll;
use common\models\Post;
use yii\console\Controller;

class GeneratorController extends Controller
{
    /**
     * This command polling fake votes
     * @param $id
     */
    public function actionVote($id = false)
    {
        /**
         * @var $poll Poll
         */
        if ($id) {
            $poll    = Poll::findOne(new \MongoId($id));
            $answers = count($poll->answers);
            for ($i = 0; $i < $answers; $i++) {
                $poll->upVote($i);
                $poll->upVote(rand(0, $i));
                $poll->upVote(rand(0, $answers - 1));
            }
        } else {
            $polls = Poll::findAll(['status' => ['$ne' => Poll::STATUS_DISABLE]]);
            foreach ($polls as $poll) {
                $answers = count($poll->answers);
                for ($i = 0; $i < $answers; $i++) {
                    $poll->upVote($i);
                    $poll->upVote(rand(0, $i));
                    $poll->upVote(rand(0, $answers - 1));
                }
            }
        }
    }

    /**
     * This command post fill any attributes fake data
     */
    public function actionPost()
    {
        $posts = Post::findAll(['status' => Post::STATUS_PUBLISHED]);
        foreach ($posts as $post) {
            $post->views = mt_rand(100, 10000);
            $post->save();
            $count = mt_rand(1, 10);
            for ($i = 0; $i < $count; $i++):
                $comment         = new Comment();
                $comment->_post  = $post->id;
                $comment->text   = 'Dolores et saepe sapiente odio recusandae id ut accusamus eum perspiciatis harum et.';
                $comment->status = Comment::STATUS_APPROVED;
                $comment->save();
            endfor;
        }
    }

    public function actionPostImg()
    {
        /**
         * @var $post Post
         */
        $posts = Post::find()->all();
        foreach ($posts as $post) {
            $content = $post->getTranslation('content', Config::LANGUAGE_CYRILLIC);

            $post->setTranslation('content', $this->fixHtppsImage($content), Config::LANGUAGE_CYRILLIC);

            $content = $post->getTranslation('content', Config::LANGUAGE_UZBEK);

            $post->setTranslation('content', $this->fixHtppsImage($content), Config::LANGUAGE_UZBEK);

            if ($post->updateAttributes(['_translations' => $post->_translations])) {
                echo $post->title . PHP_EOL;
            }

        }
    }

    private function fixHtppsImage($content)
    {
        $content = mb_ereg_replace('http://static.xabar.uz/', 'https://static.xabar.uz/', $content);
        $content = mb_ereg_replace('http://www.xabar.uz/', 'https://www.xabar.uz/', $content);

        return $content;
    }

    public function actionMobileDb()
    {

    }

}
