<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_castes".
 *
 * @property int $id
 * @property string $caste
 * @property int $status
 */
class Castes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_castes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['caste'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'caste' => 'Caste',
            'status' => 'Status',
        ];
    }
}
