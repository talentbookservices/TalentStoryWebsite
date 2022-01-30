<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_components".
 *
 * @property string $id
 * @property string $com_id
 * @property int $active
 */
class Components extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_components';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['com_id', 'active'], 'required'],
            [['active'], 'integer'],
            [['com_id'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'com_id' => 'Com ID',
            'active' => 'Active',
        ];
    }
}
