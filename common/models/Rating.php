<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/1/17
 * Time: 10:22 PM
 */

namespace common\models;


use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

/**
 * Class Rating
 * @package common\models
 * @property string title
 * @property string content
 * @property mixed  rows
 * @property mixed  url
 * @property mixed  columns
 * @property mixed  selected
 * @property mixed  status
 * @property mixed  year
 * @property mixed  views
 * @property mixed  countries
 */
class Rating extends MongoModel
{
    public $_translatedAttributes = ['title', 'content'];

    const STATUS_ACTIVE  = 'active';
    const STATUS_DISABLE = 'disable';

    public function attributes()
    {
        return [
            '_id',
            'title',
            'url',
            'year',
            'countries',
            'image',
            'content',
            'status',
            'selected',
            '_translations',
            'columns',
            'views',
            'rows',
            'created_at',
            'updated_at',
        ];
    }

    public static function collectionName()
    {
        return 'rating';
    }

    public function rules()
    {
        return [
            [['title', 'year', 'content', 'url', 'countries', 'image'], 'required'],
            [['url', 'status', 'image', 'countries', 'selected'], 'safe'],
            ['views', 'default', 'value' => 0],
            ['selected', 'number', 'min' => 1, 'max' => 1000],
        ];
    }

    public static function getStatusArray()
    {
        return [
            self::STATUS_ACTIVE  => __('Active'),
            self::STATUS_DISABLE => __('Disabled'),
        ];
    }

    public function getStatusLabel()
    {
        return isset(self::getStatusArray()[$this->status]) ? self::getStatusArray()[$this->status] : $this->status;
    }

    public function rowsProvider()
    {
        $provider = new ArrayDataProvider([
                                              'allModels'  => $this->rows,
                                              'pagination' => [
                                                  'pageSize' => 20,
                                              ],
                                          ]);
        return $provider;
    }

    public function getGridColumns()
    {
        $cols = [];
        foreach ($this->columns as $column) {
            $cols[] = [
                'attribute' => $column,
                'format'    => 'raw',
                'value'     => function ($data) use ($column) {
                    return $data[$column];
                }];
        }

        return $cols;
    }

    public function getViewUrl($scheme = true)
    {
        return Url::to(['rating/' . $this->url], $scheme);
    }


    public static function dataProvider($limit)
    {
        $query = self::find()
                     ->andWhere(
                         [
                             'status' => self::STATUS_ACTIVE,
                         ]
                     )
                     ->orderBy(['created_at' => SORT_DESC]);

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

}