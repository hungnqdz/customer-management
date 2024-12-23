<?php

class Session
{


    public static function init()
    {

        if (version_compare(phpversion(), '5.4.0', '<')) {
            if (session_id() == '') {
                session_start();
            }
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }
    }

    public static function set($key, $val)
    {
        $_SESSION[$key] = $val;
    }

    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return false;
        }
    }

    public static function destroy()
    {
        session_destroy();
        session_unset();
        echo "<script>window.location='landing.php';</script>";
    }


    public static function CheckSession()
    {
        if (self::get('login') == FALSE) {
            session_destroy();
            echo "<script>window.location='login.php';</script>";
        }

    }

    public static function CheckAuthority($role_id)
    {
        if (self::get('login') == TRUE) {
            if (Session::get('role_id') != $role_id) echo "<script>window.location='landing.php';</script>";
        }

    }


    public static function CheckLogin()
    {
        if (self::get("login") == TRUE) {
            if (Session::get('role_id') != 1) echo "<script>window.location='index.php';</script>";
            else echo "<script>window.location='landing.php';</script>";
        }
    }
}
