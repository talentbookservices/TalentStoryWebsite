<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_maincateg_master".
 *
 * @property int $categid
 * @property string $categname
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_maincateg_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categname'], 'required'],
            [['categname'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categid' => 'Categid',
            'categname' => 'Categname',
        ];
    }
}
