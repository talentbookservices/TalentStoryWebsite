<?php 
include(config.php)
session_start()

 
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form 
    
    $myusername = mysqli_real_escape_string($db,$_POST['username']);
    $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
    
    $sql = "SELECT id FROM admin WHERE username = '$myusername' and passcode = '$mypassword'";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $active = $row['active'];
    
    $count = mysqli_num_rows($result);
    
    		
    if($count == 1) {
        session_register("myusername");
        $_SESSION['login_user'] = $myusername;
        
        header("location: Usercontroller.php");
     }else {
        $error = "Your Login Name or Password is invalid";
     }
  }

else {
    
    $myusername= mysqli_fetch_array($db,$_POST[([$username]&&[$password])
    $sql = select * from user where id = myusername;
    $sql_query= mysqli_fetch_array($sql);
    print($sql1)

    if(SESSION){
        $SESSSION['session_details'] = $username;
    else {
        $error = "the data is not found"
    }
}


if(!isset session([$username]&&[$password]))
{
    if(!data=notfound)
}