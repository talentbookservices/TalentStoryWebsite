<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_startupreg".
 *
 * @property int $RegId
 * @property string $StartupName
 * @property string $Sector
 * @property string $DateOfFunding
 * @property string $TechnologyUsed
 * @property string $PitchDeck
 * @property string $AboutIdea
 * @property string $ProductStage
 * @property string $BusinessModel
 * @property string $HQLocation
 * @property string $ExplainerVideo
 * @property string $Website
 * @property int $NoOfCustomers
 * @property int $NoOfEmployee
 * @property int $Revenue
 * @property int $FundingRecieved
 * @property int $FundingRequired
 * @property string $Achievements
 * @property int $UserId1
 * @property string $FounderName
 * @property string $FounderPic
 * @property string $LinkedInProfile
 */
class Startupreg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_startupreg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['StartupName', 'Sector', 'DateOfFunding', 'TechnologyUsed', 'PitchDeck', 'AboutIdea', 'ProductStage', 'BusinessModel', 'HQLocation', 'ExplainerVideo', 'Website', 'Achievements', 'UserId1', 'FounderName', 'FounderPic', 'LinkedInProfile'], 'required'],
            [['DateOfFunding'], 'safe'],
            [['NoOfCustomers', 'NoOfEmployee', 'Revenue', 'FundingRecieved', 'FundingRequired', 'UserId1'], 'integer'],
            [['StartupName', 'Sector', 'TechnologyUsed', 'PitchDeck', 'ProductStage', 'BusinessModel', 'Website', 'FounderName', 'FounderPic', 'LinkedInProfile'], 'string', 'max' => 255],
            [['AboutIdea'], 'string', 'max' => 500],
            [['HQLocation', 'ExplainerVideo', 'Achievements'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RegId' => 'Reg ID',
            'StartupName' => 'Startup Name',
            'Sector' => 'Sector',
            'DateOfFunding' => 'Date Of Funding',
            'TechnologyUsed' => 'Technology Used',
            'PitchDeck' => 'Pitch Deck',
            'AboutIdea' => 'About Idea',
            'ProductStage' => 'Product Stage',
            'BusinessModel' => 'Business Model',
            'HQLocation' => 'Hqlocation',
            'ExplainerVideo' => 'Explainer Video',
            'Website' => 'Website',
            'NoOfCustomers' => 'No Of Customers',
            'NoOfEmployee' => 'No Of Employee',
            'Revenue' => 'Revenue',
            'FundingRecieved' => 'Funding Recieved',
            'FundingRequired' => 'Funding Required',
            'Achievements' => 'Achievements',
            'UserId1' => 'User Id1',
            'FounderName' => 'Founder Name',
            'FounderPic' => 'Founder Pic',
            'LinkedInProfile' => 'Linked In Profile',
        ];
    }
}
