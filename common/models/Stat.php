<?php

namespace common\models;

use DateTime;
use MongoDB\BSON\Timestamp;
use Yii;

/**
 * Class Comment
 * @property string question
 * @property string status
 * @property string expire_time
 * @property string[] answers
 * @property PollItem[] items
 * @property integer votes
 * @package common\models
 */
class Stat extends MongoModel
{
    const TYPE_AD_CLICK = "ac";
    const TYPE_AD_VIEW = "av";
    const TYPE_POST_VIEW = 'pv';
    const VIEW_VIEW = 'view';
    const VIEW_CLICK = 'click';
    const VIEW_BOTH = 'both';
    const GROUP_HOUR = 'hour';
    const GROUP_DAY = 'day';
    const GROUP_AUTHOR = 'author';
    const GROUP_MONTH = 'month';
    public    $group;
    public    $range;
    public    $view;
    protected $_integerAttributes = ['count', 'day', 'month', 'year'];

    public static function getGroupOptions()
    {
        return [
            self::GROUP_HOUR  => __('Hourly'),
            self::GROUP_DAY   => __('Daily'),
            self::GROUP_MONTH => __('Monthly'),
        ];
    }

    public static function getAuthorGroupOptions()
    {
        return [
            self::GROUP_AUTHOR => __('Author'),
            self::GROUP_DAY    => __('Daily'),
            self::GROUP_MONTH  => __('Monthly'),
        ];
    }

    public static function getViewOptions()
    {
        return [
            self::VIEW_VIEW  => __('View'),
            self::VIEW_CLICK => __('Click'),
            self::VIEW_BOTH  => __('View/Click'),
        ];
    }

    public static function registerPostView(Post $post, $count = 1)
    {
        $date = new \DateTime();

        $key = [
            'type'  => self::TYPE_POST_VIEW,
            'model' => $post->_id,
            'hour'  => (int)$date->format('H'),
            'day'   => (int)$date->format('d'),
            'month' => (int)$date->format('m'),
            'year'  => (int)$date->format('Y'),
        ];

        if ($stat = self::find()->where($key)->one()) {
            return $stat->updateCounters(['count' => $count]);
        } else {
            $key['count'] = $count;
            $key['time']  = (int)$date->format('U');

            return boolval(self::getConnection()
                               ->getCollection(self::collectionName())
                               ->insert($key));
        }
    }

    public static function collectionName()
    {
        return 'stat';
    }

    public static function registerAdView(Ad $ad)
    {
        $date = new \DateTime();

        $key = [
            'type'  => self::TYPE_AD_VIEW,
            'model' => $ad->_id,
            'hour'  => (int)$date->format('H'),
            'day'   => (int)$date->format('d'),
            'month' => (int)$date->format('m'),
            'year'  => (int)$date->format('Y'),
        ];

        if ($stat = self::find()->where($key)->one()) {
            return $stat->updateCounters(['count' => 1]);
        } else {
            $key['count'] = 1;
            $key['time']  = (int)$date->format('U');

            return boolval(self::getConnection()
                               ->getCollection(self::collectionName())
                               ->insert($key));
        }
    }

    public static function registerAdClick(Ad $ad)
    {
        $date = new \DateTime();

        $key = [
            'type'  => self::TYPE_AD_CLICK,
            'model' => $ad->_id,
            'hour'  => (int)$date->format('H'),
            'day'   => (int)$date->format('d'),
            'month' => (int)$date->format('m'),
            'year'  => (int)$date->format('Y'),
        ];

        if ($stat = self::find()->where($key)->one()) {
            return $stat->updateCounters(['count' => 1]);
        } else {
            $key['count'] = 1;
            $key['time']  = (int)$date->format('U');

            return boolval(self::getConnection()
                               ->getCollection(self::collectionName())
                               ->insert($key));
        }
    }

