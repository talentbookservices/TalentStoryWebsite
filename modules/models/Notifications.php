<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_notifications".
 *
 * @property string $guid
 * @property string $type
 * @property string $poster_guid
 * @property string $owner_guid
 * @property string $subject_guid
 * @property string $viewed
 * @property int $time_created
 * @property string $item_guid
 */
class Notifications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'poster_guid', 'owner_guid', 'subject_guid', 'time_created', 'item_guid'], 'required'],
            [['type'], 'string'],
            [['poster_guid', 'owner_guid', 'subject_guid', 'time_created', 'item_guid'], 'integer'],
            [['viewed'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'guid' => 'Guid',
            'type' => 'Type',
            'poster_guid' => 'Poster Guid',
            'owner_guid' => 'Owner Guid',
            'subject_guid' => 'Subject Guid',
            'viewed' => 'Viewed',
            'time_created' => 'Time Created',
            'item_guid' => 'Item Guid',
        ];
    }
}
