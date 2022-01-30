<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_user_credits".
 *
 * @property int $credit_id
 * @property int $userid
 * @property string $credits
 * @property string $created_date
 */
class Usercredits extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_user_credits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'credits'], 'required'],
            [['userid', 'credits'], 'integer'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'credit_id' => 'Credit ID',
            'userid' => 'Userid',
            'credits' => 'Credits',
            'created_date' => 'Created Date',
        ];
    }
}
