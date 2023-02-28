<?php

namespace App\Session\Admin;

use \WilliamCosta\DatabaseManager\Database;

class Login
{

    /**
     * Method to initiate session
     */
    private static function init()
    {

        // VERIFY SESSION
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Method to create user login
     * @param User $obUser
     * @return boolean
     */
    public static function login($obUser)
    {

        // INIT SESSION
        self::init();

        // DEFINE USER SESSION
        $_SESSION['user'] = [
            'id' => $obUser->id,
            'email' => $obUser->email,
            'name' => $obUser->name,
        ];

        // SUCCESS
        return true;
    }

    /**
     * Method to verify if the user is logged
     * @return boolean
     */
    public static function isLogged()
    {

        // INIT SESSION
        self::init();

        // RETURN VERIFICATON
        return isset($_SESSION['user']['id']);
    }

    /**
     * Method to logout user
     * @return boolean
     */
    public static function logout()
    {

        // INIT SESSION
        self::init();

        // LOGOUT USER
        unset($_SESSION['user']);

        // SUCCESS
        return true;
    }
}
