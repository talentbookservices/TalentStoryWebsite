<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_feedbacks".
 *
 * @property string $id
 * @property string $category
 * @property string $guid
 * @property string $feedback
 */
class Feedbacks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_feedbacks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category'], 'string'],
            [['guid'], 'required'],
            [['guid'], 'integer'],
            [['feedback'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'guid' => 'Guid',
            'feedback' => 'Feedback',
        ];
    }
}
