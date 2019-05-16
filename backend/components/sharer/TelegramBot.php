<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 11/24/17
 * Time: 10:18 AM
 */

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
        $domain = \Yii::getAlias('@frontendUrl');
        $hand   = Emoji::backhandIndexPointingRight();
        $text   = "<b>" . $post->getTranslation('title', Config::LANGUAGE_CYRILLIC) . "</b>\n\n" . "$hand {$domain}/{$post->short_id}\n\nКаналга қўшилинг:\n$hand @zarnews_uz";
        $static = \Yii::getAlias('@staticUrl/uploads');

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
        } else {
            \Yii::error('Post share failed');
        }
        \Yii::error('Post share failed');
        \Yii::endProfile('Finish sending post to telegram channel');
        return false;
    }
}