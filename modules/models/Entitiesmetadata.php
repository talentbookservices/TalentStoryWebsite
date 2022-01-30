<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_entities_metadata".
 *
 * @property string $id
 * @property string $guid
 * @property string $value
 */
class Entitiesmetadata extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_entities_metadata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guid', 'value'], 'required'],
            [['guid'], 'integer'],
            [['value'], 'string'],
            [['comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'Guid',
            'comment'=>'comment',
            'value' => 'Value',
        ];
    }
}
