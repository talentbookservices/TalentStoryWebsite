<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "k12testdata".
 *
 * @property string $guid
 * @property string $type
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $first_name
 * @property string $last_name
 * @property int $last_login
 * @property int $last_activity
 * @property string $activation
 * @property int $time_created
 * @property string $mobile
 * @property string $college
 * @property string $location
 * @property string $description
 * @property string $work
 * @property string $professionalskill
 * @property string $school
 * @property string $othermobile
 * @property string $aboutyou
 * @property string $nickname
 * @property string $favquotes
 */
class Users extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'k12testdata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'username', 'email', 'password', 'salt', 'first_name', 'last_name', 'last_login', 'last_activity', 'time_created', 'mobile'], 'required'],
            [['type', 'username', 'email', 'password', 'first_name', 'last_name', 'activation'], 'string'],
            [['last_login', 'last_activity', 'time_created'], 'integer'],
            [['salt'], 'string', 'max' => 8],
            [['mobile'], 'string', 'max' => 20],
            /*[['college', 'location', 'description'], 'string', 'max' => 255],
            [['work', 'professionalskill', 'aboutyou', 'favquotes'], 'string', 'max' => 1000],
            [['school'], 'string', 'max' => 500],
            [['nickname'], 'string', 'max' => 300],*/
        ];
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['username','password','email']; 
        return $scenarios; 
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'guid' => 'Guid',
            'type' => 'Type',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'salt' => 'Salt',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'last_login' => 'Last Login',
            'last_activity' => 'Last Activity',
            'activation' => 'Activation',
            'time_created' => 'Time Created',
            'mobile' => 'Mobile',
            /*'college' => 'College',
            'location' => 'Location',
            'description' => 'Description',
            'work' => 'Work',
            'professionalskill' => 'Professionalskill',
            'school' => 'School',
            'othermobile' => 'Othermobile',
            'aboutyou' => 'Aboutyou',
            'nickname' => 'Nickname',
            'favquotes' => 'Favquotes',*/
        ];
    }
}
