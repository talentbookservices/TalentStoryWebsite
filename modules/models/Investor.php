<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "tb_investor".
 *
 * @property int $RegId
 * @property string $InvestorName
 * @property string $InvestorType
 * @property string $CompanyName
 * @property string $Image
 * @property int $NoOfInvestments
 * @property int $AmountInvested
 * @property int $NoOfExists
 * @property string $StartUpFunded
 * @property string $SectorsInterested
 * @property string $FundingStage
 * @property string $LinkedInProfile
 * @property int $UserId
 */
class Investor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_investor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['InvestorName', 'InvestorType', 'Image', 'SectorsInterested', 'FundingStage', 'LinkedInProfile', 'UserId'], 'required'],
            [['NoOfInvestments', 'AmountInvested', 'NoOfExists', 'UserId'], 'integer'],
            [['InvestorName', 'InvestorType', 'CompanyName', 'Image', 'FundingStage', 'LinkedInProfile'], 'string', 'max' => 255],
            [['StartUpFunded'], 'string', 'max' => 500],
            [['SectorsInterested'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RegId' => 'Reg ID',
            'InvestorName' => 'Investor Name',
            'InvestorType' => 'Investor Type',
            'CompanyName' => 'Company Name',
            'Image' => 'Image',
            'NoOfInvestments' => 'No Of Investments',
            'AmountInvested' => 'Amount Invested',
            'NoOfExists' => 'No Of Exists',
            'StartUpFunded' => 'Start Up Funded',
            'SectorsInterested' => 'Sectors Interested',
            'FundingStage' => 'Funding Stage',
            'LinkedInProfile' => 'Linked In Profile',
            'UserId' => 'User ID',
        ];
    }
}
