<?php

require_once("DAL/UserRepository.php");
require_once("User.php");

class ProfileModel
{
    private $notify;
    private $userRep;

    public function __construct(Notify $notify, UserRepository $userRep)
    {
        $this->notify = $notify;
        $this->userRep = $userRep;
    }

    public function getUserData($username)
    {
        $user = $this->userRep->getUserByName($username);

        return $user;
    }

    public function ChangeUserPassword($username, $oldPassword, $password, $repPassword)
    {
        $clearForChange = true;

        $dbPassword = $this->userRep->GetUserCurrentPassword($username);

        if (!password_verify($oldPassword, $dbPassword))
        {
            $this->notify->error('Incorrect password');

            $clearForChange = false;
        }

        if($password != $repPassword)
        {
            $this->notify->error('Passwords does not match');

            $clearForChange = false;
        }

        if(strlen($password) < 6)
        {
            $this->notify->error('Password needs at least 6 characters');

            $clearForChange = false;
        }

        if ($password == $oldPassword && password_verify($oldPassword, $dbPassword))
        {
            $this->notify->error('You need to choose a new password.');

            $clearForChange = false;
        }


        if ($clearForChange && $this->userRep->UpdateUserPassword($username, password_hash($password, PASSWORD_BCRYPT)))
        {
            $this->notify->success('Your password was changed, please login with your new password.');
            $this->userRep->PasswordChangeLog($username, $_SESSION['userIp']);
        }
        
        
        return $clearForChange;
    }
}