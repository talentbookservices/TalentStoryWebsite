<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_msmereg".
 *
 * @property int $RegId
 * @property string $EntreprenuerName
 * @property string $Gender
 * @property string $SocialCategory
 * @property int $PhysicallyHandicapped
 * @property string $EnterpriseName
 * @property string $OrganisationType
 * @property string $PlantLocation
 * @property string $MobileNo
 * @property string $EMail
 * @property string $DateOfBusiness
 * @property string $BusinessActivity
 * @property int $NoOfEmployee
 * @property int $Investment
 * @property string $Sector
 * @property int $UserId
 */
class Msmereg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_msmereg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EntreprenuerName', 'Gender', 'SocialCategory', 'EnterpriseName', 'OrganisationType', 'PlantLocation', 'MobileNo', 'EMail', 'DateOfBusiness', 'BusinessActivity', 'Investment', 'Sector', 'UserId'], 'required'],
            [['DateOfBusiness'], 'safe'],
            [['NoOfEmployee', 'Investment', 'UserId'], 'integer'],
            [['EntreprenuerName', 'Gender', 'SocialCategory', 'EnterpriseName', 'OrganisationType', 'PlantLocation', 'MobileNo', 'EMail', 'BusinessActivity', 'Sector'], 'string', 'max' => 255],
            [['PhysicallyHandicapped'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RegId' => 'Reg ID',
            'EntreprenuerName' => 'Entreprenuer Name',
            'Gender' => 'Gender',
            'SocialCategory' => 'Social Category',
            'PhysicallyHandicapped' => 'Physically Handicapped',
            'EnterpriseName' => 'Enterprise Name',
            'OrganisationType' => 'Organisation Type',
            'PlantLocation' => 'Plant Location',
            'MobileNo' => 'Mobile No',
            'EMail' => 'Email',
            'DateOfBusiness' => 'Date Of Business',
            'BusinessActivity' => 'Business Activity',
            'NoOfEmployee' => 'No Of Employee',
            'Investment' => 'Investment',
            'Sector' => 'Sector',
            'UserId' => 'User ID',
        ];
    }
}
