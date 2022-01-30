<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_cities".
 *
 * @property int $city_id
 * @property string $city_name
 * @property string $latitude
 * @property string $longitude
 * @property string $StateID
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'city_name', 'latitude', 'longitude', 'StateID'], 'required'],
            [['city_id'], 'integer'],
            [['city_name', 'StateID'], 'string', 'max' => 50],
            [['latitude', 'longitude'], 'string', 'max' => 10],
            [['city_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City ID',
            'city_name' => 'City Name',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'StateID' => 'State ID',
        ];
    }
}
