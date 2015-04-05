<?php

require_once("DAL/UserRepository.php");
require_once("User.php");

class RegisterModel
{
    private $notify;
    private $userRep;

    public function __construct(Notify $notify, UserRepository $userRep)
    {
        //Notifications notify->success/error/info(message, optional header)
        $this->notify = $notify;
        $this->userRep = $userRep;
    }

    //Make sure provided credentials are valid for registration
    public function validateRegister($username, $password, $repPassword)
    {
        $validChars = '/[^a-zåäöA-ZÅÄÖ0-9]/';
        $clearForRegistration = true;

        if($password != $repPassword)
        {
            $clearForRegistration = false;
            $this->notify->error('Passwords does not match');
        }

        if(strlen($username) < 3 || strlen($username) > 25)
        {
            $clearForRegistration = false;
            $this->notify->error('Username needs at least 3 characters and 25 at max.');
        }

        if(strlen($password) < 6 || strlen($password) > 60)
        {
            $clearForRegistration = false;
            $this->notify->error('Password needs at least 6 characters and 60 at max');
        }

        //Check if username contains invalid characters
        if(preg_match($validChars, $username))
        {
            $clearForRegistration = false;
            $this->notify->error('Username contains illegal characters');
        }

        //Check if username exist in database
        if(strlen($username) > 0 && $this->userRep->getUserByName($username) != null)
        {
            $clearForRegistration = false;
            $this->notify->error('Username is already taken');
        }

        //Input user into database if everything is ok
        if($clearForRegistration)
        {

            $this->registerUser(new User($username, password_hash($password, PASSWORD_BCRYPT), null, null, null, null, null, null, null, null));

            $this->notify->success('You have successfully been registered');
            return true;
        }

        return false;
    }

    private function registerUser(User $user)
    {
        $this->userRep->addUser($user);
    }
}