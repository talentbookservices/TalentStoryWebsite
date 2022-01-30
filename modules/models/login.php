<?php
if (ossn_isLoggedin()) {
    redirect('home');
}
$username = input('username');
$password = input('password');

if (empty($username) || empty($password)) {
    ossn_trigger_message(ossn_print('login:error'), 'error');
    redirect();
} else {
    $user = ossn_user_by_username($username);
}
//check if username is email
if (strpos($username, '@') !== false) {
    $user = ossn_user_by_email($username);
    $username = $user->username;
}

/*start coding  sun  */
//check if username is mobile
if (is_numeric($username) !== false) {
    $user = ossn_user_by_mobile($username);
    $username = $user->username;
    $salt = $user->salt;
    $password = $user->generate_password($password . $salt);
    if ($password == $user->password) {
        unset($user->password);
        unset($user->salt);

        OssnSession::assign('OSSN_USER', $user);
        $user->update_last_login();

        $vars = array();
        $vars['user'] = $user;
        $login = ossn_call_hook('user', 'login', $vars, true);
        ossn_trigger_callback('login', 'success', $vars);
        redirect('home');
    } else {
        ossn_trigger_message(ossn_print('login:error'), 'error');
        redirect();
    }
}
/* end coding sun  */

if ($user && $user->isUserVALIDATED() == FALSE) {
    try {
        #$user->resendValidationEmail();
    } catch (Exception $e) {
    }
    ossn_trigger_message(ossn_print('ossn:user:validation:resend'), 'error');
    redirect(REF);
}
$vars = array(
    'user' => $user
);
ossn_trigger_callback('user', 'before:login', $vars);

$login = new OssnUser;
$login->username = $username;
$login->password = $password;
$_flg = $login->Login();
if ($_flg) {
    //One uneeded redirection when login #516
    ossn_trigger_callback('login', 'success', $vars);

    redirect('home');
} else {
    ossn_trigger_message(ossn_print('login:error'), 'error');
    redirect();
}