<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "system_dictionary".
 * @property integer  $id
 * @property string   $latin
 * @property string   $cyrill
 * @property ObjectId $_id
 */
class SystemDictionary extends ActiveRecord
{
    public $search;


    public function attributes()
    {
        return ['_id', 'latin', 'cyrill'];
    }

    public static function collectionName()
    {
        return 'system_dictionary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['latin', 'cyrill'], 'required'],
            [['latin', 'cyrill'], 'string', 'max' => 128],
            [['search'], 'string', 'max' => 128],
        ];
    }

    public function search($params)
    {
        $this->load($params);

        $query = self::find()->select(['latin', 'cyrill']);

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'sort'       => [
                                                       'defaultOrder' => [
                                                           '_id' => 'DESC',
                                                       ],
                                                   ],
                                                   'pagination' => [
                                                       'pageSize' => 50,
                                                   ],
                                               ]);

        if ($this->search) {
            $query->orFilterWhere(['like', 'latin', $this->search]);
            $query->orFilterWhere(['like', 'cyrill', $this->search]);
        }

        return $dataProvider;
    }

    public function getId()
    {
        return (string)$this->_id;
    }

    public function beforeSave($insert)
    {
        $this->cyrill = trim($this->cyrill);
        $this->latin  = trim($this->latin);

        return parent::beforeSave($insert);
    }


}
