<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_states".
 *
 * @property int $StateID
 * @property string $StateName
 * @property string $Notes
 * @property string $ChangeDate
 */
class States extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['StateName'], 'required'],
            [['Notes'], 'string'],
            [['ChangeDate'], 'safe'],
            [['StateName'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'StateID' => 'State ID',
            'StateName' => 'State Name',
            'Notes' => 'Notes',
            'ChangeDate' => 'Change Date',
        ];
    }
}
