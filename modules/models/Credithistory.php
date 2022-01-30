<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_credit_history".
 *
 * @property int $history_id
 * @property int $userid
 * @property string $credits
 * @property int $receive_userid
 * @property string $created_date
 */
class Credithistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_credit_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'credits', 'receive_userid'], 'required'],
            [['userid', 'credits', 'receive_userid'], 'integer'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'history_id' => 'History ID',
            'userid' => 'Userid',
            'credits' => 'Credits',
            'receive_userid' => 'Receive Userid',
            'created_date' => 'Created Date',
        ];
    }
}
