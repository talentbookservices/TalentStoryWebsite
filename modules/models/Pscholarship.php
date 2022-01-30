<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "post_scholarship".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $contact_number
 * @property string $email
 * @property string $website_link
 * @property int $minage
 * @property int $maxage
 * @property string $gender
 * @property string $education
 * @property string $percentage
 * @property string $caste
 * @property string $state
 * @property string $last_date
 * @property string $application_address
 * @property int $number_scholarship
 * @property int $status
 * @property string $created_date
 */
class Pscholarship extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_scholarship';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'description'], 'required'],
            [['description'], 'string'],
            [['user_id', 'minage', 'maxage', 'number_scholarship'], 'integer'],
            [['last_date', 'created_date'], 'safe'],
            [['name', 'contact_number', 'email', 'website_link', 'education', 'caste', 'state', 'application_address'], 'string', 'max' => 255],
            [['gender', 'percentage'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'UserID',
            'name' => 'Name',
            'description' => 'Description',
            'contact_number' => 'Contact Number',
            'email' => 'Email',
            'website_link' => 'Website Link',
            'minage' => 'Minage',
            'maxage' => 'Maxage',
            'gender' => 'Gender',
            'education' => 'Education',
            'percentage' => 'Percentage',
            'caste' => 'Caste',
            'state' => 'State',
            'last_date' => 'Last Date',
            'application_address' => 'Application Address',
            'number_scholarship' => 'Number Scholarship',
            'status' => 'Status',
            'created_date' => 'Created Date',
        ];
    }
}
