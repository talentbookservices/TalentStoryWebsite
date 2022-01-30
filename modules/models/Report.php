<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_report".
 *
 * @property int $report_id
 * @property int $userid
 * @property int $postid
 * @property string $message
 * @property string $created_date
 */
class Report extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'postid', 'message'], 'required'],
            [['userid', 'postid'], 'integer'],
            [['created_date'], 'safe'],
            [['message'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'report_id' => 'Report ID',
            'userid' => 'Userid',
            'postid' => 'Postid',
            'message' => 'Message',
            'created_date' => 'Created Date',
        ];
    }
}
