<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_cron_history".
 *
 * @property int $id
 * @property int $userid
 * @property string $credits
 * @property string $message
 */
class Cronhistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_cron_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'credits', 'message'], 'required'],
            [['userid'], 'integer'],
            [['credits', 'message'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'credits' => 'Credits',
            'message' => 'Message',
        ];
    }
}
