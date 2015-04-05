<?php

require_once("DAL/UserRepository.php");
require_once("User.php");

//Rather large class. Game logic
class AdminModel
{
    private $notify;
    private $userRep;

    public function __construct(Notify $notify, UserRepository $userRep)
    {
        $this->notify = $notify;
        $this->userRep = $userRep;
    }

    //Same as in ProfileModel. Maybe should for storing in session if time.
    public function getUserData($username)
    {
        $user = $this->userRep->getUserByName($username);

        return $user;
    }

    public function GetAdminRole($username)
    {
        $user = $this->getUserData($username);

        if ($user == null) 
        {
            return false;
        }

        if ($user->getRole() == ROLE::$ADMIN) 
        {
            return false;
        }
        return true;
    }

    public function GetAllUsers()
    {
        return $this->userRep->GetAllUsers();
    }

    public function PromoteUser($username)
    {
        if ($this->userRep->UpdateUserRole($username, ROLE::$MODERATOR))
        {
            $this->notify->info("User <b>$username</b> was promoted to a moderator.");
            return true;
        }
        
        $this->notify->error("Unable to promote the specified user.");
    }

    public function DemoteUser($username)
    {
        if ($this->userRep->UpdateUserRole($username, ROLE::$USER))
        {
            $this->notify->info("Moderator <b>$username</b> was demoted to a user.");
            return true;
        }

        $this->notify->error("Unable to demote the specified user.");
    }
}