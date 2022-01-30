<?php

namespace app\modules\api\controllers;

use app\modules\models\Users;
use app\modules\models\Entities;
use app\modules\models\Entitiesmetadata;
use app\modules\models\Annotations;
use app\modules\models\Object;
use app\modules\models\Likes;
use app\modules\models\Shares;
use app\modules\models\Components;
use app\modules\models\Messages;
use app\modules\models\Feedbacks;
use app\modules\models\Notifications;
use app\modules\models\Relationships;
use app\modules\models\Usercredits;
use app\modules\models\Credithistory;
use app\modules\models\Report;
use app\modules\models\Category;
use app\modules\models\Subcategory;
use app\modules\models\States;
use app\modules\models\Cities;
use app\modules\models\Courses;
use app\modules\models\Castes;
use app\modules\models\Pscholarship;
use app\modules\models\Ascholarship;
use yii\web\UploadedFile;

class UserController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    //check username and password
    //get variables username and password
    public function actionGetuser()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $user = Users::find()->where(['username' => $attributes['username']])->one();
        if(count($user) > 0)
        {
            $password = md5($attributes['password'] . $user->salt);
            $usercheck = Users::find()->where(['username' => $attributes['username'], 'password'=>$password])->one();
            if(count($usercheck) > 0 )
            {
                $gender = $birthdate = $profilephoto = '';
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$usercheck->guid." and te.type='user' and te.subtype='gender'";
                $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultgender))
                    $gender = $resultgender['value'];

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$usercheck->guid." and te.type='user' and te.subtype='birthdate'";
                $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultbirhtdate))
                   $birthdate = $resultbirhtdate['value']; 
               
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$usercheck->guid." and te.type='user' and te.subtype='file:profile:photo'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = $resultphoto['value']; 

                $data = array('userid'=>$usercheck->guid, 'type'=>$usercheck->type, 'username'=>$usercheck->username, 'email'=>$usercheck->email,
                    'first_name'=>$usercheck->first_name, 'last_name'=>$usercheck->last_name, 'mobile'=>$usercheck->mobile, 'college'=>$usercheck->college,
                    'location'=>$usercheck->location, 'description'=>$usercheck->description, 'work'=>$usercheck->work, 'professionalskill'=>$usercheck->professionalskill, 'school'=>$usercheck->school, 'othermobile'=>$usercheck->othermobile, 'aboutyou'=>$usercheck->aboutyou,
                    'nickname'=>$usercheck->nickname, 'favquotes'=>$usercheck->favquotes, 'birthdate'=>$birthdate, 'gender'=>$gender, 'profilephoto'=>$profilephoto);
                return array('data'=>$data, 'status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'username and password not matching');
        }
        else
            return array('status'=>0, 'error'=>'username not exits');
    }
    
    //check all posts
    public function actionGetallposts()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        //$posts = Object::find()->all();  
        $sql = "select * from tb_relationships where relation_from=".$attributes['ownerid'];
        $relation = \Yii::$app->db->createCommand($sql)->queryAll();
        $relationcheck = $useradmin = '';
        foreach($relation as $key=>$value)
            $relationcheck .= $value['relation_to'].",";
        $relationcheck = rtrim($relationcheck, ",");
        
        $sql = "select * from tb_users where type='admin'";
        $userrel = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach($userrel as $key=>$value)
            $useradmin .= $value['guid'].",";
        $useradmin = rtrim($useradmin, ",");
        
        $userinfo = $attributes['ownerid'].",".$useradmin.",".$relationcheck;
        $userinfo = rtrim($userinfo, ",");
        $sql = "select * from tb_object where owner_guid in (".$userinfo.") order by guid desc limit ".$attributes['minlimit']." , ".$attributes['maxlimit'];
        $posts = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($posts) > 0 )
        {
            $data = array();
            foreach($posts as $key=>$value)
            {
                $description1 = json_decode($value['description']);
                if(!empty($description1->location) && !empty($description1->friend))
                {
                    $user = Users::find()->where(['guid' => $description1->friend])->one();
                    $description = json_encode(array('post'=>$value['description'], 'location'=>$description1->location, 
                        'friend'=>$user['first_name'].' '.$user['last_name']));
                }
                elseif(!empty($description1->location))
                    $description = json_encode(array('post'=>$value['description'], 'location'=>$description1->location));
                elseif(!empty($description1->friend))
                {
                    $user = Users::find()->where(['guid' => $description1->friend])->one();
                    $description = json_encode(array('post'=>$value['description'], 
                        'friend'=>$user['first_name'].' '.$user['last_name']));
                }
                else
                    $description = json_encode(array('post'=>$value['description']));
                
                $description = isset($description1->post) ? $description1->post : '';
                $location = isset($description1->location) ? $description1->location : '';
                $friendtag = isset($description1->friend) ? $description1->friend : '';
                
                if((!empty($description)))
                {
                    if($value['type'] == 'user')
                        $usercheck = Users::find()->where(['guid' => $value['owner_guid']])->one();
                    elseif($value['type'] == 'group')
                    {
                        $objcheck = Object::find()->where(['guid' => $value['owner_guid']])->one();
                        $usercheck = Users::find()->where(['guid' => $objcheck['owner_guid']])->one();
                    }
                    
                    $likestatus = $likecount = $sharestatus = $sharecount = $commentcount = 0;
                    $birthdate = $gender = $profilephoto  = '';
                    $sql = "select * from tb_likes where subject_id=".$value['guid']." and guid=".$attributes['ownerid'];
                    $postlike = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postlike))
                        $likestatus = 1;

                    $sql = "select count(*) as cpost from tb_likes where subject_id=".$value['guid'];
                    $postlikecount = \Yii::$app->db->createCommand($sql)->queryOne();    
                    if(!empty($postlikecount) && $postlikecount['cpost'] > 0)
                        $likecount = (int)$postlikecount['cpost'];

                    $sql = "select * from tb_shares where subject_id=".$value['guid']." and guid=".$attributes['ownerid'];
                    $postshare = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postshare))
                         $sharestatus = 1;

                    $sql = "select count(*) as cshare from tb_shares where subject_id=".$value['guid'];
                    $postsharecount = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postsharecount) && $postsharecount['cshare'] > 0)
                        $sharecount = (int)$postsharecount['cshare'];
                    
                    $sql = "select count(*) as ccomment from tb_annotations as ta, tb_entities as te, tb_entities_metadata as tem where "
                            . "ta.id=te.owner_guid and te.guid=tem.guid and ta.subject_guid=".$value['guid']." and "
                            . "ta.type='comments:post' and te.type='annotation'";
                    $comments = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($comments) && $comments['ccomment'] > 0)
                        $commentcount = (int)$comments['ccomment'];
                    
                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='gender'";
                    $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultgender))
                        $gender = $resultgender['value'];

                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='birthdate'";
                    $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultbirhtdate))
                       $birthdate = $resultbirhtdate['value']; 

                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='file:profile:photo'";
                    $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultphoto))
                        $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$value['owner_guid'].'/'.$resultphoto['value']; 
                    
                    $uploadfile = $type = '';
                    $sql = "select tem.*, te.subtype from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and type='object' "
                            . "and (te.subtype='file:wallphoto' or te.subtype='file:wallvideo' or te.subtype='file:wallpdf') and te.owner_guid=".$value['guid'];
                    $photoupload = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($photoupload))
                    {
                        if($photoupload['subtype'] == 'file:wallphoto')
                        {
                            $type = 'image';
                            $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$photoupload['value'];
                        }
                        elseif($photoupload['subtype'] == 'file:wallvideo')
                        {
                            $type = 'video';
                            $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$photoupload['value'];
                        }
                        elseif($photoupload['subtype'] == 'file:wallpdf')
                        {
                            $type = 'pdf';
                            $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$photoupload['value'];
                        }
                    }
                    
                    $data[] = array('post_id'=>$value['guid'],'userid'=>$value['owner_guid'], 'type'=>$value['type'], 
                        'title'=>$value['title'], 'description'=>$description, 'friendtag'=>$friendtag, 'location'=>$location,
                        'subtype'=>$value['subtype'], 'category'=>$value['category'],
                        'subcategory'=>$value['subcategory'], 'city'=>$value['city'], 'state'=>$value['state'], 'area'=>$value['area'], 'likestatus'=>$likestatus,
                        'likecount'=>$likecount, 'sharestatus'=>$sharestatus, 'sharecount'=>$sharecount, 'timecreated' =>$value['time_created'],
                        'commentcount'=>$commentcount, 'uploadfile'=>$uploadfile, 'filetype'=>$type, 'owner'=>array('userid'=>$usercheck['guid'],
                        'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 
                        'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 
                        'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                        'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                        'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                        'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'birthdate'=>$birthdate, 'gender'=>$gender, 
                        'profilephoto'=>$profilephoto));
                }
            }
            return array('result'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'no posts');
    }

    //check all posts
    public function actionGetalladminposts()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        //$posts = Object::find()->all();  
        $sql = "select * from tb_object order by guid desc limit ".$attributes['minlimit']." , ".$attributes['maxlimit'];
        $posts = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($posts) > 0 )
        {
            $data = array();
            foreach($posts as $key=>$value)
            {
                $description1 = json_decode($value['description']);
                if(!empty($description1->location) && !empty($description1->friend))
                {
                    $user = Users::find()->where(['guid' => $description1->friend])->one();
                    $description = json_encode(array('post'=>$value['description'], 'location'=>$description1->location, 
                        'friend'=>$user['first_name'].' '.$user['last_name']));
                }
                elseif(!empty($description1->location))
                    $description = json_encode(array('post'=>$value['description'], 'location'=>$description1->location));
                elseif(!empty($description1->friend))
                {
                    $user = Users::find()->where(['guid' => $description1->friend])->one();
                    $description = json_encode(array('post'=>$value['description'], 
                        'friend'=>$user['first_name'].' '.$user['last_name']));
                }
                else
                    $description = json_encode(array('post'=>$value['description']));
                
                $description = isset($description1->post) ? $description1->post : '';
                $location = isset($description1->location) ? $description1->location : '';
                $friendtag = isset($description1->friend) ? $description1->friend : '';
                
                if((!empty($description)))
                {
                    if($value['type'] == 'user')
                        $usercheck = Users::find()->where(['guid' => $value['owner_guid']])->one();
                    elseif($value['type'] == 'group')
                    {
                        $objcheck = Object::find()->where(['guid' => $value['owner_guid']])->one();
                        $usercheck = Users::find()->where(['guid' => $objcheck['owner_guid']])->one();
                    }
                    
                    $likestatus = $likecount = $sharestatus = $sharecount = $commentcount = 0;
                    $birthdate = $gender = $profilephoto  = '';
                    $sql = "select * from tb_likes where subject_id=".$value['guid']." and guid=".$attributes['ownerid'];
                    $postlike = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postlike))
                        $likestatus = 1;

                    $sql = "select count(*) as cpost from tb_likes where subject_id=".$value['guid'];
                    $postlikecount = \Yii::$app->db->createCommand($sql)->queryOne();    
                    if(!empty($postlikecount) && $postlikecount['cpost'] > 0)
                        $likecount = (int)$postlikecount['cpost'];

                    $sql = "select * from tb_shares where subject_id=".$value['guid']." and guid=".$attributes['ownerid'];
                    $postshare = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postshare))
                         $sharestatus = 1;

                    $sql = "select count(*) as cshare from tb_shares where subject_id=".$value['guid'];
                    $postsharecount = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postsharecount) && $postsharecount['cshare'] > 0)
                        $sharecount = (int)$postsharecount['cshare'];
                    
                    $sql = "select count(*) as ccomment from tb_annotations as ta, tb_entities as te, tb_entities_metadata as tem where "
                            . "ta.id=te.owner_guid and te.guid=tem.guid and ta.subject_guid=".$value['guid']." and "
                            . "ta.type='comments:post' and te.type='annotation'";
                    $comments = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($comments) && $comments['ccomment'] > 0)
                        $commentcount = (int)$comments['ccomment'];
                    
                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='gender'";
                    $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultgender))
                        $gender = $resultgender['value'];

                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='birthdate'";
                    $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultbirhtdate))
                       $birthdate = $resultbirhtdate['value']; 

                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='file:profile:photo'";
                    $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultphoto))
                        $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$value['owner_guid'].'/'.$resultphoto['value']; 
                    
                    $uploadfile = $type = '';
                    $sql = "select tem.*, te.subtype from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and type='object' "
                            . "and (te.subtype='file:wallphoto' or te.subtype='file:wallvideo' or te.subtype='file:wallpdf') and te.owner_guid=".$value['guid'];
                    $photoupload = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($photoupload))
                    {
                        if($photoupload['subtype'] == 'file:wallphoto')
                        {
                            $type = 'image';
                            $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$photoupload['value'];
                        }
                        elseif($photoupload['subtype'] == 'file:wallvideo')
                        {
                            $type = 'video';
                            $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$photoupload['value'];
                        }
                        elseif($photoupload['subtype'] == 'file:wallpdf')
                        {
                            $type = 'pdf';
                            $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$photoupload['value'];
                        }
                    }
                    
                    $data[] = array('post_id'=>$value['guid'],'userid'=>$value['owner_guid'], 'type'=>$value['type'], 
                        'title'=>$value['title'], 'description'=>$description, 'friendtag'=>$friendtag, 'location'=>$location, 'subtype'=>$value['subtype'], 'category'=>$value['category'],
                        'subcategory'=>$value['subcategory'], 'city'=>$value['city'], 'state'=>$value['state'], 'area'=>$value['area'], 'likestatus'=>$likestatus,
                        'likecount'=>$likecount, 'sharestatus'=>$sharestatus, 'sharecount'=>$sharecount, 'timecreated' =>$value['time_created'],
                        'commentcount'=>$commentcount, 'uploadfile'=>$uploadfile, 'filetype'=>$type, 'owner'=>array('userid'=>$usercheck['guid'],
                        'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 
                        'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 
                        'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                        'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                        'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                        'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'birthdate'=>$birthdate, 'gender'=>$gender, 
                        'profilephoto'=>$profilephoto));
                }
            }
            return array('result'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'no posts');
    }
    
    //edit the user profile
    //userid and edit attributes
    public function actionEditprofile()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $user = Users::find()->where(['guid' => $attributes['userid']])->one(); 
        if(count($user) > 0 )
        {
            $password = $user->password;
            if(!empty($attributes['password']))
            {
                $salt = substr(uniqid(), 5);
                $password = md5($attributes['password'] . $user->salt);
            }
            $user->email = isset($attributes['email']) ? $attributes['email'] : $user->email;
            $user->password = $password;
            $user->first_name = isset($attributes['first_name']) ? $attributes['first_name'] : $user->first_name;
            $user->last_name = isset($attributes['last_name']) ? $attributes['last_name'] : $user->last_name;
            $user->mobile = isset($attributes['mobile']) ? $attributes['mobile'] : $user->mobile;
            $user->college = isset($attributes['college']) ? $attributes['college'] : $user->college;
            $user->location = isset($attributes['location']) ? $attributes['location'] : $user->location;
            $user->description = isset($attributes['description']) ? $attributes['description'] : $user->description;
            $user->work = isset($attributes['work']) ? $attributes['work'] : $user->work;
            $user->professionalskill = isset($attributes['professionalskill']) ? $attributes['professionalskill'] : $user->professionalskill;
            $user->school = isset($attributes['school']) ? $attributes['school'] : $user->school;
            $user->othermobile = isset($attributes['othermobile']) ? $attributes['othermobile'] : $user->othermobile;
            $user->aboutyou = isset($attributes['aboutyou']) ? $attributes['aboutyou'] : $user->aboutyou;
            $user->nickname = isset($attributes['nickname']) ? $attributes['nickname'] : $user->nickname;
            $user->favquotes = isset($attributes['favquotes']) ? $attributes['favquotes'] : $user->favquotes;
            $user->save(false);
            $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$attributes['userid']." and te.type='user' and te.subtype='gender'";
            $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
            if(!empty($resultgender))
            {
                if(!empty($attributes['gender']))
                {
                    $entity = Entitiesmetadata::find()->where(['guid' => $resultgender['guid']])->one();
                    $entity->value = isset($attributes['gender']) ? $attributes['gender'] : $entity->value;
                    $entity->save(false);
                }
                else
                {
                    \Yii::$app->db->createCommand()->delete('tb_entities', ['owner_guid' => $attributes['userid']])->execute();
                    \Yii::$app->db->createCommand()->delete('tb_entities_metadata', ['guid' => $attributes['userid']])->execute();
                }
            }    
            else
            {
                if(!empty($attributes['gender']))
                {
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $attributes['userid'],'type' => 'user',
                        'subtype' => 'gender','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $attributes['gender']])->execute();
                }
            }
            $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$attributes['userid']." and te.type='user' and te.subtype='birthdate'";
            $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
            if(!empty($resultbirhtdate))
            {
                if(!empty($attributes['birthdate']))
                {
                    $entity = Entitiesmetadata::find()->where(['guid' => $resultgender['guid']])->one();
                    $entity->value = isset($attributes['birthdate']) ? $attributes['birthdate'] : $entity->value;
                    $entity->save(false);
                }
                else
                {
                    \Yii::$app->db->createCommand()->delete('tb_entities', ['owner_guid' => $attributes['userid']])->execute();
                    \Yii::$app->db->createCommand()->delete('tb_entities_metadata', ['guid' => $attributes['userid']])->execute();
                }
            }
            else
            {
                if(!empty($attributes['birthdate']))
                {
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $attributes['userid'],'type' => 'user',
                        'subtype' => 'birthdate','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $attributes['gender']])->execute();
                }
            }
            $usercheck = Users::find()->where(['guid' => $attributes['userid']])->one();
            $birthdate = $gender = $profilephoto  = '';
            $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$attributes['userid']." and te.type='user' and te.subtype='gender'";
            $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
            if(!empty($resultgender))
                $gender = $resultgender['value'];

            $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$attributes['userid']." and te.type='user' and te.subtype='birthdate'";
            $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
            if(!empty($resultbirhtdate))
               $birthdate = $resultbirhtdate['value']; 

            $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$attributes['userid']." and te.type='user' and te.subtype='file:profile:photo'";
            $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
            if(!empty($resultphoto))
                $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$value['owner_guid'].'/'.$resultphoto['value']; 
                    
            $data = array('userid'=>$usercheck['guid'],
                'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 
                'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 
                'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'birthdate'=>$birthdate, 'gender'=>$gender, 
                'profilephoto'=>$profilephoto);
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'No User');
    }
    
    //Like status
    //pass postid and userid get the like status
    public function actionLikestatus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $postlike = Likes::find()->where(['subject_id'=>$attributes['postid'], 'guid'=>$attributes['userid']])->one();
        if(count($postlike) > 0 )
            return array('status'=>1, 'error'=>'');
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //Like count
    //pass postid and get the count
    public function actionLikecount()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $postlike = Likes::find()->where(['subject_id'=>$attributes['postid']])->count();
        if(count($postlike) > 0 )
            return array('count'=>$postlike, 'error'=>'');
        else
            return array('count'=>0, 'error'=>'');
    }
    
    //Like count
    //pass postid and get the count
    public function actionLikeusers()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_likes where subject_id=".$attributes['postid'];
        $postlike = \Yii::$app->db->createCommand($sql)->queryAll();
        $postlike = Likes::find()->where(['subject_id'=>$attributes['postid']])->all();
        if(count($postlike) > 0 )
        {
            $data = '';
            foreach($postlike as $key=>$value)
            {
                $usercheck = Users::find()->where(['guid' => $value['guid']])->one();
                if(count($usercheck) > 0)
                {
                    $data[] = array('userid'=>$usercheck->guid, 'type'=>$usercheck->type, 'username'=>$usercheck->username, 'email'=>$usercheck->email,
                    'first_name'=>$usercheck->first_name, 'last_name'=>$usercheck->last_name, 'mobile'=>$usercheck->mobile, 'college'=>$usercheck->college,
                    'location'=>$usercheck->location, 'description'=>$usercheck->description, 'work'=>$usercheck->work, 'professionalskill'=>$usercheck->professionalskill, 'school'=>$usercheck->school, 'othermobile'=>$usercheck->othermobile, 'aboutyou'=>$usercheck->aboutyou,
                    'nickname'=>$usercheck->nickname, 'favquotes'=>$usercheck->favquotes);
                }
            }
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'No Count');
    }

    //Like count
    //pass postid and get the count
    public function actionShareusers()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $postlike = Shares::find()->where(['subject_id'=>$attributes['postid']])->all();
        if(count($postlike) > 0 )
        {
            $data = '';
            foreach($postlike as $key=>$value)
            {
                $usercheck = Users::find()->where(['guid' => $value['guid']])->one();
                if(count($usercheck) > 0)
                {
                    $data[] = array('userid'=>$usercheck->guid, 'type'=>$usercheck->type, 'username'=>$usercheck->username, 'email'=>$usercheck->email,
                    'first_name'=>$usercheck->first_name, 'last_name'=>$usercheck->last_name, 'mobile'=>$usercheck->mobile, 'college'=>$usercheck->college,
                    'location'=>$usercheck->location, 'description'=>$usercheck->description, 'work'=>$usercheck->work, 'professionalskill'=>$usercheck->professionalskill, 'school'=>$usercheck->school, 'othermobile'=>$usercheck->othermobile, 'aboutyou'=>$usercheck->aboutyou,
                    'nickname'=>$usercheck->nickname, 'favquotes'=>$usercheck->favquotes);
                }
            }
            return array('data'=>$data, 'status'=>1, 'error'=>'');            
        }
        else
            return array('status'=>0, 'error'=>'No Count');
    }

    //share status
    //pass postid and userid get the like status
    public function actionSharestatus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $postshare = Shares::find()->where(['subject_id'=>$attributes['postid'], 'guid'=>$attributes['userid']])->one();
        if(count($postshare) > 0 )
            return array('status'=>1, 'error'=>'');
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //Share count
    //pass postid and get the count
    public function actionSharecount()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $postshare = Shares::find()->where(['subject_id'=>$attributes['postid']])->count();
        if(count($postshare) > 0 )
            return array('count'=>$postshare, 'error'=>'');
        else
            return array('count'=>0, 'error'=>'');
    }
    
    //Unline or like save database
    //pass postid, userid and status
    //type should be post, entity, anotation
    public function actionLikedata()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        if(isset($attributes['status']) && $attributes['status'] == 'like')
        {
            $postlike1 = Likes::find()->where(['subject_id'=>$attributes['postid'], 'guid'=>$attributes['userid']])->one();
            if(empty($postlike1))
            {
                $postlike = new Likes();
                $postlike->subject_id = $attributes['postid'];
                $postlike->guid = $attributes['userid'];
                $postlike->type = isset($attributes['type']) ? $attributes['type'] : 'post';
                $postlike->save();
                return array('status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'');
        }
        else
        {
            $postlike = Likes::find()->where(['subject_id'=>$attributes['postid'], 'guid'=>$attributes['userid']])->one();
            if(count($postlike) > 0 )
            {
                \Yii::$app->db->createCommand()->delete('tb_likes', ['subject_id' => $attributes['postid'], 'guid' => $attributes['userid']])->execute();
                return array('status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'');
        }
    }
    
    //Unline or like save database
    //pass postid, userid and status
    //type should be post, entity, anotation
    public function actionSharedata()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        if(isset($attributes['status']) && $attributes['status'] == 'like')
        {
            $postshare1 = Shares::find()->where(['subject_id'=>$attributes['postid'], 'guid'=>$attributes['userid']])->one();
            if(empty($postshare1))
            {
                $postshare = new Shares();
                $postshare->subject_id = $attributes['postid'];
                $postshare->guid = $attributes['userid'];
                $postshare->type = isset($attributes['type']) ? $attributes['type'] : 'post';
                $postshare->save();
                return array('status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'');
        }
        else
        {
            $postshare1 = Shares::find()->where(['subject_id'=>$attributes['postid'], 'guid'=>$attributes['userid']])->one();
            if(count($postshare1) > 0 )
            {
                \Yii::$app->db->createCommand()->delete('tb_shares', ['subject_id' => $attributes['postid'], 'guid' => $attributes['userid']])->execute();
                return array('status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'');
        }
    }
    
    //Post message
    //pass the message and userid
    public function actionPostmessage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['description']) && !empty($attributes['description'])) {
            $description = json_encode(array('post'=>$attributes['description']));
            $object = new Object();
            $object->owner_guid = $attributes['userid'];
            $object->type = isset($attributes['type']) ? $attributes['type'] : 'user';
            $object->time_created =  time();
            $object->title = isset($attributes['title']) ? $attributes['title'] : '';
            $object->description = $description;
            $object->subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'wall';
            $object->category = isset($attributes['category']) ? $attributes['category'] : '';
            $object->subcategory = isset($attributes['subcategory']) ? $attributes['subcategory'] : '';
            $object->city = isset($attributes['city']) ? $attributes['city'] : '';
            $object->area = isset($attributes['area']) ? $attributes['area'] : '';
            $object->state = isset($attributes['state']) ? $attributes['state'] : '';
            if($object->save())
            {
                $objectinfo = Object::find()->where(['guid' => $object->guid])->one();
                $description1 = json_decode($objectinfo['description']);
                $description = $description1->post;
                $birthdate = $gender = $profilephoto  = '';
                if($objectinfo['type'] == 'user')
                    $usercheck = Users::find()->where(['guid' => $objectinfo['owner_guid']])->one();
                elseif($value['type'] == 'group')
                {
                    $objcheck = Object::find()->where(['guid' => $objectinfo['owner_guid']])->one();
                    $usercheck = Users::find()->where(['guid' => $objcheck['owner_guid']])->one();
                }
                
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='gender'";
                $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultgender))
                    $gender = $resultgender['value'];

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='birthdate'";
                $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultbirhtdate))
                   $birthdate = $resultbirhtdate['value']; 

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='file:profile:photo'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$objectinfo['owner_guid'].'/'.$resultphoto['value']; 
                
                $uploadfile = $type = '';
                $sql = "select tem.*, te.subtype from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and type='object' "
                        . "and (te.subtype='file:wallphoto' or te.subtype='file:wallvideo' or te.subtype='file:wallpdf') and te.owner_guid=".$objectinfo['guid'];
                $photoupload = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($photoupload))
                {
                    if($photoupload['subtype'] == 'file:wallphoto')
                    {
                        $type = 'image';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                    elseif($photoupload['subtype'] == 'file:wallvideo')
                    {
                        $type = 'video';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                    elseif($photoupload['subtype'] == 'file:wallpdf')
                    {
                        $type = 'pdf';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                }
                
                $data = array('post_id'=>$objectinfo['guid'],'userid'=>$objectinfo['owner_guid'], 'type'=>$objectinfo['type'], 
                    'title'=>$objectinfo['title'], 'description'=>$description, 'subtype'=>$objectinfo['subtype'], 'category'=>$objectinfo['category'],
                    'subcategory'=>$objectinfo['subcategory'], 'city'=>$objectinfo['city'], 'state'=>$objectinfo['state'], 'area'=>$objectinfo['area'], 'timecreated' =>$value['time_created'], 'uploadfile'=>$uploadfile, 'filetype'=>$type, 'owner'=>array('userid'=>$usercheck['guid'],
                    'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                    'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                    'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                    'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'birthdate'=>$birthdate, 'gender'=>$gender, 
                    'profilephoto'=>$profilephoto));
                return array('data'=>$data,'status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //Post edit message
    //pass the message and userid
    public function actionPosteditmessage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['description']) && !empty($attributes['description'])) {
            $location = isset($attributes['location']) ? $attributes['location'] : '';
            $friend = isset($attributes['friend']) ? $attributes['friend'] : '';
            if(!empty($attributes['location']) && !empty($attributes['friend']))
                $description = json_encode(array('post'=>$attributes['description'], 'location'=>$attributes['location'], 'friend'=>$attributes['friend']));
            elseif(!empty($attributes['location']))
                $description = json_encode(array('post'=>$attributes['description'], 'location'=>$attributes['location']));
            elseif(!empty($attributes['friend']))
                $description = json_encode(array('post'=>$attributes['description'], 'friend'=>$attributes['friend']));
            else
                $description = json_encode(array('post'=>$attributes['description']));
            
            $object = Object::find()->where(['guid'=>$attributes['postid']])->one();
            $object->title = isset($attributes['title']) ? $attributes['title'] : '';
            $object->description = $description;
            $object->subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'wall';
            $object->category = isset($attributes['category']) ? $attributes['category'] : '';
            $object->subcategory = isset($attributes['subcategory']) ? $attributes['subcategory'] : '';
            $object->city = isset($attributes['city']) ? $attributes['city'] : '';
            $object->area = isset($attributes['area']) ? $attributes['area'] : '';
            $object->state = isset($attributes['state']) ? $attributes['state'] : '';
            if($object->save(false))
            {
                if(isset($_FILES["imagepath"]["name"]) && !empty($_FILES["imagepath"]["name"]))
                {
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid, 0777, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall'))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall', 0777, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$attributes['filetype']))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$attributes['filetype'], 0777, true);    

                    $extension = pathinfo($_FILES["imagepath"]["name"], PATHINFO_EXTENSION);
                    $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                    $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$attributes['filetype'].'/'.$name.".".$extension;

                    $datafile = 'ossnwall/'.$attributes['filetype'].'/'.$name.'.'.$extension;
                    $filepatht = 'https://thetalentbook.co.in/tb_data/object/'.$object->guid.'/ossnwall/'.$attributes['filetype'].'/'.$name.".".$extension;
                    if(move_uploaded_file($_FILES["imagepath"]["tmp_name"], $target_file))
                    {
                        if($attributes['filetype'] == 'image')
                            $type = 'file:wallphoto';
                        elseif($attributes['filetype'] == 'video')
                            $type = 'file:wallvideo';
                        elseif($attributes['filetype'] == 'pdf')
                            $type = 'file:wallpdf';

                        $entity = Entities::find()->where(['owner_guid'=>$object->guid, 'type'=>'object'])->one();
                        if(!empty($entity))
                        {
                            $entity->subtype = $type;
                            $entity->time_created = time();
                            $entity->permission = 2;
                            $entity->active = 1;
                            if($entity->save())
                            {
                                $entityid = $entity->guid;
                                $entitymeta = Entitiesmetadata::find()->where(['guid'=>$entityid])->one();
                                $entitymeta->value = $datafile;
                                $entitymeta->save();
                            }
                        }
                        else
                        {
                            $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $object->guid,'type' => 'object',
                                'subtype' => 'poster_guid','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                            $entityid = \Yii::$app->db->getLastInsertID();
                            $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $attributes['userid']])->execute();

                            $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $object->guid,'type' => 'object',
                                    'subtype' => 'access','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                            $entityid = \Yii::$app->db->getLastInsertID();
                            $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => '3'])->execute();

                            $entity = new Entities();
                            $entity->owner_guid = $object->guid;
                            $entity->type = 'object';
                            $entity->subtype = $type;
                            $entity->time_created = time();
                            $entity->permission = 2;
                            $entity->active = 1;
                            if($entity->save())
                            {
                                $entityid = $entity->guid;
                                $entitymeta = new Entitiesmetadata();
                                $entitymeta->guid = $entityid;
                                $entitymeta->value = $datafile;
                                $entitymeta->save();
                            }
                        }
                    }
                }
                $objectinfo = Object::find()->where(['guid' => $object->guid])->one();
                $description1 = json_decode($objectinfo['description']);
                if(!empty($description1->location) && !empty($description1->friend))
                {
                    $user = Users::find()->where(['guid' => $description1->friend])->one();
                    $description = json_encode(array('post'=>$attributes['description'], 'location'=>$description1->location, 
                        'friend'=>$user['first_name'].' '.$user['last_name']));
                    
                }
                elseif(!empty($description1->location))
                    $description = json_encode(array('post'=>$attributes['description'], 'location'=>$description1->location));
                elseif(!empty($description1->friend))
                {
                    $user = Users::find()->where(['guid' => $description1->friend])->one();
                    $description = json_encode(array('post'=>$attributes['description'], 
                        'friend'=>$user['first_name'].' '.$user['last_name']));
                }
                else
                    $description = json_encode(array('post'=>$attributes['description']));
                
                $description = isset($description1->post) ? $description1->post : '';
                $location = isset($description1->location) ? $description1->location : '';
                $friendtag = isset($description1->friend) ? $description1->friend : '';
                $birthdate = $gender = $profilephoto  = '';
                if($objectinfo['type'] == 'user')
                    $usercheck = Users::find()->where(['guid' => $objectinfo['owner_guid']])->one();
                elseif($value['type'] == 'group')
                {
                    $objcheck = Object::find()->where(['guid' => $objectinfo['owner_guid']])->one();
                    $usercheck = Users::find()->where(['guid' => $objcheck['owner_guid']])->one();
                }
                
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='gender'";
                $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultgender))
                    $gender = $resultgender['value'];

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='birthdate'";
                $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultbirhtdate))
                   $birthdate = $resultbirhtdate['value']; 

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='file:profile:photo'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$objectinfo['owner_guid'].'/'.$resultphoto['value']; 
                
                $uploadfile = $type = '';
                $sql = "select tem.*, te.subtype from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and type='object' "
                        . "and (te.subtype='file:wallphoto' or te.subtype='file:wallvideo' or te.subtype='file:wallpdf') and te.owner_guid=".$objectinfo['guid'];
                $photoupload = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($photoupload))
                {
                    if($photoupload['subtype'] == 'file:wallphoto')
                    {
                        $type = 'image';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                    elseif($photoupload['subtype'] == 'file:wallvideo')
                    {
                        $type = 'video';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                    elseif($photoupload['subtype'] == 'file:wallpdf')
                    {
                        $type = 'pdf';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                }
                
                $data = array('post_id'=>$objectinfo['guid'],'userid'=>$objectinfo['owner_guid'], 'type'=>$objectinfo['type'], 
                    'title'=>$objectinfo['title'], 'description'=>$description, 'friendtag'=>$friendtag, 'location'=>$location,
                    'subtype'=>$objectinfo['subtype'], 'category'=>$objectinfo['category'],
                    'subcategory'=>$objectinfo['subcategory'], 'city'=>$objectinfo['city'], 'state'=>$objectinfo['state'], 'area'=>$objectinfo['area'], 'timecreated' =>$objectinfo['time_created'], 'uploadfile'=>$uploadfile, 'filetype'=>$type, 'owner'=>array('userid'=>$usercheck['guid'],
                    'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                    'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                    'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                    'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'birthdate'=>$birthdate, 'gender'=>$gender, 
                    'profilephoto'=>$profilephoto));
                return array('data'=>$data,'status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //post delete
    public function actionDeletepost()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $object = Object::find()->where(['guid'=>$attributes['postid']])->one();
        if(count($object) > 0 )
        {
            \Yii::$app->db->createCommand()->delete('tb_object', ['guid' => $attributes['postid']])->execute();
            return array('status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //createcomment
    public function actionCommentcreate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $datafile = '';
        if(isset($attributes['postid']) && !empty($attributes['postid']))
        {
            $annotations = new Annotations();
            $annotations->owner_guid = $attributes['userid'];
            $annotations->subject_guid = $attributes['postid'];
            $annotations->type = 'comments:post';
            $annotations->time_created = time();
            $annotations->save();
            $annotationid = $annotations->id;
            $subjecttype = 'comments:post';
            if(isset($_FILES["comment"]["name"]) && !empty($_FILES["comment"]["name"]))
            {
                if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid))
                {
                    mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid, 0777, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment'))
                       mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment', 0777, true);
                   if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment/photo'))
                       mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment/photo', 0777, true);
                }

                $extension = pathinfo($_FILES["comment"]["name"], PATHINFO_EXTENSION);
                $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment/photo/'.$name.".".$extension;
                
                $filepatht = 'https://thetalentbook.co.in/tb_data/'.$annotationid.'/comment/photo/'.$name.".".$extension;

                if(move_uploaded_file($_FILES["comment"]["tmp_name"], $target_file))
                {
                    $datafile = 'comment/photo/'.$name.'.'.$extension;
                    $subjecttype = 'file:comment:photo';
                }
            }
            $entity = new Entities();
            $entity->owner_guid = $annotationid;
            $entity->type = 'annotation';
            $entity->subtype = 'comments:post';
            $entity->time_created = time();
            $entity->permission = 2;
            $entity->active = 1;
            if($entity->save(false))
            {
                $entityid = $entity->guid;
                $entitymeta = new Entitiesmetadata();
                $entitymeta->guid = $entityid;
                $entitymeta->value = isset($attributes['commenttext']) ? $attributes['commenttext'] : '';
                $entitymeta->save();
            }
            if(!empty($datafile))
            {
                $entity = new Entities();
                $entity->owner_guid = $annotationid;
                $entity->type = 'annotation';
                $entity->subtype = $subjecttype;
                $entity->time_created = time();
                $entity->permission = 2;
                $entity->active = 1;
                $entity->save();
                $entityid = $entity->guid;
                
                $entitymeta = new Entitiesmetadata();
                $entitymeta->guid = $entityid;
                $entitymeta->value = $datafile;
                $entitymeta->save();
            }
            $usercheck = Users::find()->where(['guid' => $attributes['userid']])->one();
            $commentinfo = array('commentid'=>$annotationid, 'userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                        'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                        'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                        'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                        'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes']);
            return array('commentinfo'=>$commentinfo,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');    
    }
   
    //getcomment 
    public function actionGetcomment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select tem.*, ta.owner_guid, ta.id as tid, te.subtype as subtype from tb_annotations as ta, tb_entities as te, tb_entities_metadata as tem where "
                . "ta.id=te.owner_guid and te.guid=tem.guid and ta.subject_guid=".$attributes['postid']." and "
                . "(ta.type='comments:post' || ta.type='file:comment:photo') and te.type='annotation'";
        $comments = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($comments) > 0 )
        {
            $commentinfo = array();
            foreach($comments as $key=>$value)
            {
                if($value['subtype'] == 'file:comment:photo')
                    $comentmessage = 'https://thetalentbook.co.in/tb_data/annotation/'.$value['tid'].'/'.$value['value'];
                else
                    $comentmessage = $value['value'];
                $likestatus = $commentcount = 0;
                $sql = "select * from tb_likes where subject_id=".$value['tid']." and type='annotation' and guid=".$attributes['ownerid'];
                $postlike = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($postlike))
                    $likestatus = 1;
                
                $sql = "select count(*) as ccomment from tb_likes where subject_id=".$value['tid']." and type='annotation'";
                $likecounts = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($likecounts) && $likecounts['ccomment'] > 0)
                        $commentcount = (int)$likecounts['ccomment'];
                
                $usercheck = Users::find()->where(['guid' => $value['owner_guid']])->one();
                $commentinfo[] = array('commentid'=>$value['tid'],'comment'=>$comentmessage, 'userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                        'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                        'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                        'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                        'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'likestatus'=>$likestatus, 'likecount'=>$commentcount);
            }
            return array('data'=>$commentinfo,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //edit comment
    public function actionEditcomment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $annotation = Annotations::find()->where(['id'=>$attributes['commentid']])->one();
        $annotationid = $annotation['id'];
        $entity = Entities::find()->where(['owner_guid'=>$annotation['id']])->one();
        $entitymeta = Entitiesmetadata::find()->where(['guid'=>$entity['guid']])->one();
        if(count($entitymeta) > 0 )
        {
            if(isset($_FILES["comment"]["name"]) && !empty($_FILES["comment"]["name"]))
            {
                if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid))
                {
                    mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid, 0777, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment'))
                       mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment', 0777, true);
                   if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment/photo'))
                       mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment/photo', 0777, true);
                }

                $extension = pathinfo($_FILES["comment"]["name"], PATHINFO_EXTENSION);
                $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/'.$annotationid.'/comment/photo/'.$name.".".$extension;
                
                $filepatht = 'https://thetalentbook.co.in/tb_data/'.$annotationid.'/comment/photo/'.$name.'.'.$extension;

                if(move_uploaded_file($_FILES["comment"]["tmp_name"], $target_file))
                {
                    $datafile = 'comment/photo/'.$name.'.'.$extension;
                    $subjecttype = 'file:comment:photo';
                }
            }
            else
                $datafile = isset($attributes['comment']) ? $attributes['comment'] : '';
            $entitymeta->value = $datafile;
            $entitymeta->save(false);
            return array('commentid'=>$annotationid,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //delete comment
    public function actionDeletecomment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $annotation = Annotations::find()->where(['id'=>$attributes['commentid']])->one();
        if(count($annotation) > 0 )
        {
            $entity = Entities::find()->where(['owner_guid'=>$annotation['id']])->one();
            $entitymeta = Entitiesmetadata::find()->where(['guid'=>$entity['guid']])->one();
            \Yii::$app->db->createCommand()->delete('tb_annotations', ['id' => $annotation['id']])->execute();
            \Yii::$app->db->createCommand()->delete('tb_entities', ['guid' => $entity['guid']])->execute();
            \Yii::$app->db->createCommand()->delete('tb_entities_metadata', ['guid' => $attributes['commentid']])->execute();
            return array('status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //delete profile image
    //send userid
    public function actionDeleteimage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $getattributes = \yii::$app->request->get();
        $user = Users::find()->where(['guid' => $getattributes['userid']])->one(); 
        if(count($user) > 0 )
        {
            $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$getattributes['userid']." and te.type='user' and te.subtype='file:profile:photo'";
            $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
            if(!empty($resultbirhtdate))
            {
                \Yii::$app->db->createCommand()->delete('tb_entities', ['owner_guid' => $getattributes['userid']])->execute();
                \Yii::$app->db->createCommand()->delete('tb_entities_metadata', ['guid' => $getattributes['userid']])->execute();
                return array('status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'No Profile Image');
            
            $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$getattributes['userid']." and te.type='user' and te.subtype='file:profile:cover'";
            $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
            if(!empty($resultbirhtdate))
            {
                \Yii::$app->db->createCommand()->delete('tb_entities', ['owner_guid' => $getattributes['userid']])->execute();
                \Yii::$app->db->createCommand()->delete('tb_entities_metadata', ['guid' => $getattributes['userid']])->execute();
                return array('status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'No Cover Image');
        }
        else
            return array('status'=>0, 'error'=>'No User');
    }
    
    public function Cropimage($filename, $filetype, $width, $height)
    {
        switch(strtolower($filetype)) 
        { 
            case 'image/jpeg': 
                $image = imagecreatefromjpeg($filename); 
                break; 
            case 'image/jpg': 
                $image = imagecreatefromjpeg($filename); 
                break;     
            case 'image/png': 
                $image = imagecreatefrompng($filename); 
                break; 
            case 'image/gif': 
                $image = imagecreatefromgif($filename); 
                break; 
            default: 
                break;
        } 

        // Target dimensions 
        $max_width = $width; 
        $max_height = $height; 

        // Get current dimensions 
        $old_width  = imagesx($image); 
        $old_height = imagesy($image); 

        // Calculate the scaling we need to do to fit the image inside our frame 
        $scale      = min($max_width/$old_width, $max_height/$old_height); 

        // Get the new dimensions 
        $new_width  = ceil($scale*$old_width); 
        $new_height = ceil($scale*$old_height); 

        // Create new empty image 
        $new = imagecreatetruecolor($new_width, $new_height); 

        // Resize old image into new 
        imagecopyresampled($new, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height); 
        return $new;
    }
    //edit profile image
    //send userid and image path
    public function actionEditimage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $user = Users::find()->where(['guid' => $attributes['id']])->one(); 
        if(count($user) > 0 )
        {
            if(isset($_FILES["profilephoto"]["name"]) && !empty($_FILES["profilephoto"]["name"]))
            {
                if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id']))
                {
                    mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'], 0755, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile'))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile', 0755, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo'))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo', 0755, true);
                }
                $files = glob('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo/*'); // get all file names
                foreach($files as $file){ // iterate files
                  if(is_file($file))
                    unlink($file); // delete file
                }
                $extension = pathinfo($_FILES["profilephoto"]["name"], PATHINFO_EXTENSION);
                $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo/'.$name.".".$extension;
                $topbar_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo/topbar_'.$name.".".$extension;
                $smaller_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo/smaller_'.$name.".".$extension;
                $small_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo/small_'.$name.".".$extension;
                $larger_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo/larger_'.$name.".".$extension;
                $large_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/photo/large_'.$name.".".$extension;
                $datafile = 'profile/photo/'.$name.".".$extension;
                $filepatht = 'https://thetalentbook.co.in/tb_data/user/'.$attributes['id'].'/profile/photo/'.$name.".".$extension;

                $new = $this->Cropimage($_FILES['profilephoto']['tmp_name'], $_FILES['profilephoto']['type'], '2048', '1536');
                $newlarger = $this->Cropimage($_FILES['profilephoto']['tmp_name'], $_FILES['profilephoto']['type'], '170', '170');
                $newlarge = $this->Cropimage($_FILES['profilephoto']['tmp_name'], $_FILES['profilephoto']['type'], '100', '100');
                $newsmall = $this->Cropimage($_FILES['profilephoto']['tmp_name'], $_FILES['profilephoto']['type'], '50', '50');
                $newsmaller = $this->Cropimage($_FILES['profilephoto']['tmp_name'], $_FILES['profilephoto']['type'], '32', '32');
                $newtopbar = $this->Cropimage($_FILES['profilephoto']['tmp_name'], $_FILES['profilephoto']['type'], '20', '20');
                
                if(imagejpeg($new, $target_file, 90))
                {
                    imagejpeg($new, $target_file, 90);
                    imagejpeg($newlarger, $larger_file, 90);
                    imagejpeg($newlarge, $large_file, 90);
                    imagejpeg($newsmall, $small_file, 90);
                    imagejpeg($newsmaller, $smaller_file, 90);
                    imagejpeg($newtopbar, $topbar_file, 90);

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $attributes['id'],'type' => 'user',
                            'subtype' => 'file:profile:photo','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $datafile])->execute();
                    $entitymetaid = \Yii::$app->db->getLastInsertID();

                    $post = json_encode(array('post'=>'null:data'));
                    $tbobject = \Yii::$app->db->createCommand()->insert('tb_object', ['owner_guid' => $attributes['id'],'type' => 'user',
                            'subtype' => 'wall','time_created' => time(),'description' => $post])->execute();
                    $objectid = \Yii::$app->db->getLastInsertID();

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $objectid,'type' => 'object',
                            'subtype' => 'item_type','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => 'profile:photo'])->execute();

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $objectid,'type' => 'object',
                            'subtype' => 'item_guid','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $entitymetaid])->execute();

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $objectid,'type' => 'object',
                            'subtype' => 'poster_guid','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $attributes['id']])->execute();

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $objectid,'type' => 'object',
                            'subtype' => 'access','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => '3'])->execute();

                    return array('filepath'=>$filepatht,'status'=>1, 'error'=>'');
                }
                else
                    return array('status'=>0, 'error'=>'image not uploaded');
            }
            else
                return array('status'=>0, 'error'=>'image file not there');
        }
        else
            return array('status'=>0, 'error'=>'No User');
    }
    
    //edit profile image
    //send userid and image path
    public function actionEditcoverimage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $user = Users::find()->where(['guid' => $attributes['id']])->one(); 
        if(count($user) > 0 )
        {
            if(isset($_FILES["coverphoto"]["name"]) && !empty($_FILES["coverphoto"]["name"]))
            {
                if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id']))
                {
                    mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'], 0755, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/cover'))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/cover', 0755, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/cover/photo'))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/cover/photo', 0755, true);
                }
                $files = glob('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/cover/photo/*'); // get all file names
                foreach($files as $file){ // iterate files
                  if(is_file($file))
                    unlink($file); // delete file
                }
                $extension = pathinfo($_FILES["coverphoto"]["name"], PATHINFO_EXTENSION);
                $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/cover/'.$name.".".$extension;
                $topbar_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/cover/topbar_'.$name.".".$extension;
                $smaller_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/cover/smaller_'.$name.".".$extension;
                $small_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/cover/small_'.$name.".".$extension;
                $larger_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/cover/larger_'.$name.".".$extension;
                $large_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/user/'.$attributes['id'].'/profile/cover/large_'.$name.".".$extension;
                $datafile = 'profile/cover/'.$name.".".$extension;
                $filepatht = 'https://thetalentbook.co.in/tb_data/user/'.$attributes['id'].'/profile/cover/'.$name.".".$extension;

                $new = $this->Cropimage($_FILES['coverphoto']['tmp_name'], $_FILES['coverphoto']['type'], '2048', '1536');
                $newlarger = $this->Cropimage($_FILES['coverphoto']['tmp_name'], $_FILES['coverphoto']['type'], '170', '170');
                $newlarge = $this->Cropimage($_FILES['coverphoto']['tmp_name'], $_FILES['coverphoto']['type'], '100', '100');
                $newsmall = $this->Cropimage($_FILES['coverphoto']['tmp_name'], $_FILES['coverphoto']['type'], '50', '50');
                $newsmaller = $this->Cropimage($_FILES['coverphoto']['tmp_name'], $_FILES['coverphoto']['type'], '32', '32');
                $newtopbar = $this->Cropimage($_FILES['coverphoto']['tmp_name'], $_FILES['coverphoto']['type'], '20', '20');
                
                if(imagejpeg($new, $target_file, 90))
                {
                    imagejpeg($new, $target_file, 90);
                    imagejpeg($newlarger, $larger_file, 90);
                    imagejpeg($newlarge, $large_file, 90);
                    imagejpeg($newsmall, $small_file, 90);
                    imagejpeg($newsmaller, $smaller_file, 90);
                    imagejpeg($newtopbar, $topbar_file, 90);

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $attributes['id'],'type' => 'user',
                            'subtype' => 'file:profile:cover','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $datafile])->execute();
                    $entitymetaid = \Yii::$app->db->getLastInsertID();

                    $post = json_encode(array('post'=>'null:data'));
                    $tbobject = \Yii::$app->db->createCommand()->insert('tb_object', ['owner_guid' => $attributes['id'],'type' => 'user',
                            'subtype' => 'wall','time_created' => time(),'description' => $post])->execute();
                    $objectid = \Yii::$app->db->getLastInsertID();

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $objectid,'type' => 'object',
                            'subtype' => 'item_type','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => 'profile:cover'])->execute();

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $objectid,'type' => 'object',
                            'subtype' => 'item_guid','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $entitymetaid])->execute();

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $objectid,'type' => 'object',
                            'subtype' => 'poster_guid','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $attributes['id']])->execute();

                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $objectid,'type' => 'object',
                            'subtype' => 'access','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => '3'])->execute();

                    return array('filepath'=>$filepatht,'status'=>1, 'error'=>'');
                }
                else
                    return array('status'=>0, 'error'=>'image not uploaded');
            }
            else
                return array('status'=>0, 'error'=>'image file not there');
        }
        else
            return array('status'=>0, 'error'=>'No User');
    }
    
    //Post upload
    public function actionPostupload()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        //$tt = getcwd();
        if(isset($attributes['description']) && !empty($attributes['description']))
        {
            $location = isset($attributes['location']) ? $attributes['location'] : '';
            $friend = isset($attributes['friend']) ? $attributes['friend'] : '';
            if(!empty($attributes['location']) && !empty($attributes['friend']))
                $description = json_encode(array('post'=>$attributes['description'], 'location'=>$attributes['location'], 'friend'=>$attributes['friend']));
            elseif(!empty($attributes['location']))
                $description = json_encode(array('post'=>$attributes['description'], 'location'=>$attributes['location']));
            elseif(!empty($attributes['friend']))
                $description = json_encode(array('post'=>$attributes['description'], 'friend'=>$attributes['friend']));
            else
                $description = json_encode(array('post'=>$attributes['description']));
            
            $object = new Object();
            $object->owner_guid = $attributes['userid'];
            $object->type = isset($attributes['type']) ? $attributes['type'] : 'user';
            $object->time_created =  time();
            $object->title = isset($attributes['title']) ? $attributes['title'] : '';
            $object->description = $description;
            $object->subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'wall';
            $object->category = isset($attributes['category']) ? $attributes['category'] : '';
            $object->subcategory = isset($attributes['subcategory']) ? $attributes['subcategory'] : '';
            $object->city = isset($attributes['city']) ? $attributes['city'] : '';
            $object->area = isset($attributes['area']) ? $attributes['area'] : '';
            $object->state = isset($attributes['state']) ? $attributes['state'] : '';
            if($object->save())
            {
                $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $object->guid,'type' => 'object',
                                'subtype' => 'poster_guid','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                $entityid = \Yii::$app->db->getLastInsertID();
                $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $attributes['userid']])->execute();

                $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $object->guid,'type' => 'object',
                        'subtype' => 'access','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                $entityid = \Yii::$app->db->getLastInsertID();
                $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => '3'])->execute();
                        
                if(isset($_FILES["imagepath"]["name"]) && !empty($_FILES["imagepath"]["name"]))
                {
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid))
                    {
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid, 0777, true);
                        if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall'))
                            mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall', 0777, true);
                        if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$attributes['filetype']))
                            mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$attributes['filetype'], 0777, true);

                    }
                    $extension = pathinfo($_FILES["imagepath"]["name"], PATHINFO_EXTENSION);
                    $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                    $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$attributes['filetype'].'/'.$name.".".$extension;

                    $datafile = 'ossnwall/'.$attributes['filetype'].'/'.$name.'.'.$extension;
                    $filepatht = 'https://thetalentbook.co.in/tb_data/object/'.$object->guid.'/ossnwall/'.$attributes['filetype'].'/'.$name.".".$extension;
                    if(move_uploaded_file($_FILES["imagepath"]["tmp_name"], $target_file))
                    {
                        if($attributes['filetype'] == 'image')
                            $type = 'file:wallphoto';
                        elseif($attributes['filetype'] == 'video')
                            $type = 'file:wallvideo';
                        elseif($attributes['filetype'] == 'pdf')
                            $type = 'file:wallpdf';

                        $entity = new Entities();
                        $entity->owner_guid = $object->guid;
                        $entity->type = 'object';
                        $entity->subtype = $type;
                        $entity->time_created = time();
                        $entity->permission = 2;
                        $entity->active = 1;
                        if($entity->save())
                        {
                            $entityid = $entity->guid;
                            $entitymeta = new Entitiesmetadata();
                            $entitymeta->guid = $entityid;
                            $entitymeta->value = $datafile;
                            $entitymeta->save();
                        }
                    }
                }
                $objectinfo = Object::find()->where(['guid' => $object->guid])->one();
                $description1 = json_decode($objectinfo['description']);
                if(!empty($description1->location) && !empty($description1->friend))
                {
                    $user = Users::find()->where(['guid' => $description1->friend])->one();
                    $description = json_encode(array('post'=>$attributes['description'], 'location'=>$description1->location, 
                        'friend'=>$user['first_name'].' '.$user['last_name']));
                }
                elseif(!empty($description1->location))
                    $description = json_encode(array('post'=>$attributes['description'], 'location'=>$description1->location));
                elseif(!empty($description1->friend))
                {
                    $user = Users::find()->where(['guid' => $description1->friend])->one();
                    $description = json_encode(array('post'=>$attributes['description'], 
                        'friend'=>$user['first_name'].' '.$user['last_name']));
                }
                else
                    $description = json_encode(array('post'=>$attributes['description']));
                
                $description = isset($description1->post) ? $description1->post : '';
                $location = isset($description1->location) ? $description1->location : '';
                $friendtag = isset($description1->friend) ? $description1->friend : '';
                
                $birthdate = $gender = $profilephoto  = '';
                if($objectinfo['type'] == 'user')
                    $usercheck = Users::find()->where(['guid' => $objectinfo['owner_guid']])->one();
                elseif($value['type'] == 'group')
                {
                    $objcheck = Object::find()->where(['guid' => $objectinfo['owner_guid']])->one();
                    $usercheck = Users::find()->where(['guid' => $objcheck['owner_guid']])->one();
                }
                
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='gender'";
                $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultgender))
                    $gender = $resultgender['value'];

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='birthdate'";
                $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultbirhtdate))
                   $birthdate = $resultbirhtdate['value']; 

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$objectinfo['owner_guid']." and te.type='user' and te.subtype='file:profile:photo'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$objectinfo['owner_guid'].'/'.$resultphoto['value']; 
                
                $uploadfile = $type = '';
                $sql = "select tem.*, te.subtype from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.type='object' "
                        . "and (te.subtype='file:wallphoto' or te.subtype='file:wallvideo' or te.subtype='file:wallpdf') and te.owner_guid=".$objectinfo['guid'];
                $photoupload = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($photoupload))
                {
                    if($photoupload['subtype'] == 'file:wallphoto')
                    {
                        $type = 'image';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                    elseif($photoupload['subtype'] == 'file:wallvideo')
                    {
                        $type = 'video';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                    elseif($photoupload['subtype'] == 'file:wallpdf')
                    {
                        $type = 'pdf';
                        $uploadfile = 'https://thetalentbook.co.in/tb_data/object/'.$objectinfo['guid'].'/'.$photoupload['value'];
                    }
                }
                
                $data = array('post_id'=>$objectinfo['guid'],'userid'=>$objectinfo['owner_guid'], 'type'=>$objectinfo['type'], 
                    'title'=>$objectinfo['title'], 'description'=>$description, 'friendtag'=>$friendtag, 'location'=>$location, 
                    'subtype'=>$objectinfo['subtype'], 'category'=>$objectinfo['category'],
                    'subcategory'=>$objectinfo['subcategory'], 'city'=>$objectinfo['city'], 'state'=>$objectinfo['state'], 'area'=>$objectinfo['area'], 'timecreated' =>$objectinfo['time_created'], 'uploadfile'=>$uploadfile, 'filetype'=>$type, 'owner'=>array('userid'=>$usercheck['guid'],
                    'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                    'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                    'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                    'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'birthdate'=>$birthdate, 'gender'=>$gender, 
                    'profilephoto'=>$profilephoto));
                return array('data'=>$data,'status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //get message
    public function actionGetmessage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_messages where message_from=".$attributes['userid'];
        $messages = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($messages) > 0 )
        {
            $data = array();
            foreach($messages as $key=>$value)
            {
                $usercheck = Users::find()->where(['guid' => $value['message_to']])->one();
                $data[] = array('message'=>$value['message'], 'userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                    'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                    'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                    'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                    'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes']);
            }
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //insert message
    public function actionCreatemessage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['message']) && !empty($attributes['message']))
        {
            $data = array();
            $message = new Messages();
            $message->message_from = $attributes['userid'];
            $message->message_to = $attributes['relateuserid'];
            $message->message = $attributes['message'];
            $message->viewed = 0;
            $message->time = time();
            if($message->save(false)) {
                $usercheck = Users::find()->where(['guid' => $attributes['relateuserid']])->one();
                $data = array('messageid'=>$message->id,'message'=>$attributes['message'], 'userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                    'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                    'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                    'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                    'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes']);
                return array('data'=>$data,'status'=>1, 'error'=>'');
            }
            else
                return array('status'=>0, 'error'=>'');    
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //activate message
    public function actionActivatemessage()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['messageid']) && !empty($attributes['messageid']))
        {
            $message = Messages::find()->where(['id' => $attributes['messageid']])->one();
            $message->viewed = 1;
            $message->save(false);
            return array('status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //get message
    public function actionGetfeedback()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_feedbacks where guid=".$attributes['userid'];
        $messages = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($messages) > 0 )
        {
            $data = array();
            foreach($messages as $key=>$value)
            {
                $usercheck = Users::find()->where(['guid' => $value['guid']])->one();
                $data[] = array('feedback'=>$value['feedback'], 'category'=>$value['category'], 'id'=>$value['id'],'userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                    'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                    'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                    'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                    'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes']);
            }
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //insert message
    public function actionCreatefeedback()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['feedback']) && !empty($attributes['feedback']))
        {
            $data = array();
            $message = new Feedbacks();
            $message->category = $attributes['category'];
            $message->guid = $attributes['userid'];
            $message->feedback = $attributes['feedback'];
            $message->save();
            $usercheck = Users::find()->where(['guid' => $attributes['userid']])->one();
            $data = array('id'=>$message->id,'message'=>$attributes['feedback'], 'userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //get friends
    public function actionGetfriends()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_relationships where type='friend:request' and relation_from='".$attributes['userid']."' order by relation_id"
                . " desc limit ".$attributes['minlimit']." , ".$attributes['maxlimit'];
        $relations = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($relations) > 0 )
        {
            $friends = '';
            foreach($relations as $key=>$value)
            {
                $profilephoto = '';
                $usercheck = Users::find()->where(['guid' => $value['relation_to']])->one();
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$usercheck['guid']." and te.type='user' and te.subtype='file:profile:photo'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$usercheck['guid'].'/'.$resultphoto['value']; 

                $friends[]  = array('userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                    'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                    'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                    'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                    'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'profilephoto'=>$profilephoto);
            }
            return array('data'=>$friends,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //get friends
    public function actionGetgroups()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_relationships where (type='group:join' || type='group:join:approve') and relation_from=".$attributes['userid'];
        $relations = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($relations) > 0 )
        {
            $friends = '';
            foreach($relations as $key=>$value)
            {
                $object = Object::find()->where(['guid' => $value['relation_to']])->one();
                $profilephoto = '';
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$object['guid']." and te.type='object' and te.subtype='file:cover'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = 'https://thetalentbook.co.in/tb_data/object/'.$object['guid'].'/cover/'.$resultphoto['value']; 

                $usercheck = Users::find()->where(['guid' => $object['owner_guid']])->one();
                $friends[]  = array('groupid'=>$object['guid'], 'time_created'=>$object['time_created'], 'groupphoto'=>$profilephoto,
                    'title'=>$object['title'], 'description'=>$object['description'], 'subtype'=>$object['subtype'],
                    'category'=>$object['category'], 'subcategory'=>$object['subcategory'], 'city'=>$object['city'],
                    'area'=>$object['area'], 'state'=>$object['state'], 'owner'=>array('userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                    'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                    'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                    'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                    'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes']));
            }
            return array('data'=>$friends,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //creategroup
    public function actionCreategroup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $friends = '';
        if(isset($attributes['title']) && !empty($attributes['title']))
        {
            $newobject = new Object();
            $newobject->owner_guid = $attributes['userid'];
            $newobject->type = 'user';
            $newobject->time_created = time();
            $newobject->title = $attributes['title'];
            $newobject->description = isset($attributes['description']) ? $attributes['description'] : '';
            $newobject->subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'ossngroup';
            $newobject->category = isset($attributes['category']) ? $attributes['category'] : '';
            $newobject->subcategory = isset($attributes['subcategory']) ? $attributes['subcategory'] : '';
            $newobject->city = isset($attributes['city']) ? $attributes['city'] : '';
            $newobject->area = isset($attributes['area']) ? $attributes['area'] : '';
            $newobject->state = isset($attributes['state']) ? $attributes['state'] : '';
            $newobject->save(false);
            $filepatht = '';
            if(isset($_FILES["groupphoto"]["name"]) && !empty($_FILES["groupphoto"]["name"]))
            {
                if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$newobject->guid))
                {
                    mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$newobject->guid, 0755, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$newobject->guid.'/cover'))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$newobject->guid.'/cover', 0755, true);
                }
                $extension = pathinfo($_FILES["groupphoto"]["name"], PATHINFO_EXTENSION);
                $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$newobject->guid.'/cover/'.$name.".".$extension;

                $datafile = 'cover/'.$name.'.'.$extension;
                $filepatht = 'https://thetalentbook.co.in/tb_data/object/'.$newobject->guid.'/cover/'.$name.".".$extension;
                if(move_uploaded_file($_FILES["groupphoto"]["tmp_name"], $target_file))
                {
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $newobject->guid,'type' => 'object',
                            'subtype' => 'file:cover','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $datafile])->execute();
                }
            }
            $privacy = isset($attributes['privacy']) ? $attributes['privacy'] : '2';
            $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $newobject->guid,'type' => 'object',
                            'subtype' => 'membership','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
            $entityid = \Yii::$app->db->getLastInsertID();
            $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $privacy])->execute();
            $object = Object::find()->where(['guid' => $newobject->guid])->one();
            $usercheck = Users::find()->where(['guid' => $object['owner_guid']])->one();
            $privacycheck = ($attributes['privacy'] == 1) ? 'Closed' : 'Public';
            $friends[]  = array('groupid'=>$object['guid'], 'time_created'=>$object['time_created'], 'title'=>$object['title'], 
                'description'=>$object['description'], 'subtype'=>$object['subtype'], 'category'=>$object['category'], 'subcategory'=>$object['subcategory'], 
                'city'=>$object['city'], 'area'=>$object['area'], 'state'=>$object['state'], 'owner'=>array('userid'=>$usercheck['guid'], 
                    'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 
                    'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'groupphoto'=>$filepatht,
                    'privacy'=>$privacycheck));
            return array('data'=>$friends,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //creategroup
    public function actionEditgroup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $friends = '';
        if(isset($attributes['groupid']) && !empty($attributes['groupid']))
        {
            $newobject = Object::find()->where(['guid' => $attributes['groupid']])->one();
            $newobject->title = $attributes['title'];
            $newobject->description = isset($attributes['description']) ? $attributes['description'] : '';
            $newobject->subtype = isset($attributes['subtype']) ? $attributes['subtype'] : '';
            $newobject->category = isset($attributes['category']) ? $attributes['category'] : '';
            $newobject->subcategory = isset($attributes['subcategory']) ? $attributes['subcategory'] : '';
            $newobject->city = isset($attributes['city']) ? $attributes['city'] : '';
            $newobject->area = isset($attributes['area']) ? $attributes['area'] : '';
            $newobject->state = isset($attributes['state']) ? $attributes['state'] : '';
            $newobject->save(false);
            
            $filepatht = '';
            if(isset($_FILES["groupphoto"]["name"]) && !empty($_FILES["groupphoto"]["name"]))
            {
                if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$attributes['groupid']))
                {
                    mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$attributes['groupid'], 0755, true);
                    if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$attributes['groupid'].'/cover'))
                        mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$attributes['groupid'].'/cover', 0755, true);
                }
                $extension = pathinfo($_FILES["groupphoto"]["name"], PATHINFO_EXTENSION);
                $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$attributes['groupid'].'/cover/'.$name.".".$extension;

                $datafile = 'cover/'.$name.'.'.$extension;
                $filepatht = 'https://thetalentbook.co.in/tb_data/object/'.$newobject->guid.'/cover/'.$name.".".$extension;
                if(move_uploaded_file($_FILES["groupphoto"]["tmp_name"], $target_file))
                {
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $newobject->guid,'type' => 'object',
                            'subtype' => 'file:cover','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                    $entityid = \Yii::$app->db->getLastInsertID();
                    $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $datafile])->execute();
                }
            }
            $privacy = isset($attributes['privacy']) ? $attributes['privacy'] : '2';
            $newentity = Entities::find()->where(['owner_guid' => $attributes['groupid'], 'type'=>'object', 'subtype'=>'membership'])->one();
            $newentitymeta = Entitiesmetadata::find()->where(['guid' => $newentity['guid']])->one();
            $newentitymeta->value = $privacy;
            $newentitymeta->save(false);
            $object = Object::find()->where(['guid' => $attributes['groupid']])->one();
            $usercheck = Users::find()->where(['guid' => $object['owner_guid']])->one();
            $privacycheck = ($newentitymeta['value'] == 1) ? 'Closed' : 'Public';
            $friends[]  = array('groupid'=>$object['guid'], 'time_created'=>$object['time_created'],
                'title'=>$object['title'], 'description'=>$object['description'], 'subtype'=>$object['subtype'],
                'category'=>$object['category'], 'subcategory'=>$object['subcategory'], 'city'=>$object['city'],
                'area'=>$object['area'], 'state'=>$object['state'], 'owner'=>array('userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'privacy'=>$privacycheck));
            return array('data'=>$friends,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //get all groups
    public function actionGetallgroups()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_object where title!=''";
        $groups = \Yii::$app->db->createCommand($sql)->queryAll();
        $data = array();
        if(count($groups) > 0 )
        {
            foreach($groups as $key=>$value)
            {
                $profilephoto = '';
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['guid']." and te.type='object' and te.subtype='file:cover'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = 'https://thetalentbook.co.in/tb_data/object/'.$object['guid'].'/cover/'.$resultphoto['value']; 
                
                $newentity = Entities::find()->where(['owner_guid' => $attributes['groupid'], 'type'=>'object', 'subtype'=>'membership'])->one();
                $newentitymeta = Entitiesmetadata::find()->where(['guid' => $newentity['guid']])->one();
                $privacycheck = ($newentitymeta['value'] == 1) ? 'Closed' : 'Public';
                $data[] = array('groupid'=>$value['guid'], 'owner_guid'=>$value['owner_guid'], 'time_created'=>$value['time_created'],'grouptype'=>$value['type'],
                'title'=>$value['title'], 'description'=>$value['description'], 'subtype'=>$value['subtype'], 'groupphoto'=>$profilephoto,
                'category'=>$value['category'], 'subcategory'=>$value['subcategory'], 'city'=>$value['city'],
                'area'=>$value['area'], 'state'=>$value['state'], 'privacy'=>$privacycheck);
            }
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //create friendrequest
    public function actionFriendrequest()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['relationto']) && !empty($attributes['relationto']))
        {
            $relation = new Relationships();
            $relation->relation_from = $attributes['userid'];
            $relation->relation_to = $attributes['relationto'];
            $relation->type = 'friend:request';
            $relation->time = time();
            $relation->save();
            return array('status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //group request
    public function actionGrouprequest()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['relationto']) && !empty($attributes['relationto']))
        {
            $relation = new Relationships();
            $relation->relation_from = $attributes['userid'];
            $relation->relation_to = $attributes['relationto'];
            $relation->type = 'group:join';
            $relation->time = time();
            $relation->save();
            return array('status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //accept friend request
    public function actionAcceptfriend()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['relationto']) && !empty($attributes['relationto']))
        {
            $relation = new Relationships();
            $relation->relation_from = $attributes['userid'];
            $relation->relation_to = $attributes['relationto'];
            $relation->type = 'friend:request';
            $relation->time = time();
            $relation->save();
            return array('status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //accept group request
    public function actionAcceptgroup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['relationto']) && !empty($attributes['relationto']))
        {
            $relation = new Relationships();
            $relation->relation_from = $attributes['userid'];
            $relation->relation_to = $attributes['relationto'];
            $relation->type = 'group:join:approve';
            $relation->time = time();
            $relation->save();
            return array('status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //notifiactions
    public function actionGetnotifications()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_notifications where owner_guid=".$attributes['userid'];
        $notifications = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($notifications) > 0 )
        {
            foreach($notifications as $key=>$value)
            {
                $usercheck = Users::find()->where(['guid' => $value['poster_guid']])->one();
                if($value['type'] == 'like:post' || $value['type'] == 'share:post' || $value['type'] == 'comments:post' || $value['type'] == 'like:post:group:wall')
                {
                    $description = $description1 = '';
                    $object = Object::find()->where(['guid' => $value['subject_guid']])->one();
                    if(!empty($object['description']))
                        $description1 = json_decode($object['description']);
                    if(isset($description1->post) && (!empty($description1->post)))
                        $description = $description1->post;
                    
                    if($value['type'] == 'like:post')
                        $message = 'Liked the post';
                    if($value['type'] == 'share:post')
                        $message = 'Shared the post';
                    if($value['type'] == 'comments:post')
                        $message = 'Commented the post';

                    $profilephoto = $owner = '';
                    //$usercheck = Users::find()->where(['guid' => $object['owner_guid']])->one();
                    if(!empty($usercheck)) {
                        $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$usercheck['guid']." and te.type='user' and te.subtype='file:profile:photo'";
                        $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                        if(!empty($resultphoto))
                            $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$usercheck['guid'].'/'.$resultphoto['value']; 
                        $owner = array('userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'profilephoto'=>$profilephoto);
                    }
                    $data[] = array('postid'=>$value['subject_guid'], 'description'=>$description, 'message'=>$message, 
                        'owner'=>$owner); 
                    
                }
                elseif($value['type'] == 'group:approverequest' || $value['type'] == 'group:joinrequest')
                {
                    $object = Object::find()->where(['guid' => $value['subject_guid']])->one();
                    $description = $object['description'];
                    if($value['type'] == 'group:approverequest')
                        $message = 'Group approved request';
                    if($value['type'] == 'group:joinrequest')
                        $message = 'Group joined request';
                    $profilephoto = $owner = '';
                    //$usercheck = Users::find()->where(['guid' => $object['owner_guid']])->one();
                    if(!empty($usercheck)) {
                        $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$usercheck['guid']." and te.type='user' and te.subtype='file:profile:photo'";
                        $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                        if(!empty($resultphoto))
                            $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$usercheck['guid'].'/'.$resultphoto['value']; 
                        $owner = array('userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'profilephoto'=>$profilephoto);
                    }
                    $data[] = array('postid'=>$value['subject_guid'], 'description'=>$description, 'message'=>$message, 
                        'owner'=>$owner); 
                }
                elseif($value['type'] == 'comments:entity:file:profile:photo' || $value['type'] == 'like:entity:file:profile:cover' || $value['type'] == 'like:entity:file:profile:photo')
                {
                    $sql = "select tem.*, te.owner_guid from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.guid=".$value['poster_guid'];
                    $result = \Yii::$app->db->createCommand($sql)->queryOne();
                    if($value['type'] == 'comments:entity:file:profile:photo')
                        $message = 'Commented profile photo';
                    if($value['type'] == 'like:entity:file:profile:cover')
                        $message = 'Liked profile cover photo';
                    if($value['type'] == 'like:entity:file:profile:photo')
                        $message = 'Liked profile photo';

                    $profilephoto = $owner = '';
                    //$usercheck = Users::find()->where(['guid' => $result['owner_guid']])->one();
                    if(!empty($usercheck)) {
                        $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$usercheck['guid']." and te.type='user' and te.subtype='file:profile:photo'";
                        $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                        if(!empty($resultphoto))
                            $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$usercheck['guid'].'/'.$resultphoto['value']; 
                        $owner = array('userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'profilephoto'=>$profilephoto);
                    }
                    $data[] = array('postid'=>$result['guid'], 'description'=>$result['value'], 'message'=>$message, 
                        'owner'=>$owner); 
                }
            }
            if(!empty($data))
            {
                $min = $attributes['minlimit'];
                $max = $attributes['maxlimit'];
                $index = ($attributes['maxlimit'] - $attributes['minlimit']) +1;
                $data = array_slice($data, $min, $index);
            }
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //getcomment liked users
    public function actionGetcommentliked()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select tem.*, ta.owner_guid, ta.id as tid, te.subtype as subtype from tb_annotations as ta, tb_entities as te, tb_entities_metadata as tem where "
                . "ta.id=te.owner_guid and te.guid=tem.guid and ta.id=".$attributes['commentid']." and "
                . "(ta.type='comments:post' || ta.type='file:comment:photo') and te.type='annotation'";
        $comments = \Yii::$app->db->createCommand($sql)->queryOne();
        if(!empty($comments))
        {
            $commentinfo = array();
            $sql = "select * from tb_likes where subject_id=".$comments['tid']." and type='annotation'";
            $likeinfo = \Yii::$app->db->createCommand($sql)->queryAll();
            foreach($likeinfo as $key=>$value)    
            {    
                $usercheck = Users::find()->where(['guid' => $value['guid']])->one();
                $commentinfo[] = array('userid'=>$usercheck['guid'], 'type'=>$usercheck['type'], 
                        'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                        'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                        'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                        'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes']);
            }
            return array('data'=>$commentinfo,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }

    //check userinfo
    public function actionCheckuser()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $usercheck = Users::find()->where(['username' => $attributes['username']])->one();
        if(!empty($usercheck))
            return array('status'=>1, 'error'=>'');
        else
            return array('status'=>0, 'error'=>'');
    }

    public function actionForgetpassword()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $usercheck = Users::find()->where(['username' => $attributes['username']])->one();
        $btext = '';
        if(!empty($usercheck))
        {
            $btext = '<p>Please clicking on the link below:</p></br>';
            $btext .= '<p>https://thetalentbook.co.in/uservalidate/resetpassword/'.$usercheck['guid'].'</p></br>';
            $btext .= '<p>You may copy and paste the address to your browser manually in case the link does not work.</p></br>';
            $btext .= '<p>https://thetalentbook.co.in/';

            \yii::$app->mailer->compose()
            ->setFrom(\yii::$app->params['adminEmail'])
            ->setTo($usercheck['username'])
            ->setSubject($usercheck['first_name'].' please confirm your email address for Talentbook!')
            ->setTextBody($btext)
            ->send();
            return array('userid'=>$usercheck['guid'],'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }

    public function actionChangepassword()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $usercheck = Users::find()->where(['username' => $attributes['username']])->one();
        if(!empty($usercheck))
        {
            $salt = substr(uniqid(), 5);
            $password = md5($attributes['password'] . $salt);
            $usercheck ->password = $password;
            $usercheck ->salt = $salt;
            if($usercheck ->save(false))
                return array('userid'=>$usercheck ['guid'],'status'=>1, 'error'=>'');
            else
                return array('status'=>0, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }

    public function actionRegister()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        
        if ($attributes['usertype'] == 'email')
            $activation = md5($attributes['password'] . time() . rand());
        else
            $activation = '';

        $salt = substr(uniqid(), 5);

        $password = md5($attributes['password'] . $salt);
        $user = new Users();
        $user->type = $attributes['type'];
        $user->username = $attributes['username'];
        $user->email = isset($attributes['email']) ? $attributes['email'] : '';
        $user->password = $password;
        $user->salt = $salt;
        $user->first_name = $attributes['first_name'];
        $user->last_name = $attributes['last_name'];
        $user->activation = $activation;
        $user->time_created = time();
        $user->mobile = isset($attributes['mobile']) ? $attributes['mobile'] : '';
        $user->college = isset($attributes['college']) ? $attributes['college'] : '';
        $user->location = isset($attributes['location']) ? $attributes['location'] : '';
        $user->description = isset($attributes['description']) ? $attributes['description'] : '';
        $user->work = isset($attributes['work']) ? $attributes['work'] : '';
        $user->professionalskill = isset($attributes['professionalskill']) ? $attributes['professionalskill'] : '';
        $user->school = isset($attributes['school']) ? $attributes['school'] : '';
        $user->othermobile = isset($attributes['othermobile']) ? $attributes['othermobile'] : '';
        $user->aboutyou = isset($attributes['aboutyou']) ? $attributes['aboutyou'] : '';
        $user->nickname = isset($attributes['nickname']) ? $attributes['nickname'] : '';
        $user->favquotes = isset($attributes['favquotes']) ? $attributes['favquotes'] : '';
        if($user->save(false))
        {
            if ($attributes['usertype'] == 'email') {
                $btext = '<p>Before you can start using Talentbook, you must confirm your email address.</p>';
                $btext .= '<p>Please confirm your email address by clicking on the link below:</p>';
                $btext .= '<p>https://thetalentbook.co.in/uservalidate/activate/'.$user->guid.'/'.$activation.'</p>';
                $btext .= '<p>You may copy and paste the address to your browser manually in case the link does not work.</p>';
                $btext .= '<p>https://thetalentbook.co.in/';

                \yii::$app->mailer->compose()
                ->setFrom(\yii::$app->params['adminEmail'])
                ->setTo($attributes['username'])
                ->setSubject($attributes['first_name'].' please confirm your email address for Talentbook!')
                ->setHtmlBody($btext)
                ->send();
            }
            if(isset($attributes['gender']) && !empty($attributes['gender']))
            {
                $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $user->guid,'type' => 'user',
                    'subtype' => 'gender','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                $entityid = \Yii::$app->db->getLastInsertID();
                $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $attributes['gender']])->execute();
            }
            return array('status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }

    public function smsgatewaycenter_com_Send($mobile, $sendmessage, $debug = false) {
        $username = "RAGHAVENDRARAO";
        $password = "raghavendra123@";
        $message = $sendmessage;
        $sender = "TALENT"; //ex:INVITE
        #$mobile = "7800859580";// for test
        $url = "login.bulksmsgateway.in/sendmessage.php?user=" . urlencode($username) . "&password=" . urlencode($password) . "&mobile=" . urlencode($mobile) . "&message=" . urlencode($sendmessage) . "&sender=" . urlencode($sender) . "&type=" . urlencode('3');
        #echo $url;exit;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);

        if ($debug) {
            echo "Response: <br><pre>" . $curl_scraped_page . "</pre><br>";
        }
        return($curl_scraped_page);
    }

    public function smsgatewaycenter_com_OTP($length = 4, $chars = '0123456789') {
        $chars_length = (strlen($chars) - 1);
        $string = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string)) {
            $r = $chars{rand(0, $chars_length)};
            if ($r != $string{$i - 1})
                $string .= $r;
        }
        return $string;
    }
    
    public function actionGetotp()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $smsotp = $this->smsgatewaycenter_com_OTP();
        if($smsotp > 0)
        {
            $this->smsgatewaycenter_com_Send($attributes['username'], 'Dear User! Please authenticate your OTP. Your One Time password is: ' . $smsotp . ' sent to your mobile ' . $attributes['username'] . '', FALSE);
            return array('otp'=>$smsotp, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }  

    //search user or group
    public function actionSearchdata()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $data = array();
        if($attributes['type'] == 'user')
        {
            $sql = "select * from tb_users where first_name like '%".$attributes['name']."%' or last_name like '%".$attributes['name']."%' or "
                    . "username like '%".$attributes['name']."%' or email like '%".$attributes['name']."%'";
            $userinfo = \Yii::$app->db->createCommand($sql)->queryAll();
            foreach($userinfo as $key=>$value)
            {
                $birthdate = $gender = $profilephoto  = '';
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['guid']." and te.type='user' and te.subtype='gender'";
                $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultgender))
                    $gender = $resultgender['value'];

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['guid']." and te.type='user' and te.subtype='birthdate'";
                $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultbirhtdate))
                   $birthdate = $resultbirhtdate['value']; 

                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['guid']." and te.type='user' and te.subtype='file:profile:photo'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$value['guid'].'/'.$resultphoto['value']; 

                 $data[] = array('userid'=>$value['guid'], 'type'=>$value['type'], 'username'=>$value['username'], 'email'=>$value['email'], 
                    'first_name'=>$value['first_name'], 'last_name'=>$value['last_name'], 'mobile'=>$value['mobile'], 
                    'college'=>$value['college'], 'location'=>$value['location'], 'description'=>$value['description'], 'work'=>$value['work'], 
                    'school'=>$value['school'], 'professionalskill'=>$value['professionalskill'],  'othermobile'=>$value['othermobile'], 
                    'aboutyou'=>$value['aboutyou'], 'nickname'=>$value['nickname'], 'favquotes'=>$value['favquotes'], 'birthdate'=>$birthdate, 
                    'gender'=>$gender, 'profilephoto'=>$profilephoto);
            }
            if(!empty($data))
                return array('data'=>$data,'status'=>1, 'error'=>'');
            else
                return array('status'=>0, 'error'=>'');
        }
        elseif($attributes['type'] == 'group')
        {
            $sql = "select * from tb_object where title like '%".$attributes['name']."%' or category like '%".$attributes['name']."%'"
                    . "or subcategory like '%".$attributes['name']."%'";
            $userinfo = \Yii::$app->db->createCommand($sql)->queryAll();
            foreach($userinfo as $key=>$value)
            {
                $profilephoto  = '';
                $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['guid']." and te.type='object' and te.subtype='file:cover'";
                $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                if(!empty($resultphoto))
                    $profilephoto = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/cover/'.$resultphoto['value']; 

                $data[] = array('userid'=>$value['guid'], 'type'=>$value['type'], 'title'=>$value['title'], 'description'=>$value['description'], 
                    'subtype'=>$value['subtype'], 'category'=>$value['category'], 'subcategory'=>$value['subcategory'], 
                    'city'=>$value['city'], 'state'=>$value['state'], 'area'=>$value['area'], 'profilephoto'=>$profilephoto);
            }
            if(!empty($data))
                return array('data'=>$data,'status'=>1, 'error'=>'');
            else
                return array('status'=>0, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
        
    }
    
    public function actionReport()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['option']) && !empty($attributes['option']))
        {
            $report = new Report();
            $report->userid = $attributes['userid'];
            $report->postid = $attributes['postid'];
            $report->option = $attributes['option'];
            $report->message = $attributes['message'];
            $report->save(false);
            $data = array('option'=>$attributes['option'], 'message'=>$attributes['message'], 'postid'=>$attributes['postid'], 'userid'=>$attributes['userid']);
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionGetreports()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_report";
        $reports = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($reports))
        {
            foreach($reports as $key=>$value)
            {
                $user = Users::find()->where(['guid' => $value['userid']])->one();
                $data[] = array('option'=>$value['option'], 'message'=>$value['message'], 'postid'=>$value['postid'], 'owner'=>array('userid'=>$user['guid'],
                    'type'=>$user['type'], 'username'=>$user['username'], 'email'=>$user['email'], 
                    'first_name'=>$user['first_name'], 'last_name'=>$user['last_name']));
            }
        }
        else
            return array('status'=>0, 'error'=>'');    
    }
    
    //get category
    public function actionGetcategory()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_maincateg_master";
        $category = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($category))
        {
            $data = array();
            foreach($category as $key=>$value)
                $data[] = array('catid'=>$value['categid'], 'categname'=>$value['categname']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //create category
    public  function actionCreatecategory()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $category = new Category();
        $category->categname = $attributes['category'];
        if($category->save(false)) {
            $data = array('catid'=>$category->categid, 'category'=>$attributes['category']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //get subcategory
    public function actionGetsubcategory()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_subcateg_master where categid in (".$attributes['catid'].")";
        $category = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($category))
        {
            $data = array();
            foreach($category as $key=>$value)
                $data[] = array('subcategid'=>$value['subcategid'], 'catid'=>$value['categid'], 'subcategname'=>$value['subcategname']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //create subcategory
    public  function actionCreatesubcategory()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $category = new Subcategory();
        $category->categid = $attributes['catid'];
        $category->subcategname = $attributes['subcategory'];
        if($category->save(false)) {
            $catdata = Category::find()->where(['categid' => $attributes['catid']])->one();
            $data = array('catid'=>$attributes['catid'], 'category'=>$catdata['categname'], 'subcatid'=>$category->subcategid,
                'subcategory'=>$attributes['subcategory']);
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //get state
    public function actionGetstate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_states";
        $category = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($category))
        {
            $data = array();
            foreach($category as $key=>$value)
                $data[] = array('StateID'=>$value['StateID'], 'StateName'=>$value['StateName']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //create category
    public  function actionCreatestate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $category = new States();
        $category->StateName = $attributes['state'];
        if($category->save(false)) {
            $data = array('StateID'=>$category->StateID, 'StateName'=>$attributes['state']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //get state
    public function actionGetcity()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_cities where StateID=".$attributes['StateID'];
        $category = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($category))
        {
            $data = array();
            foreach($category as $key=>$value)
                $data[] = array('city_id'=>$value['city_id'], 'city_name'=>$value['city_name'], 
                    'latitude'=>$value['latitude'], 'longitude'=>$value['longitude'], 'StateID'=>$value['StateID']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //create subcategory
    public  function actionCreatecity()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $category = new Cities();
        $category->StateID = $attributes['stateid'];
        $category->city_name = $attributes['city'];
        $category->latitude = isset($attributes['latitude']) ? $attributes['latitude'] : '';
        $category->longitude = isset($attributes['longitude']) ? $attributes['longitude'] : '';
        if($category->save(false)) {
            $catdata = States::find()->where(['StateID' => $attributes['stateid']])->one();
            $data = array('StateID'=>$attributes['stateid'], 'statename'=>$catdata['StateName'], 'cityid'=>$category->city_id,
                'city_name'=>$attributes['city'], 'latitude'=>$category->latitude, 'longitude'=>$category->longitude);
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //unfriend
    public function actionUnfriend()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        \Yii::$app->db->createCommand()->delete('tb_relationships', ['relation_from' => $attributes['userid'], 'relation_to' => $attributes['relationto'], 'type' => 'friend:request'])->execute();
        \Yii::$app->db->createCommand()->delete('tb_relationships', ['relation_from' => $attributes['relationto'], 'relation_to' => $attributes['userid'], 'type' => 'friend:request'])->execute();
        return array('status'=>1, 'error'=>'');
    }
    
    //ungroup
    public function actionUngroup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        \Yii::$app->db->createCommand()->delete('tb_relationships', ['relation_from' => $attributes['userid'], 'relation_to' => $attributes['relationto'], 'type' => 'group:join:approve'])->execute();
        \Yii::$app->db->createCommand()->delete('tb_relationships', ['relation_from' => $attributes['relationto'], 'relation_to' => $attributes['userid'], 'type' => 'group:join:approve'])->execute();
        return array('status'=>1, 'error'=>'');
    }
    
    public function actionCreatecredits()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $sql = "select count(*) as cuser from tb_users";
        $usercount = \Yii::$app->db->createCommand($sql)->queryOne(); 
        if($usercount > 0)
        {
            if($usercount['cuser'] < 1000000)
                $credits = '10000';
            elseif($usercount['cuser'] > 1000000 && $usercount['cuser'] < 10000000)
                $credits = '7500';
            elseif($usercount['cuser'] > 10000000 && $usercount['cuser'] < 50000000)
                $credits = '5000';
            elseif($usercount['cuser'] > 5000000 && $usercount['cuser'] < 100000000)
                $credits = '2500';
            elseif($usercount['cuser'] > 10000000 && $usercount['cuser'] < 1000000000)
                $credits = '1000';
            else
                $credits = 0;
            \Yii::$app->db->createCommand()->insert('tb_user_credits', ['userid' => $attributes['userid'],'credits' => $credits])->execute();
            
            $usercheck1 = Usercredits::find()->where(['userid' => 54])->one();
            $usercheck1->credits = $usercheck1->credits - $credits;
            $usercheck1->save();
            
            $usercheck = Users::find()->where(['guid' => $attributes['userid']])->one();
            $data = array('userid'=>$usercheck['guid'],'credits' => $credits,
                'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 
                'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name']);
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionTransfercredits()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $usercheck2 = Usercredits::find()->where(['userid' => $attributes['userid']])->one();
        if($usercheck2->credits >= $attributes['credits'])
        {
            $usercredits2 = $usercheck2->credits - $attributes['credits'];
            $usercheck2->credits = $usercredits2;
            $usercheck2->save();
            
            $usercheck1 = Usercredits::find()->where(['userid' => $attributes['receiveuser']])->one();
            $usercheck1->credits = $usercheck1->credits + $attributes['credits'];
            $usercheck1->save();
            
            \Yii::$app->db->createCommand()->insert('tb_credit_history', ['userid' => $attributes['userid'],'credits' => $attributes['credits'], 'receive_userid'=>$attributes['receiveuser']])->execute();
            
            $usercheck = Users::find()->where(['guid' => $attributes['userid']])->one();
            $data = array('userid'=>$usercheck['guid'],'credits' => $usercredits2,
                'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 
                'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name']);
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'no available credits');
    }
    
    public function actionGetallcredits()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_user_credits";
        $credits = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($credits))
        {
            foreach($credits as $key=>$value)
            {
                $usercheck = Users::find()->where(['guid' => $value['userid']])->one();
                $data[] = array('userid'=>$usercheck['guid'],'credits' => $value['credits'],
                    'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 
                    'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name']);
            }
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'no available credits');
    }
    
    public function actionGetallhistory()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_credit_history";
        $credits = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($credits))
        {
            foreach($credits as $key=>$value)
            {
                $user = Users::find()->where(['guid' => $value['userid']])->one();
                $receiveuser = Users::find()->where(['guid' => $value['receive_userid']])->one();
                
                $data[] = array('credits' => $value['credits'], 'owner'=>array('userid'=>$user['guid'],
                    'type'=>$user['type'], 'username'=>$user['username'], 'email'=>$user['email'], 
                    'first_name'=>$user['first_name'], 'last_name'=>$user['last_name']), 
                    'receive_owner'=>array('userid'=>$receiveuser['guid'],
                    'type'=>$receiveuser['type'], 'username'=>$receiveuser['username'], 'email'=>$receiveuser['email'], 
                    'first_name'=>$receiveuser['first_name'], 'last_name'=>$receiveuser['last_name']));
            }
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'no history available');
    }
    
    public function actionGetuserhistory()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_credit_history where userid=".$attributes['userid'];
        $credits = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($credits))
        {
            foreach($credits as $key=>$value)
            {
                $user = Users::find()->where(['guid' => $value['userid']])->one();
                $receiveuser = Users::find()->where(['guid' => $value['receive_userid']])->one();
                
                $data[] = array('credits' => $value['credits'], 'owner'=>array('userid'=>$user['guid'],
                    'type'=>$user['type'], 'username'=>$user['username'], 'email'=>$user['email'], 
                    'first_name'=>$user['first_name'], 'last_name'=>$user['last_name']), 
                    'receive_owner'=>array('userid'=>$receiveuser['guid'],
                    'type'=>$receiveuser['type'], 'username'=>$receiveuser['username'], 'email'=>$receiveuser['email'], 
                    'first_name'=>$receiveuser['first_name'], 'last_name'=>$receiveuser['last_name']));
            }
            return array('data'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'no history available');
    }
    
    public function actionCreatepostcount()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $startdate = date("Y-m")."-01 00:00:00";
        $enddate = date("Y-m")."-28 23:59:59";
        $likecount = $sharecount = $commentcount = $tlikes = $tshare = $tcomment = 0;
        $sql = "select count(*) as clike from tb_likes where guid='".$attributes['userid']."' and created_date >='".$startdate."' and created_date <='".$enddate."'";
        $postlike = \Yii::$app->db->createCommand($sql)->queryOne();
        if(!empty($postlike))
        {
            $likecount = (int)$postlike['clike'];
            $tlikes = $likecount*0.01;
        }

        $sql = "select count(*) as cshare from tb_shares where guid='".$attributes['userid']."' and created_date >='".$startdate."' and created_date <='".$enddate."'";
        $postsharecount = \Yii::$app->db->createCommand($sql)->queryOne();
        if(!empty($postsharecount) && $postsharecount['cshare'] > 0)
        {
            $sharecount = (int)$postsharecount['cshare'];
            $tshare = $sharecount*0.02;
        }
        
        $sql = "select count(*) as ccomment from tb_annotations as ta, tb_entities as te, tb_entities_metadata as tem where ta.id=te.owner_guid and te.guid=tem.guid and ta.owner_guid='".$attributes['userid']."' and ta.created_date >='".$startdate."' and ta.created_date <='".$enddate."' and ta.type='comments:post' and te.type='annotation'";
        $comments = \Yii::$app->db->createCommand($sql)->queryOne();
        if(!empty($comments) && $comments['ccomment'] > 0)
        {
            $commentcount = (int)$comments['ccomment'];
            $tcomment = $commentcount*0.04;
        }
        $tcredits = $tlikes+$tshare+$tcomment;
        if($tcredits > 0)
        {
            $usercheck = UserCredits::find()->where(['userid' => $attributes['userid']])->one();
            $credits = $usercheck['credits']+$tcredits;

            $usercheck->credits = $credits;
            $usercheck->save(false);
            \Yii::$app->db->createCommand()->insert('tb_cron_history', ['userid' => $attributes['userid'],'credits' => $tcredits, 'message'=>"added credits to ".$startdate."to".$enddate])->execute();
            $data = array('userid'=>$usercheck['guid'],'credits' => $credits,
                    'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 
                    'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
        {
            \Yii::$app->db->createCommand()->insert('tb_cron_history', ['userid' => $attributes['userid'],'credits' => $tcredits, 'message'=>"added credits to ".$startdate."to".$enddate])->execute();
            return array('status'=>1, 'error'=>'no credits generated');
        }
    }

    public function actionGetcronhistory()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_cron_history";
        $reports = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($reports))
        {
            foreach($reports as $key=>$value)
            {
                $user = Users::find()->where(['guid' => $value['userid']])->one();
                $data[] = array('credits'=>$value['credits'], 'message'=>$value['message'], 'owner'=>array('userid'=>$user['guid'],
                    'type'=>$user['type'], 'username'=>$user['username'], 'email'=>$user['email'], 
                    'first_name'=>$user['first_name'], 'last_name'=>$user['last_name']));
            }
        }
        else
            return array('status'=>0, 'error'=>'');  
    }
    
    public function actionGetcasts()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_castes where status=1";
        $castes = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($castes))
        {
            $data = array();
            foreach($castes as $key=>$value)
                $data[] = array('id'=>$value['id'], 'caste'=>$value['caste']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionGetcourses()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from tb_courses where status=1";
        $courses = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($courses))
        {
            $data = array();
            foreach($courses as $key=>$value)
                $data[] = array('id'=>$value['id'], 'course'=>$value['name']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionPostscholarship()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $posts = new Pscholarship();
        $posts->user_id = isset($attributes['userid']) ? $attributes['userid'] : '';
        $posts->name = isset($attributes['name']) ? $attributes['name'] : '';
        $posts->description = isset($attributes['description']) ? $attributes['description'] : '';
        $posts->contact_number = isset($attributes['contact_number']) ? $attributes['contact_number'] : '';
        $posts->email = isset($attributes['email']) ? $attributes['email'] : '';
        $posts->website_link = isset($attributes['website_link']) ? $attributes['website_link'] : '';
        $posts->minage = isset($attributes['minage']) ? $attributes['minage'] : '';
        $posts->maxage = isset($attributes['maxage']) ? $attributes['maxage'] : '';
        $posts->gender = isset($attributes['gender']) ? $attributes['gender'] : '';
        $posts->education = isset($attributes['education']) ? $attributes['education'] : '';
        $posts->percentage = isset($attributes['percentage']) ? $attributes['percentage'] : '';
        $posts->caste = isset($attributes['caste']) ? $attributes['caste'] : '';
        $posts->state = isset($attributes['state']) ? $attributes['state'] : '';
        $posts->last_date = isset($attributes['last_date']) ? $attributes['last_date'] : '';
        $posts->application_address = isset($attributes['application_address']) ? $attributes['application_address'] : '';
        $posts->number_scholarship = isset($attributes['number_scholarship']) ? $attributes['number_scholarship'] : '';
        if($posts->save(false))
        {
            $post = Pscholarship::find()->where(['id' => $posts->id])->one();
            $data = array('id'=>$post['id'], 'userid'=>$post['user_id'], 'name'=>$post['name'], 'description'=>$post['description'], 
                    'contact_number'=>$post['contact_number'], 'email'=>$post['email'], 'website_link'=>$post['website_link'], 
                    'minage'=>$post['minage'], 'maxage'=>$post['maxage'], 'gender'=>$post['gender'], 
                    'education'=>$post['education'], 'percentage'=>$post['percentage'], 'caste'=>$post['caste'],
                    'state'=>$post['state'], 'last_date'=>$post['last_date'], 'application_address'=>$post['application_address'],
                    'number_scholarship'=>$post['number_scholarship']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionEditpostscholarship()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $posts = Pscholarship::find()->where(['id' => $attributes['id'], 'user_id' => $attributes['userid']])->one();
        $posts->name = isset($attributes['name']) ? $attributes['name'] : '';
        $posts->description = isset($attributes['description']) ? $attributes['description'] : '';
        $posts->contact_number = isset($attributes['contact_number']) ? $attributes['contact_number'] : '';
        $posts->email = isset($attributes['email']) ? $attributes['email'] : '';
        $posts->website_link = isset($attributes['website_link']) ? $attributes['website_link'] : '';
        $posts->minage = isset($attributes['minage']) ? $attributes['minage'] : '';
        $posts->maxage = isset($attributes['maxage']) ? $attributes['maxage'] : '';
        $posts->gender = isset($attributes['gender']) ? $attributes['gender'] : '';
        $posts->education = isset($attributes['education']) ? $attributes['education'] : '';
        $posts->percentage = isset($attributes['percentage']) ? $attributes['percentage'] : '';
        $posts->caste = isset($attributes['caste']) ? $attributes['caste'] : '';
        $posts->state = isset($attributes['state']) ? $attributes['state'] : '';
        $posts->last_date = isset($attributes['last_date']) ? $attributes['last_date'] : '';
        $posts->application_address = isset($attributes['application_address']) ? $attributes['application_address'] : '';
        $posts->number_scholarship = isset($attributes['number_scholarship']) ? $attributes['number_scholarship'] : '';
        if($posts->save(false))
        {
            $post = Pscholarship::find()->where(['id' => $attributes['id']])->one();
            $data = array('id'=>$post['id'], 'userid'=>$post['user_id'], 'name'=>$post['name'], 'description'=>$post['description'], 
                    'contact_number'=>$post['contact_number'], 'email'=>$post['email'], 'website_link'=>$post['website_link'], 
                    'minage'=>$post['minage'], 'maxage'=>$post['maxage'], 'gender'=>$post['gender'], 
                    'education'=>$post['education'], 'percentage'=>$post['percentage'], 'caste'=>$post['caste'],
                    'state'=>$post['state'], 'last_date'=>$post['last_date'], 'application_address'=>$post['application_address'],
                    'number_scholarship'=>$post['number_scholarship']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionDeletepostscholarship()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        \Yii::$app->db->createCommand()->delete('post_scholarship', ['id' => $attributes['id']])->execute();
        return array('status'=>1, 'error'=>'');
    }
    
    public function actionShowpostscholarshipadmin()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $today = date("Y-m-d");
        $sql = "select * from post_scholarship where status=1 and last_date >= '".$today."'";
        $pscholarship = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($pscholarship))
        {
            $data = array();
            foreach($pscholarship as $key=>$value)
                $data[] = array('id'=>$value['id'], 'userid'=>$value['user_id'], 'name'=>$value['name'], 'description'=>$value['description'], 
                    'contact_number'=>$value['contact_number'], 'email'=>$value['email'], 'website_link'=>$value['website_link'], 
                    'minage'=>$value['minage'], 'maxage'=>$value['maxage'], 'gender'=>$value['gender'], 
                    'education'=>$value['education'], 'percentage'=>$value['percentage'], 'caste'=>$value['caste'],
                    'state'=>$value['state'], 'last_date'=>$value['last_date'], 'application_address'=>$value['application_address'],
                    'number_scholarship'=>$value['number_scholarship']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionShowpostscholarship()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $today = date("Y-m-d");
        $sql = "select * from post_scholarship where status=1 and last_date >= '".$today."' and user_id=".$attributes['userid'];
        $pscholarship = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($pscholarship))
        {
            $data = array();
            foreach($pscholarship as $key=>$value)
                $data[] = array('id'=>$value['id'], 'userid'=>$value['user_id'], 'name'=>$value['name'], 'description'=>$value['description'], 
                    'contact_number'=>$value['contact_number'], 'email'=>$value['email'], 'website_link'=>$value['website_link'], 
                    'minage'=>$value['minage'], 'maxage'=>$value['maxage'], 'gender'=>$value['gender'], 
                    'education'=>$value['education'], 'percentage'=>$value['percentage'], 'caste'=>$value['caste'],
                    'state'=>$value['state'], 'last_date'=>$value['last_date'], 'application_address'=>$value['application_address'],
                    'number_scholarship'=>$value['number_scholarship']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionApplyscholarship()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $posts = new Ascholarship();
        $posts->user_id = isset($attributes['userid']) ? $attributes['userid'] : '';
        $posts->name = isset($attributes['name']) ? $attributes['name'] : '';
        $posts->father_name = isset($attributes['father_name']) ? $attributes['father_name'] : '';
        $posts->mobile_number = isset($attributes['mobile_number']) ? $attributes['mobile_number'] : '';
        $posts->email = isset($attributes['email']) ? $attributes['email'] : '';
        $posts->age = isset($attributes['age']) ? $attributes['age'] : '';
        $posts->gender = isset($attributes['gender']) ? $attributes['gender'] : '';
        $posts->education_details = isset($attributes['education_details']) ? $attributes['education_details'] : '';
        $posts->educational_qualification = isset($attributes['educational_qualification']) ? $attributes['educational_qualification'] : '';
        $posts->percentage = isset($attributes['percentage']) ? $attributes['percentage'] : '';
        $posts->caste = isset($attributes['caste']) ? $attributes['caste'] : '';
        $posts->state = isset($attributes['state']) ? $attributes['state'] : '';
        if($posts->save(false))
        {
            $post = Ascholarship::find()->where(['id' => $posts->id])->one();
            $data = array('id'=>$post['id'], 'userid'=>$post['user_id'], 'name'=>$post['name'], 
                    'father_name'=>$post['father_name'], 'mobile_number'=>$post['mobile_number'], 
                    'email'=>$post['email'], 'age'=>$post['age'], 'gender'=>$post['gender'], 
                    'education_details'=>$post['education_details'], 'percentage'=>$post['percentage'], 
                    'educational_qualification'=>$post['educational_qualification'], 'caste'=>$post['caste'],
                    'state'=>$post['state']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }    
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionEditapplyscholarship()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        $posts = Ascholarship::find()->where(['id' => $attributes['id'], 'user_id' => $attributes['userid']])->one();
        $posts->name = isset($attributes['name']) ? $attributes['name'] : '';
        $posts->father_name = isset($attributes['father_name']) ? $attributes['father_name'] : '';
        $posts->mobile_number = isset($attributes['mobile_number']) ? $attributes['mobile_number'] : '';
        $posts->email = isset($attributes['email']) ? $attributes['email'] : '';
        $posts->age = isset($attributes['age']) ? $attributes['age'] : '';
        $posts->gender = isset($attributes['gender']) ? $attributes['gender'] : '';
        $posts->education_details = isset($attributes['education_details']) ? $attributes['education_details'] : '';
        $posts->educational_qualification = isset($attributes['educational_qualification']) ? $attributes['educational_qualification'] : '';
        $posts->percentage = isset($attributes['percentage']) ? $attributes['percentage'] : '';
        $posts->caste = isset($attributes['caste']) ? $attributes['caste'] : '';
        $posts->state = isset($attributes['state']) ? $attributes['state'] : '';
        if($posts->save(false))
        {
            $post = Ascholarship::find()->where(['id' => $posts->id])->one();
            $data = array('id'=>$post['id'], 'userid'=>$post['user_id'], 'name'=>$post['name'], 
                    'father_name'=>$post['father_name'], 'mobile_number'=>$post['mobile_number'], 
                    'email'=>$post['email'], 'age'=>$post['age'], 'gender'=>$post['gender'], 
                    'education_details'=>$post['education_details'], 'percentage'=>$post['percentage'], 
                    'educational_qualification'=>$post['educational_qualification'], 'caste'=>$post['caste'],
                    'state'=>$post['state']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionDeleteapplyscholarship()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        \Yii::$app->db->createCommand()->delete('apply_scholarship', ['id' => $attributes['id']])->execute();
        return array('status'=>1, 'error'=>'');
    }
    
    public function actionShowapplyscholarshipadmin()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from apply_scholarship where status=1";
        $ascholarship = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($ascholarship))
        {
            $data = array();
            foreach($ascholarship as $key=>$value)
                $data[] = array('id'=>$value['id'], 'userid'=>$value['user_id'], 'name'=>$value['name'], 'father_name'=>$value['father_name'], 
                    'email'=>$value['email'], 'mobile_number'=>$value['mobile_number'], 'age'=>$value['age'], 
                    'gender'=>$value['gender'], 'education_details'=>$value['education_details'], 
                    'educational_qualification'=>$value['educational_qualification'], 'percentage'=>$value['percentage'], 
                    'caste'=>$value['caste'], 'state'=>$value['state']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionShowapplyscholarship()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $sql = "select * from apply_scholarship where status=1 and user_id=".$attributes['userid'];
        $ascholarship = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($ascholarship))
        {
            $data = array();
            foreach($ascholarship as $key=>$value)
                $data[] = array('id'=>$value['id'], 'userid'=>$value['user_id'], 'name'=>$value['name'], 'father_name'=>$value['father_name'], 
                    'email'=>$value['email'], 'mobile_number'=>$value['mobile_number'], 'age'=>$value['age'], 
                    'gender'=>$value['gender'], 'education_details'=>$value['education_details'], 
                    'educational_qualification'=>$value['educational_qualification'], 'percentage'=>$value['percentage'], 
                    'caste'=>$value['caste'], 'state'=>$value['state']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    public function actionShowpostsch()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        $auser = Ascholarship::find()->where(['user_id' => $attributes['userid']])->one();
        $today = date("Y-m-d");
        $sql = "select * from post_scholarship where status=1 and last_date >= '".$today."' and "
                . "minage <= '".$auser['age']."' and maxage >='".$auser['age']."' and gender='".$auser['gender']."' "
                . "and percentage <='".$auser['percentage']."' and caste='".$auser['caste']."' and "
                . "state='".$auser['state']."'";
        $pscholarship = \Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($pscholarship))
        {
            $data = array();
            foreach($pscholarship as $key=>$value)
                $data[] = array('id'=>$value['id'], 'userid'=>$value['user_id'], 'name'=>$value['name'], 'description'=>$value['description'], 
                    'contact_number'=>$value['contact_number'], 'email'=>$value['email'], 'website_link'=>$value['website_link'], 
                    'minage'=>$value['minage'], 'maxage'=>$value['maxage'], 'gender'=>$value['gender'], 
                    'education'=>$value['education'], 'percentage'=>$value['percentage'], 'caste'=>$value['caste'],
                    'state'=>$value['state'], 'last_date'=>$value['last_date'], 'application_address'=>$value['application_address'],
                    'number_scholarship'=>$value['number_scholarship']);
            return array('data'=>$data,'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //Post upload
    public function actionPostmultipleupload()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->post();
        if(isset($attributes['description']) && !empty($attributes['description']))
        {
            $description = json_encode(array('post'=>$attributes['description']));
            $object = new Object();
            $object->owner_guid = $attributes['userid'];
            $object->type = isset($attributes['type']) ? $attributes['type'] : 'user';
            $object->time_created =  time();
            $object->title = isset($attributes['title']) ? $attributes['title'] : '';
            $object->description = $description;
            $object->subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'wall';
            $object->category = isset($attributes['category']) ? $attributes['category'] : '';
            $object->subcategory = isset($attributes['subcategory']) ? $attributes['subcategory'] : '';
            $object->city = isset($attributes['city']) ? $attributes['city'] : '';
            $object->area = isset($attributes['area']) ? $attributes['area'] : '';
            $object->state = isset($attributes['state']) ? $attributes['state'] : '';
            if($object->save())
            {
                if(isset($_FILES["imagepath"]) && !empty($_FILES["imagepath"]))
                {
                    foreach($_FILES['imagepath']['name'] as $key=>$value)
                    {
                        $ftype = explode("/", $_FILES['imagepath']['type'][$key]);
                        if($ftype[0] == 'image')
                            $filetype = 'image';
                        elseif($ftype[0] == 'video')
                            $filetype = 'video';
                        elseif($ftype[0] == 'application')
                            $filetype = 'pdf';
                        if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid))
                            mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid, 0777, true);
                        if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall'))
                            mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall', 0777, true);
                        if (!file_exists('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$filetype))
                            mkdir('/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$filetype, 0777, true);
                        $extension = pathinfo($value, PATHINFO_EXTENSION);
                        $name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, '8');
                        $target_file = '/var/sentora/hostdata/zadmin/public_html/thetalentbook_co_in/tb_data/object/'.$object->guid.'/ossnwall/'.$filetype.'/'.$name.".".$extension;

                        $datafile = 'ossnwall/'.$filetype.'/'.$name.'.'.$extension;
                        $filepatht = 'https://thetalentbook.co.in/tb_data/object/'.$object->guid.'/ossnwall/'.$filetype.'/'.$name.".".$extension;
                        move_uploaded_file($_FILES['imagepath']['tmp_name'][$key], $target_file);

                        if($filetype == 'image')
                            $type = 'file:wallphoto';
                        elseif($filetype == 'video')
                            $type = 'file:wallvideo';
                        elseif($filetype == 'pdf')
                            $type = 'file:wallpdf';

                        $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $object->guid,'type' => 'object',
                                'subtype' => 'poster_guid','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                        $entityid = \Yii::$app->db->getLastInsertID();
                        $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => $attributes['userid']])->execute();

                        $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities', ['owner_guid' => $object->guid,'type' => 'object',
                                'subtype' => 'access','time_created' => time(),'time_updated' => 0, 'permission' => '2', 'active'=>'1'])->execute();
                        $entityid = \Yii::$app->db->getLastInsertID();
                        $tbentity = \Yii::$app->db->createCommand()->insert('tb_entities_metadata', ['guid' => $entityid,'value' => '3'])->execute();

                        $entity = new Entities();
                        $entity->owner_guid = $object->guid;
                        $entity->type = 'object';
                        $entity->subtype = $type;
                        $entity->time_created = time();
                        $entity->permission = 2;
                        $entity->active = 1;
                        if($entity->save())
                        {
                            $entityid = $entity->guid;
                            $entitymeta = new Entitiesmetadata();
                            $entitymeta->guid = $entityid;
                            $entitymeta->value = $datafile;
                            $entitymeta->save();
                        }
                    }
                    return array('postid'=>$object->guid,'status'=>1, 'error'=>'');
                }
                else
                  return array('postid'=>$object->guid,'status'=>1, 'error'=>'');  
            }
            else
                return array('status'=>0, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'');
    }
    
    //check all posts
    public function actionGetallpostsupload()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \yii::$app->request->get();
        //$posts = Object::find()->all();  
        $sql = "select * from tb_relationships where relation_from=".$attributes['ownerid'];
        $relation = \Yii::$app->db->createCommand($sql)->queryAll();
        $relationcheck = $useradmin = '';
        foreach($relation as $key=>$value)
            $relationcheck .= $value['relation_to'].",";
        $relationcheck = rtrim($relationcheck, ",");
        
        $sql = "select * from tb_users where type='admin'";
        $userrel = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach($userrel as $key=>$value)
            $useradmin .= $value['guid'].",";
        $useradmin = rtrim($useradmin, ",");
        
        $userinfo = $attributes['ownerid'].",".$useradmin.",".$relationcheck;
        $userinfo = rtrim($userinfo, ",");
        $sql = "select * from tb_object where owner_guid in (".$userinfo.") order by guid desc limit ".$attributes['minlimit']." , ".$attributes['maxlimit'];
        $posts = \Yii::$app->db->createCommand($sql)->queryAll();
        if(count($posts) > 0 )
        {
            $data = array();
            foreach($posts as $key=>$value)
            {
                if(!empty($value['description']))
                    $description1 = json_decode($value['description']);
                if(isset($description1->post) && (!empty($description1->post)))
                {
                    $description = $description1->post;
                    if($value['type'] == 'user')
                        $usercheck = Users::find()->where(['guid' => $value['owner_guid']])->one();
                    elseif($value['type'] == 'group')
                    {
                        $objcheck = Object::find()->where(['guid' => $value['owner_guid']])->one();
                        $usercheck = Users::find()->where(['guid' => $objcheck['owner_guid']])->one();
                    }
                    
                    $likestatus = $likecount = $sharestatus = $sharecount = $commentcount = 0;
                    $birthdate = $gender = $profilephoto  = '';
                    $sql = "select * from tb_likes where subject_id=".$value['guid']." and guid=".$attributes['ownerid'];
                    $postlike = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postlike))
                        $likestatus = 1;

                    $sql = "select count(*) as cpost from tb_likes where subject_id=".$value['guid'];
                    $postlikecount = \Yii::$app->db->createCommand($sql)->queryOne();    
                    if(!empty($postlikecount) && $postlikecount['cpost'] > 0)
                        $likecount = (int)$postlikecount['cpost'];

                    $sql = "select * from tb_shares where subject_id=".$value['guid']." and guid=".$attributes['ownerid'];
                    $postshare = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postshare))
                         $sharestatus = 1;

                    $sql = "select count(*) as cshare from tb_shares where subject_id=".$value['guid'];
                    $postsharecount = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($postsharecount) && $postsharecount['cshare'] > 0)
                        $sharecount = (int)$postsharecount['cshare'];
                    
                    $sql = "select count(*) as ccomment from tb_annotations as ta, tb_entities as te, tb_entities_metadata as tem where "
                            . "ta.id=te.owner_guid and te.guid=tem.guid and ta.subject_guid=".$value['guid']." and "
                            . "ta.type='comments:post' and te.type='annotation'";
                    $comments = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($comments) && $comments['ccomment'] > 0)
                        $commentcount = (int)$comments['ccomment'];
                    
                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='gender'";
                    $resultgender = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultgender))
                        $gender = $resultgender['value'];

                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='birthdate'";
                    $resultbirhtdate = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultbirhtdate))
                       $birthdate = $resultbirhtdate['value']; 

                    $sql = "select tem.* from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and te.owner_guid=".$value['owner_guid']." and te.type='user' and te.subtype='file:profile:photo'";
                    $resultphoto = \Yii::$app->db->createCommand($sql)->queryOne();
                    if(!empty($resultphoto))
                        $profilephoto = 'https://thetalentbook.co.in/tb_data/user/'.$value['owner_guid'].'/'.$resultphoto['value']; 
                    
                    $type = '';
                    $uploadfile = array();
                    $sql = "select tem.*, te.subtype from tb_entities te, tb_entities_metadata tem where te.guid=tem.guid and type='object' "
                            . "and (te.subtype='file:wallphoto' or te.subtype='file:wallvideo' or te.subtype='file:wallpdf') and te.owner_guid=".$value['guid'];
                    $photoupload = \Yii::$app->db->createCommand($sql)->queryAll();
                    if(!empty($photoupload))
                    {
                        foreach($photoupload as $key1=>$value1) {
                            if($value1['subtype'] == 'file:wallphoto')
                            {
                                $type = 'image';
                                $uploadfile[] = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$value1['value'];
                            }
                            elseif($value1['subtype'] == 'file:wallvideo')
                            {
                                $type = 'video';
                                $uploadfile[] = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$value1['value'];
                            }
                            elseif($value1['subtype'] == 'file:wallpdf')
                            {
                                $type = 'pdf';
                                $uploadfile[] = 'https://thetalentbook.co.in/tb_data/object/'.$value['guid'].'/'.$value1['value'];
                            }
                        }
                    }
                    
                    $data[] = array('post_id'=>$value['guid'],'userid'=>$value['owner_guid'], 'type'=>$value['type'], 
                        'title'=>$value['title'], 'description'=>$description, 'subtype'=>$value['subtype'], 'category'=>$value['category'],
                        'subcategory'=>$value['subcategory'], 'city'=>$value['city'], 'state'=>$value['state'], 'area'=>$value['area'], 'likestatus'=>$likestatus,
                        'likecount'=>$likecount, 'sharestatus'=>$sharestatus, 'sharecount'=>$sharecount, 'timecreated' =>$value['time_created'],
                        'commentcount'=>$commentcount, 'uploadfile'=>$uploadfile, 'filetype'=>$type, 'owner'=>array('userid'=>$usercheck['guid'],
                        'type'=>$usercheck['type'], 'username'=>$usercheck['username'], 'email'=>$usercheck['email'], 
                        'first_name'=>$usercheck['first_name'], 'last_name'=>$usercheck['last_name'], 
                        'mobile'=>$usercheck['mobile'], 'college'=>$usercheck['college'], 'location'=>$usercheck['location'], 
                        'description'=>$usercheck['description'], 'work'=>$usercheck['work'], 'professionalskill'=>$usercheck['professionalskill'], 
                        'school'=>$usercheck['school'], 'othermobile'=>$usercheck['othermobile'], 'aboutyou'=>$usercheck['aboutyou'],
                        'nickname'=>$usercheck['nickname'], 'favquotes'=>$usercheck['favquotes'], 'birthdate'=>$birthdate, 'gender'=>$gender, 
                        'profilephoto'=>$profilephoto));
                }
            }
            return array('result'=>$data, 'status'=>1, 'error'=>'');
        }
        else
            return array('status'=>0, 'error'=>'no posts');
    }
}