    public static function indexAdViewsAll()
    {
        echo "indexAdViewsAll===================\n";
        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate([
                                      ['$match' => [
                                          'time' => ['$gt' => 0],
                                          'type' => ['$eq' => self::TYPE_AD_VIEW],
                                      ]],
                                      array('$group' => array(
                                          '_id'   => '$model',
                                          'count' => ['$sum' => '$count'],
                                      )),
                                  ]);

        foreach ($result as $item) {
            Ad::updateAll(['views' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    public static function indexAdClicksAll()
    {
        echo "indexAdClicksAll===================\n";
        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate([
                                      ['$match' => [
                                          'time' => ['$gt' => 0],
                                          'type' => ['$eq' => self::TYPE_AD_CLICK],
                                      ]],
                                      array('$group' => array(
                                          '_id'   => '$model',
                                          'count' => ['$sum' => '$count'],
                                      )),
                                  ]);

        foreach ($result as $item) {
            Ad::updateAll(['clicks' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    public static function indexPostViewsReset()
    {
        Post::updateAll([
                            'views_l3d'   => 0,
                            'views_l7d'   => 0,
                            'views_l30d'  => 0,
                            'views_today' => 0,
                        ]);
    }

    public static function indexPostViewsAll()
    {
        echo "indexPostViewsAll===================\n";
        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate([
                                      ['$match' => ['time' => ['$gt' => 0]]],
                                      ['$group' => [
                                          '_id'   => '$model',
                                          'count' => ['$sum' => '$count'],
                                      ]],
                                  ]);

        foreach ($result as $item) {
            Post::updateAll(['views' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    public static function indexPostViewsL3D()
    {
        echo "indexPostViewL3D===================\n";
        $date = new \DateTime();
        $time = (int)$date->format('U')
            - 3 * 24 * 3600
            - ((int)$date->format('h')) * 3600;

        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate([
                                      ['$match' => ['time' => ['$gt' => $time]]],
                                      ['$group' => [
                                          '_id'   => '$model',
                                          'count' => ['$sum' => '$count'],
                                      ]],
                                  ]);

        foreach ($result as $item) {
            Post::updateAll(['views_l3d' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    public static function indexPostViewsL7D()
    {
        echo "indexPostViewL7D===================\n";
        $date = new \DateTime();
        $time = (int)$date->format('U')
            - 7 * 24 * 3600
            - ((int)$date->format('h')) * 3600;

        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate([
                                      ['$match' => ['time' => ['$gt' => $time]]],
                                      ['$group' => [
                                          '_id'   => '$model',
                                          'count' => ['$sum' => '$count'],
                                      ]],
                                  ]);

        foreach ($result as $item) {
            Post::updateAll(['views_l7d' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    public static function indexPostViewsL30D()
    {
        echo "indexPostViewL30D===================\n";

        $date = new \DateTime();
        $time = (int)$date->format('U')
            - 30 * 24 * 3600
            - ((int)$date->format('h')) * 3600;

        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate([
                                      ['$match' => ['time' => ['$gt' => $time]]],
                                      ['$group' => [
                                          '_id'   => '$model',
                                          'count' => ['$sum' => '$count'],
                                      ]],
                                  ]);

        foreach ($result as $item) {
            Post::updateAll(['views_l30d' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    public static function indexPostViewsToday()
    {
        echo "indexPostViewToday===================\n";
        $date = new \DateTime();
        $time = (int)$date->format('U')
            - ((int)$date->format('h')) * 3600;


        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate([
                                      ['$match' => ['time' => ['$gt' => $time]]],
                                      ['$group' => [
                                          '_id'   => '$model',
                                          'count' => ['$sum' => '$count'],
                                      ]],
                                  ]);

        foreach ($result as $item) {
            Post::updateAll(['views_today' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    public function rules()
    {
        return [
            [['group', 'range', 'view'], 'safe'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'type',
            'model',
            'hour',
            'day',
            'month',
            'year',
            'count',
        ]);
    }

    public function getAdStatistics(Ad $model, $params)
    {
        $this->load($params);

        $data = [
            'labels'   => [],
            'datasets' => [
                [
                    'data'            => [],
                    'label'           => __("View"),
                    'backgroundColor' => ['rgba(255, 99, 132, 0.2)'],
                    'borderColor'     => ['rgba(255,99,132,1)'],
                    'borderWidth'     => 1,
                ],
                [
                    'data'            => [],
                    'label'           => __("Click"),
                    'backgroundColor' => ['rgba(153, 102, 255, 0.2)'],
                    'borderColor'     => ['rgba(54, 162, 235, 1)'],
                    'borderWidth'     => 1,
                ],
            ],
        ];

        if (!$this->view) $this->view = self::VIEW_VIEW;
        if (!$this->group) $this->group = 'day';


        $query = self::find()
                     ->where([
                                 'model' => $model->_id,
                             ])
                     ->orderBy(['time' => SORT_ASC])
                     ->asArray();

        if ($this->range) {
            $ranges = explode(' / ', preg_replace('!\s+!', ' ', $this->range));
            if (count($ranges) == 2) {
                list($from, $to) = $ranges;
                if ($from && $to) {
                    if ($fromDate = DateTime::createFromFormat('d-m-Y H:i:s', $from . ':59')) {

                    } else if ($fromDate = DateTime::createFromFormat('d-m-Y H:i:s', $from)) {

                    }

                    if ($toDate = DateTime::createFromFormat('d-m-Y H:i:s', $to . ':59')) {

                    } else if ($toDate = DateTime::createFromFormat('d-m-Y H:i:s', $to)) {

                    }

                    if ($fromDate && $toDate) {
                        $query->andFilterWhere([
                                                   'time' => [
                                                       '$gt' => $fromDate->getTimestamp(),// * 1000,
                                                       '$lt' => $toDate->getTimestamp(),// * 1000,
                                                   ],
                                               ]);
                    }
                }
            }
            //print_r($query->all());die;
        }


        if ($this->view == self::VIEW_CLICK) {
            $query->andFilterWhere(
                ['type' => self::TYPE_AD_CLICK]
            );
        } else if ($this->view == self::VIEW_VIEW) {
            $query->andFilterWhere(
                ['type' => self::TYPE_AD_VIEW]
            );
        }

        $labels = [];
        $view   = [];
        $click  = [];


        foreach ($query->all() as $i => $item) {
            if ($this->group == self::GROUP_DAY) {
                $day   = Yii::$app->formatter->asDate($item['time'], 'php:d/m/Y');
                $label = Yii::$app->formatter->asDate($item['time'], 'php:d/m');
            } elseif ($this->group == self::GROUP_HOUR) {
                $day = Yii::$app->formatter->asDate($item['time'], 'php:d/m/Y H');

                if ($item['hour'] == 0) {
                    $label = Yii::$app->formatter->asDate($item['time'], 'php:d/m');
                } else {
                    $label = Yii::$app->formatter->asDate($item['time'], 'H');
                }
            } else if ($this->group == self::GROUP_MONTH) {
                $day   = Yii::$app->formatter->asDate($item['time'], 'php:m/Y');
                $label = Yii::$app->formatter->asDate($item['time'], 'php:F, Y');
            }

            $labels[$day] = $label;

            if (!isset($view[$day])) $view[$day] = 0;
            if (!isset($click[$day])) $click[$day] = 0;

            if ($item['type'] == self::TYPE_AD_CLICK) {
                $click[$day] += $item['count'];
            } else {
                $view[$day] += $item['count'];
            }

        }

        $data['labels']              = array_values($labels);
        $data['datasets'][0]['data'] = array_values($view);
        $data['datasets'][1]['data'] = array_values($click);

        if ($this->view == self::VIEW_VIEW) {
            array_pop($data['datasets']);
        } else if ($this->view == self::VIEW_CLICK) {
            array_shift($data['datasets']);
        }

        return $data;
    }


    public function getAdminStatistics($params = [])
    {
        $this->load($params);

        if (!$this->group) $this->group = self::GROUP_AUTHOR;

        $query = Post::find()
                     ->select(['_categories', '_creator', '_id', 'published_on'])
                     ->where([
                                 'status' => Post::STATUS_PUBLISHED,
                             ])
                     ->orderBy(['published_on' => SORT_DESC]);

        if ($this->range) {
            $ranges = explode(' / ', preg_replace('!\s+!', ' ', $this->range));
            if (count($ranges) == 2) {
                list($from, $to) = $ranges;
                if ($from && $to) {
                    if ($fromDate = DateTime::createFromFormat('d-m-Y H:i:s', $from . ':59')) {

                    } else if ($fromDate = DateTime::createFromFormat('d-m-Y H:i:s', $from)) {

                    }

                    if ($toDate = DateTime::createFromFormat('d-m-Y H:i:s', $to . ':59')) {

                    } else if ($toDate = DateTime::createFromFormat('d-m-Y H:i:s', $to)) {

                    }

                    if ($fromDate && $toDate) {
                        $query->andFilterWhere([
                                                   'published_on' => [
                                                       '$gt' => new Timestamp(1, $fromDate->getTimestamp()),// * 1000,
                                                       '$lt' => new Timestamp(1, $toDate->getTimestamp()),// * 1000,
                                                   ],
                                               ]);
                    }
                }
            }
        }

        $result = [];
        foreach ($query->all() as $i => $item) {
            $id = (string)$item->_creator;
            if ($id) {
                if ($this->group == self::GROUP_DAY) {
                    $day   = Yii::$app->formatter->asDate($item->published_on->getTimestamp(), 'php:d/m/Y');
                    $label = Yii::$app->formatter->asDate($item->published_on->getTimestamp(), 'php:d/m/Y');
                } else if ($this->group == self::GROUP_MONTH) {
                    $day   = Yii::$app->formatter->asDate($item->published_on->getTimestamp(), 'php:m/Y');
                    $label = Yii::$app->formatter->asDate($item->published_on->getTimestamp(), 'php:F, Y');
                } else {
                    $day   = $id;
                    $label = $item->creator->getFullname();
                }


                if (!isset($result[$day])) {
                    $result[$day] = [
                        'date' => $label,
                        'auth' => [],
                    ];
                }

                if (!isset($result[$day]['auth'][$id])) {
                    $result[$day]['auth'][$id] = [
                        'author' => $item->creator,
                        'news'   => 0,
                        'art'    => 0,
                        'all'    => 0,
                    ];
                }
                $result[$day]['auth'][$id]['all']++;

                if ($item->category->id == Category::ID_NEWS) {
                    $result[$day]['auth'][$id]['news']++;
                } else {
                    $result[$day]['auth'][$id]['art']++;
                }
            }
        }

        return $result;
    }

}


