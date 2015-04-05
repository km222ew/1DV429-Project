<?php
require_once("src/app/Model/DAL/ROLE.php");

class AdminView
{
    private $notify;

    private static $demote = "toUser";
    private static $promote = "toMod";
    private static $username = "username";

    public function __construct(Notify $notify)
    {
        $this->notify = $notify;
    }

    public function AdminDidDemote()
    {
        if (isset($_POST[self::$demote]))
            return true;
        return false;
    }

    public function AdminDidPromote()
    {
        if (isset($_POST[self::$promote]))
            return true;
        return false;
    }

    public function GetUsername()
    {
        if (isset($_POST[self::$username]))
            return $_POST[self::$username];
        return null;
    }

    public function GetAllUsersHTML($users)
    {
        $HTML = "
                <div class='row'>
                    <div class=col-lg-5>
                    <h2>Username</h2>
                    </div>
                    <div class=col-lg-5>
                    <h2>Role</h2>
                    </div>
                    <div class=col-lg-2>
                    <h2>Actions</h2>
                    </div>
                </div>
                <hr/>
                ";
        if ($users != null) 
            foreach($users as $user)
            {
                $username = $user->getUsername();
                $role = $user->getRole();

                $HTML .= 
                "<div class='row height'>
                    <form role='form' action='' method='post'>
                        <div class='col-lg-5'>
                            <input type='hidden' name='".self::$username."' value='$username'>
                           <h4>$username</h4>
                        </div>
                        <div class='col-lg-5'>
                            <input type='hidden' name='role' value='$role'>
                            <h4>".ROLE::IdToName($role)."</h4>
                        </div>";
                        
                            if ($role == ROLE::$MODERATOR)
                            {
                                $HTML .= 
                                        "<div class='col-lg-2'><button type='submit' name='".self::$demote."' class='btn btn-primary btn-block' value='Demote'>Demote</button>
                                        </div>";
                            }
                            else if ($role == ROLE::$USER)
                            {
                                $HTML .= 
                                        "<div class='col-lg-2'><button type='submit' name='".self::$promote."' class='btn btn-primary btn-block' value='Promote'>Promote</button>
                                        </div>";
                            }
                            $HTML .=
                            "   
                    </form>
                </div>";
            }

        return $HTML;
    }
}