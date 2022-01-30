<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "apply_scholarship".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $father_name
 * @property string $email
 * @property string $mobile_number
 * @property int $age
 * @property string $gender
 * @property string $education_details
 * @property string $educational_qualification
 * @property string $percentage
 * @property string $caste
 * @property string $state
 * @property int $status
 * @property string $created_date
 */
class Ascholarship extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apply_scholarship';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'father_name', 'email', 'mobile_number', 'age', 'gender', 'education_details', 'educational_qualification', 'percentage', 'caste', 'state'], 'required'],
            [['user_id', 'age'], 'integer'],
            [['created_date'], 'safe'],
            [['name', 'father_name', 'email', 'mobile_number', 'education_details', 'educational_qualification', 'caste', 'state'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'name' => 'Name',
            'father_name' => 'Father Name',
            'email' => 'Email',
            'mobile_number' => 'Mobile Number',
            'age' => 'Age',
            'gender' => 'Gender',
            'education_details' => 'Education Details',
            'educational_qualification' => 'Educational Qualification',
            'percentage' => 'Percentage',
            'caste' => 'Caste',
            'state' => 'State',
            'status' => 'Status',
            'created_date' => 'Created Date',
        ];
    }
}
