<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_relationships".
 *
 * @property string $relation_id
 * @property string $relation_from
 * @property string $relation_to
 * @property string $type
 * @property int $time
 */
class Relationships extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_relationships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relation_from', 'relation_to', 'type', 'time'], 'required'],
            [['relation_from', 'relation_to', 'time'], 'integer'],
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'relation_id' => 'Relation ID',
            'relation_from' => 'Relation From',
            'relation_to' => 'Relation To',
            'type' => 'Type',
            'time' => 'Time',
        ];
    }
}
