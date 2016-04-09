<?php 
namespace auth;

function login_session($userid, $usertype)
{
    session_start();
    $_SESSION['userid'] = $userid;
    $_SESSION['usertype'] = $usertype;
}
function check_user($usertype)
{
    session_start();
    if ($usertype == $_SESSION['usertype']) {
        return true;
    }
    return false;
}
function is_auth() {
    session_start();
    if(empty($_SESSION['userid'])) {
        return false;
    }
    return true;
}
function logout_session() {
    session_start();
    session_unset();
    session_destroy();
}