<?php
require_once '../models/User.php';

class AuthController
{
    public static function login($emailOrUsername, $password, $totpCode)
    {
        $user = User::findByEmailOrUsername($emailOrUsername);
        if ($user && password_verify($password, $user['password'])) {
            $ga = new PHPGangsta_GoogleAuthenticator();
            if ($ga->verifyCode($user['secret'], $totpCode, 2)) {
                return $user; // Login erfolgreich
            }
        }
        return null; // Login fehlgeschlagen
    }
}
?>
