<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/20/17
 * Time: 10:43 PM
 */

namespace frontend\models;


use common\models\Poll;
use Yii;
use yii\data\ActiveDataProvider;

class PollProvider extends Poll
{

    public static function dataProvider($where = [], $size)
    {
        $query = Poll::find()
                     ->where($where)
                     ->orderBy(['created_at' => SORT_DESC]);

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => Yii::$app->request->get('load', $size),
                                          ],
                                      ]);
    }
}