<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_object".
 *
 * @property string $guid
 * @property string $owner_guid
 * @property string $type
 * @property int $time_created
 * @property string $title
 * @property string $description
 * @property string $subtype
 * @property string $category
 * @property string $subcategory
 * @property string $city
 * @property string $area
 * @property string $state
 */
class BaseObject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_object';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_guid', 'type', 'time_created', 'description', 'subtype'], 'required'],
            [['owner_guid', 'time_created'], 'integer'],
            [['title', 'description', 'subtype'], 'string'],
            [['type'], 'string', 'max' => 20],
            [['category'], 'string', 'max' => 255],
            [['subcategory'], 'string', 'max' => 1000],
            [['city', 'area', 'state'], 'string', 'max' => 300],
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
            'type' => 'Type',
            'time_created' => 'Time Created',
            'title' => 'Title',
            'description' => 'Description',
            'subtype' => 'Subtype',
            'category' => 'Category',
            'subcategory' => 'Subcategory',
            'city' => 'City',
            'area' => 'Area',
            'state' => 'State',
        ];
    }
}
