<?php

namespace backend\components\sharer;

use common\components\Config;
use Exception;
use Spatie\Emoji\Emoji;
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
        $domain      = \Yii::getAlias('@frontendUrl');
        $channelLink = getenv('CHANNEL_LINK');
        $link        = $post->getShortViewUrl();

        $hand   = Emoji::backhandIndexPointingRight();
        $text   = "<b>" . $post->getTranslation('title', Config::LANGUAGE_CYRILLIC) . "</b>\n\n" . "Батафсил: $link\n\nЭнг сўнгги хабарларга обуна бўлинг:\n\n$hand $channelLink";
        $static = \Yii::getAlias('@staticUrl/uploads');

        try {
            if ($this->_botApi->sendPhoto(
                $this->channelId,
                "{$static}/{$post->image['path']}",
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
        }

        \Yii::error('Post share failed');
        \Yii::endProfile('Finish sending post to telegram channel');
        return false;
    }
}