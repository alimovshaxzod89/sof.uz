<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace common\models;


use common\components\Config;
use DateTime;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\StringHelper;

/**
 * Class Comment
 * @property string     question
 * @property string     content
 * @property string     status
 * @property string     expire_time
 * @property string[]   answers
 * @property PollItem[] items
 * @property PollVote[] userVotes
 * @property integer    votes
 * @package common\models
 */
class Poll extends MongoModel
{
    protected $_translatedAttributes = ['question', 'answers','content'];
    protected $_integerAttributes    = ['votes', 'expire_time'];

    const STATUS_ENABLE  = 'enable';
    const STATUS_EXPIRE  = 'expire';
    const STATUS_DISABLE = 'disable';

    public static function getStatusArray()
    {
        return [
            self::STATUS_ENABLE  => __('Enable'),
            self::STATUS_EXPIRE  => __('Expire'),
            self::STATUS_DISABLE => __('Disable'),
        ];
    }

    public function getStatusLabel()
    {
        $status = self::getStatusArray();
        return isset($status[$this->status]) ? $status[$this->status] : $this->status;
    }

    public static function collectionName()
    {
        return 'poll';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'question',
            'content',
            'answers',
            'expire_time',
            'status',
            'votes',
            'items',
        ]);
    }

    public function rules()
    {
        return [
            [['status'], 'in', 'range' => array_keys(self::getStatusArray())],
            [['question', 'answers', 'expire_time'], 'required'],
            [['content'], 'string', 'max' => 6000],
            [['question'], 'string', 'max' => 500],
            [['search'], 'safe', 'on' => 'search'],
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => 30,
                                                   ],
                                               ]);

        $this->load($params);

        if ($this->search) {
            $query->orFilterWhere(['_translations.question_uz' => ['$regex' => $this->search, '$options' => 'si']]);
            $query->orFilterWhere(['_translations.question_oz' => ['$regex' => $this->search, '$options' => 'si']]);
            $query->orFilterWhere(['_translations.question_ru' => ['$regex' => $this->search, '$options' => 'si']]);
        }

        return $dataProvider;
    }

    private $_oldAnswers;

    public function afterFind()
    {
        if (!$this->votes) {
            $this->votes = 0;
        }

        parent::afterFind();

        $this->_oldAnswers = $this->answers;
    }

    public function updatePoll()
    {
        if (Yii::$app->language == Config::LANGUAGE_UZBEK) {
            foreach ($this->_translatedAttributes as $attribute) {
                $this->$attribute = $this->convertLatinQuotas($this->getAttribute($attribute));
            }
        }

        if (count($this->_oldAnswers) > count($this->answers)) {
            //todo something
        }
        return $this->save();
    }

    public function getShortTitle()
    {
        $question = strip_tags($this->question);
        return StringHelper::truncateWords($question, 5);
    }

    public function resetVotes()
    {
        return $this->updateAttributes(['votes' => 0, 'items' => '']);
    }


    public function getShortFormattedDate()
    {
        if ($this->created_at instanceof Timestamp) {
            $diff = time() - $this->created_at->getTimestamp();

            if ($diff < 300) {
                return __('Hozirgina');
            } elseif ($diff < 3600) {
                return __('{minute} minut avval', ['minute' => round($diff / 60)]);
            } elseif ($diff < 3600 * 3) {
                return __('{hour} soat avval', ['hour' => round($diff / 3600)]);
            } elseif ($diff < 86400) {
                $today = new DateTime();
                $today->setTime(0, 0, 0);

                $match_date = new DateTime();
                $match_date->setTimestamp($this->created_at->getTimestamp());
                $match_date->setTime(0, 0, 0);

                $diff     = $today->diff($match_date);
                $diffDays = (integer)$diff->format("%R%a");
                switch ($diffDays) {
                    case 0:
                        //today
                        return __('Bugun, {time}', ['time' => Yii::$app->formatter->asDate($this->created_at->getTimestamp(), 'php:H:i')]);
                        break;
                    case -1:
                        //Yesterday
                        return __('Kecha, {time}', ['time' => Yii::$app->formatter->asDate($this->created_at->getTimestamp(), 'php:H:i')]);
                        break;
                }

                return Yii::$app->formatter->asDate($this->created_at->getTimestamp(), 'php:d/M, H:i');
            } elseif ($diff < 31536000) {
                return Yii::$app->formatter->asDate($this->created_at->getTimestamp(), 'php:d/m, H:i');
            }
            return Yii::$app->formatter->asDate($this->created_at->getTimestamp());
        }

        return Yii::$app->formatter->asDate($this->created_at->getTimestamp());
    }

    public function upVote($itemPosition, $userId = false)
    {
        $items = $this->getAllItems();
        if (isset($items[$itemPosition])) {
            $this->votes++;
            $items[$itemPosition]->upVote();

            foreach ($items as &$item) {
                $item->updatePercent($this->votes);
            }
            $this->updateAttributes(['votes' => $this->votes, 'items' => serialize($items)]);

            if ($userId) {
                $answer        = new PollVote();
                $answer->_user = $userId;
                $answer->_poll = $this->id;
                $answer->vote  = $itemPosition;
                $answer->save();
            }
        }
    }

    /**
     * @return PollItem[]
     */
    public function getAllItems()
    {
        /**
         * @var $items PollItem[]
         */
        $items = @unserialize($this->items);

        if ($items == null || !is_array($items)) {
            $items = [];
        }

        foreach ($this->answers as $position => $answer) {
            if (isset($items[$position])) {
                $items[$position]->answer = $answer;
                $items[$position]->active = true;
            } else {
                $items[$position] = new PollItem($answer);
            }
        }

        return $items;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUserVotes()
    {
        return $this->hasMany(PollVote::className(), ['_poll' => '_id']);
    }

    public function hasUserVoted($userId)
    {
        return PollVote::find()->where(['_user' => $userId, '_poll' => $this->id])->exists();
    }
}


