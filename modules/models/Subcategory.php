<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_subcateg_master".
 *
 * @property int $subcategid
 * @property int $categid
 * @property string $subcategname
 */
class Subcategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_subcateg_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categid', 'subcategname'], 'required'],
            [['categid'], 'integer'],
            [['subcategname'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subcategid' => 'Subcategid',
            'categid' => 'Categid',
            'subcategname' => 'Subcategname',
        ];
    }
}
