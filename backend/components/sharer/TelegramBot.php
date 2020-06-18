<?php

namespace backend\components\sharer;

use common\components\Config;
use Exception;
use TelegramBot\Api\BotApi;
use yii\base\InvalidArgumentException;

class TelegramBot extends BaseShare
{
    /**
     * @var string
     */
    public $channelId;
    /**
     * @var BotApi
     */
    protected $_botApi;

    public function init()
    {
        if (empty($this->accessToken)) {
            throw new Exception('Bot token cannot be empty');
        }
        $this->_botApi = new BotApi($this->accessToken);
    }

    public function publish($post)
    {
        if (empty($this->channelId)) {
            throw new InvalidArgumentException('Property `$channelId` must be set');
        }
        \Yii::beginProfile('Sending post to telegram channel');
        $domain = \Yii::getAlias('@frontendUrl');
        $channelLink = getenv('CHANNEL_LINK');
        $link = $post->getShortViewUrl();

        $hand = "\u{1F449}";
        $title = $post->getTranslation('title', Config::LANGUAGE_CYRILLIC);
        $info = $post->tg_info ? trim($post->getTranslation('info', Config::LANGUAGE_CYRILLIC)) . "\n\n" : '';

        $text = "<b>$title</b>\n\n{$info}" . "Батафсил: $link\n\n<b>Энг сўнгги хабарларга обуна бўлинг:</b> $hand \n$channelLink";
        $photo = $post->getFileUrl('image', true);

        try {
            echo $photo . PHP_EOL;
            if ($this->_botApi->sendPhoto(
                $this->channelId,
                $photo ? $photo : null,
                $text,
                null,
                null,
                false,
                'html'
            )) {
                \Yii::info("Post shared to {$this->channelId} telegram channel successfully");
                return true;
            }
        } catch (Exception $e) {
            \Yii::error($e->getMessage());

            echo $e->getMessage() . PHP_EOL;
            echo $e->getTraceAsString() . PHP_EOL;
        }

        \Yii::error('Post share failed');
        \Yii::endProfile('Finish sending post to telegram channel');
        return false;
    }
}