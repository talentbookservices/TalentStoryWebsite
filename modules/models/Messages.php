<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_messages".
 *
 * @property string $id
 * @property string $message_from
 * @property string $message_to
 * @property string $message
 * @property string $viewed
 * @property int $time
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_from', 'message_to', 'message', 'time'], 'required'],
            [['message_from', 'message_to', 'time'], 'integer'],
            [['message'], 'string'],
            [['viewed'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_from' => 'Message From',
            'message_to' => 'Message To',
            'message' => 'Message',
            'viewed' => 'Viewed',
            'time' => 'Time',
        ];
    }
}
