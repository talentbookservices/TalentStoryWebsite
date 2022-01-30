<?php

$servername = 'localhost';

mysqli_connect = mysqli_query('localhost','root','',)

$username = $_POST['username'];
$password = $_POST['password'];
$query = mysqli_query($conn,"SELECT *  from user where username and password is matched");
$result_can = mysqli_query($conn,query);
while($row = mysqli_insert)
$username =  $check['username'];
$password = $check['password'];
}
while(!conn)
$check_username = check[$username]
$check_password = check[$password]
$if(!conn)
{
    $sql = "INSERT INTO tb (username, password)VALUES ('$username','$password');

}
if(conn_query = TRUE)
{
    

   

 
// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);
 
// connect to the mysql database
$link = mysqli_connect('localhost', 'user', 'pass', 'dbname');
mysqli_set_charset($link,'utf8');
 
// retrieve the table and key from the path
$table = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
$key = array_shift($request)+0;
 
// escape the columns and values from the input object
$columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));
$values = array_map(function ($value) use ($link) {
  if ($value===null) return null;
  return mysqli_real_escape_string($link,(string)$value);
},array_values($input));
 
// build the SET part of the SQL command
$set = '';
for ($i=0;$i<count($columns);$i++) {
  $set.=($i>0?',':'').'`'.$columns[$i].'`=';
  $set.=($values[$i]===null?'NULL':'"'.$values[$i].'"');
}
 
// create SQL based on HTTP method
switch ($method) {
  case 'GET':
    $sql = "select * from `$table`".($key?" WHERE id=$key":''); break;
  case 'PUT':
    $sql = "update `$table` set $set where id=$key"; break;
  case 'POST':
    $sql = "insert into `$table` set $set"; break;
  case 'DELETE':
    $sql = "delete `$table` where id=$key"; break;
}
 
// excecute SQL statement
$result = mysqli_query($link,$sql);
 
// die if SQL statement failed
if (!$result) {
  http_response_code(404);
  die(mysqli_error());
}
 
// print results, insert id or affected row count

if($method == 'GET')
{
    if(!$key) echo '[';
        for ($i=0;$i<mysqli_num_rows($result);$i++)
        {
            echo ($i>0',':'').json_encode(mysqli_fetch_objects($result));
        }

    if(!$key) echo ']'
}
elseif($method == 'POST')
{
    echo mysqli_insert_id($link);

}
else
{
    echo mysqli_affected_rows($link)
}

//getting the data
function Streampublish(name,href title,href link)
{
    showLoader(True);
    method: stream.publish
    attachemnt(
    tb.info{
        'name' : $name;
        'href_title' : $title;
        'href_link' : $link;

    }
    action_links{
        method = $POST;
    }
    )
}

$attachment = array(
    'message' => $message,
    'name' => $title,
    'link' => $link,
    'description' => $description,
    'picture'=>$picture,
);

try{
   
    $fb->post('/me/feed', $attachment, $accessToken);

   
    echo 'The post was published successfully to the Facebook timeline.';
}
    exit;
}catch(Talentbookoption $e){
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
}else{
// Get Facebook login URL
$talentbook = $helper->getLoginUrl($redirectURL, $fbPermissions);

// Redirect to Facebook login page
echo '<a href="'.$talentbook.'"><img src="#" /></a>';
}
session_start()
if(isset ($session[username] || $session[password])
{
    echo ('session')
    $rows = preg_replace(array_keys($input));
    $values = array_map(function ($data)) use ($post) {
    if($value===null) return null;
    return mysqli_fetch_objects($link,(string)$value)
    else {
        return mysqli_norows_affected($data)
    }

}
else {
    echo ('invalid credentials')
}

}
session_close()
?>






