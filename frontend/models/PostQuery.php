<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * User: rustam
 * Date: 11/16/18
 * Time: 5:03 PM
 */

namespace frontend\models;


use yii\mongodb\ActiveQuery;

class PostQuery extends ActiveQuery
{

    public function active($fields = [])
    {
        return $this->select(
            array_merge([
                            'title',
                            'url',
                            'short_id',
                            'is_main',
                            'status',
                            'info',
                            'image',
                            '_categories',
                            'published_on',
                            'audio',
                            'views',
                            'views_l3d',
                            'views_l7d',
                            'read_min',
                            'is_tagged',
                            'is_bbc',
                            'hide_image',
                            '_translations.title_uz',
                            '_translations.title_oz',
                            '_translations.info_uz',
                            '_translations.info_oz',
                        ], $fields))
                    ->andFilterWhere([
                                         'status' => PostProvider::STATUS_PUBLISHED,
                                     ]);
    }

    public function domain()
    {
        return $this->where(['_domain' => ['$eq' => getenv('DOMAIN')]]);
    }

    /**
     * @param null $db
     * @return PostProvider[]|array|\yii\mongodb\ActiveRecord
     */
    public function all($db = null)
    {
        return parent::all($db); // TODO: Change the autogenerated stub
    }

    /**
     * @param null $db
     * @return PostProvider|array|null|\yii\mongodb\ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db); // TODO: Change the autogenerated stub
    }
}