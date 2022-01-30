<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_entities".
 *
 * @property string $guid
 * @property string $owner_guid
 * @property string $type
 * @property string $subtype
 * @property int $time_created
 * @property int $time_updated
 * @property int $permission
 * @property int $active
 */
class Entities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_entities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_guid','comment', 'type', 'subtype', 'time_created', 'permission', 'active'], 'required'],
            [['owner_guid', 'time_created', 'time_updated', 'permission', 'active'], 'integer'],
            [['type', 'subtype'], 'string'],
            [['comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'guid' => 'Guid',
            'owner_guid' => 'Owner Guid',
            'comment'=>'comment',
            'type' => 'Type',
            'subtype' => 'Subtype',
            'time_created' => 'Time Created',
            'time_updated' => 'Time Updated',
            'permission' => 'Permission',
            'active' => 'Active',
        ];
    }
}
