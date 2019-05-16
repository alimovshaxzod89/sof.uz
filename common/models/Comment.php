<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace common\models;


use DateTime;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class Comment
 * @property string    text
 * @property string    status
 * @property string    _user
 * @property string    _post
 * @property mixed     _parent
 * @property mixed     _history
 * @property Post      post
 * @property User      user
 * @property Comment[] replies
 * @property mixed     upvote_count
 * @property mixed     votes
 * @property mixed     pings
 * @property mixed     edited_at
 * @property mixed     is_approved
 * @package common\models
 */
class Comment extends MongoModel
{
    protected $_integerAttributes = ['upvote_count'];

    const STATUS_NEW      = 'new';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public $is_approved;

    public static function dataProvider($post, $limit = 1)
    {
        $query = self::find()
                     ->where(['status' => ['$in' => [self::STATUS_APPROVED, self::STATUS_NEW]], '_parent' => ['$eq' => false]])
                     ->andWhere(['_post' => $post])
                     ->addOrderBy(['created_at' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => Yii::$app->request->get('load', $limit),
                                                   ],
                                               ]);


        return $dataProvider;
    }

    public static function getStatusArray()
    {
        return [
            self::STATUS_NEW      => __('New'),
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_REJECTED => __('Rejected'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => call_user_func($this->getTimestampValue()),
            ],
        ];
    }

    public function getStatusLabel()
    {
        $status = self::getStatusArray();
        return isset($status[$this->status]) ? $status[$this->status] : $this->status;
    }

    public static function collectionName()
    {
        return 'comment';
    }

    public function attributes()
    {
        return [
            '_id',
            'text',
            'status',
            'votes',
            'upvote_count',
            'pings',
            '_post',
            '_user',
            '_parent',
            '_history',
            'edited_at',
            'created_at',
            'updated_at',
        ];
    }

    public function rules()
    {
        return [
            [['status'], 'in', 'range' => array_keys(self::getStatusArray())],
            [['text'], 'safe'],
            [['text'], 'string', 'max' => 4500, 'min' => '2'],
            [['text'], 'required'],
            [['_parent'], 'default', 'value' => false],
            [['search'], 'safe', 'on' => 'search'],
        ];
    }

    public function search($params)
    {
        $query = self::find();
        $query->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => 50,
                                                   ],
                                               ]);

        $this->load($params);
        if ($this->search) {
            $query->orFilterWhere(['like', 'text', $this->search]);
            $query->orFilterWhere(['like', '_user', $this->search]);
        }

        return $dataProvider;
    }

    public function afterDelete()
    {
        parent::afterDelete();
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['_id' => '_post']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['_id' => '_user']);
    }

    public function getReplies()
    {
        return $this->hasMany(Comment::className(), ['_parent' => 'id'])->addOrderBy(['created_at' => SORT_ASC]);
    }

    /**
     * @param Post $post
     * @param      $userId
     * @return array|Comment[]
     */
    public static function getAsArray(Post $post, $userId)
    {
        $all      = self::find()
                        ->where(['_post' => $post->getId()])
                        ->andWhere(['status' => ['$in' => [self::STATUS_APPROVED, self::STATUS_NEW]]])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->all();
        $comments = [];

        /** @var self[] $all */
        foreach ($all as $comment) {
            if ($comment->status == Comment::STATUS_NEW && $comment->_user != $userId)
                continue;
            $comments[] = $comment->getArray($userId);
        }

        return $comments;
    }

    public function getArray($userId)
    {
        return [
            'id'                      => $this->getId(),
            'parent'                  => $this->_parent ?: null,
            'created'                 => $this->getShortFormattedDate('created_at'),
            'modified'                => $this->getShortFormattedDate('edited_at'),
            'timestamp'               => $this->created_at->getTimestamp(),
            'modified_time'           => $this->edited_at->getTimestamp(),
            'content'                 => $this->text,
            'pings'                   => $this->pings,
            'creator'                 => $this->user->getId(),
            'fullname'                => $this->user->getFullname(),
            'profile_picture_url'     => $this->user->avatar_url,
            'created_by_admin'        => false,
            'created_by_current_user' => $userId == $this->user->getId(),
            'upvote_count'            => $this->upvote_count ?: 0,
            'user_has_upvoted'        => in_array($userId, $this->votes ?: []),
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->edited_at = call_user_func($this->getTimestampValue());
        }

        if (count($this->pings)) {
            $text = $this->text;
            foreach ($this->pings as $ping) {
                if ($user = User::findOne($ping)) {

                    $text = str_replace("@$ping", "@$user->fullname", $text);
                }
            }
            $this->text = $text;
        }

        if ($this->isAttributeChanged('text')) {
            $history = $this->_history;
            if (!is_array($history)) {
                $history = [];
            }
            $history[Yii::$app->formatter->asDatetime(time())] = $this->getOldAttribute('text');
            $this->_history                                    = $history;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function upVote($post, $user)
    {
        $userId = $user ?: $post['creator'];

        $votes = $this->votes;
        if (!isset($votes[$userId])) {
            $votes[$userId] = $userId;
        } else {
            unset($votes[$userId]);
        }

        $this->votes        = array_unique($votes);
        $this->upvote_count = count($votes);

        return $this->save();
    }

    public function beforeDelete()
    {
        foreach ($this->replies as $comment) {
            $comment->delete();
        }
        return parent::beforeDelete();
    }

    public function getShortFormattedDate($attribute)
    {
        if ($this->{$attribute} instanceof Timestamp) {
            $diff = time() - $this->{$attribute}->getTimestamp();

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
                $match_date->setTimestamp($this->{$attribute}->getTimestamp());
                $match_date->setTime(0, 0, 0);

                $diff     = $today->diff($match_date);
                $diffDays = (integer)$diff->format("%R%a");
                switch ($diffDays) {
                    case 0:
                        //today
                        return __('Bugun, {time}', ['time' => Yii::$app->formatter->asDate($this->{$attribute}->getTimestamp(), 'php:H:i')]);
                        break;
                    case -1:
                        //Yesterday
                        return __('Kecha, {time}', ['time' => Yii::$app->formatter->asDate($this->{$attribute}->getTimestamp(), 'php:H:i')]);
                        break;
                }

                return Yii::$app->formatter->asDate($this->{$attribute}->getTimestamp(), 'php:d/M, H:i');
            } elseif ($diff < 31536000) {
                return Yii::$app->formatter->asDate($this->{$attribute}->getTimestamp(), 'php:d/m, H:i');
            }
            return Yii::$app->formatter->asDate($this->{$attribute}->getTimestamp());
        }
        return Yii::$app->formatter->asDate($this->created_at->getTimestamp());
    }

    public function afterFind()
    {
        $this->is_approved = $this->status == self::STATUS_APPROVED;
        parent::afterFind(); // TODO: Change the autogenerated stub
    }
}