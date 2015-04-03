<?php

require_once("DAL/UserRepository.php");
require_once("DAL/ROLE.php");
require_once("User.php");

class LoginModel {

    private $notify;
    private $userRep;

    //Strings
    private $usernameStr;
    private $userAgentStr;
    private $userIpStr;

	public function __construct(Notify $notify, UserRepository $userRep)
    {
		$this->notify = $notify;
        $this->userRep = $userRep;

        //Strings
        $this->usernameStr = 'username';
        $this->userAgentStr = 'userAgent';
        $this->userIpStr = 'userIp';
	}

	public function getUsername()
    {
		if (isset($_SESSION[$this->usernameStr]))
        {
			return $_SESSION[$this->usernameStr];
		}
        else
        {
			return '';
		}
	}

    //Look for user in database
    public function getUserFromDb($username)
    {
        $user = $this->userRep->getUserByName($username);

        return $user;
    }

	public function logout()
    {
		session_destroy();
		session_start();
		$this->notify->info('You have signed out');
	}

	//Is user already logged in?
	public function IsLoggedIn($userAgent, $userIp)
    {
		if (!isset($_SESSION[$this->usernameStr]))
        {
			return false;
		}

		$username = $_SESSION[$this->usernameStr];

        $user = $this->getUserFromDb($username);

        if($user != null && $_SESSION[$this->userAgentStr] == $userAgent && $_SESSION[$this->userIpStr] == $userIp)
        {
            return true;
        }
        else
        {
            return false;
        }
	}

    private function setSession($username, $userIp, $userAgent)
    {
        $_SESSION[$this->usernameStr] = $username;
        $_SESSION[$this->userIpStr] = $userIp;
        $_SESSION[$this->userAgentStr] = $userAgent;
    }

	//Validate credentials, used on login by post
	public function validateCredentials($username, $password, $userAgent, $userIp) 
    {
        $validChars = '/[^a-zåäöA-ZÅÄÖ0-9]/';
        $loginOutcome = true;

    	if ($username == '')
        {
    		$this->notify->error('Username is missing');
    		$loginOutcome = false;
    	}

    	if ($password == '')
        {
    		$this->notify->error('Password is missing');
    		$loginOutcome = false;
    	}

        $user = $this->getUserFromDb($username);

        if($user == null || !password_verify($password, $user->getPassword()))
        {
            $this->notify->error('Wrong username and/or password');
            $loginOutcome = false;
        }

        //Check if username contains invalid characters
        if(preg_match($validChars, $username))
        {
            //$clearForRegistration = false;
            $this->notify->error('Username contains illegal characters.');
            $loginOutcome = false;
        }

        if ($loginOutcome)
        {
            $this->notify->success('You have successfully signed in');

            $this->setSession($username, $userIp, $userAgent);

            $this->userRep->LogAttemptAtLogin($username, 1);

        	return $loginOutcome;
        }

        $this->userRep->LogAttemptAtLogin($username, 0);

        return $loginOutcome;
	}

    public function CheckBruteforce($username)
    {
        //Five or more failed attempts
        if ($this->userRep->CheckUserLoginAttempts($username))
        {
            $this->notify->error('Your account has been blocked for up to 15 minutes!');
            return false;
        }

        return true;
    }

    public function IsUserAdmin()
    {
        $user = $this->getUserFromDb($this->getUsername());

        if ($user == null)
            return false;

        return $user->getRole() == ROLE::$ADMIN;
    }
}