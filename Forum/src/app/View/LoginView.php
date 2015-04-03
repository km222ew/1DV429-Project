<?php

class LoginView {

    //Strings
    private $username;
    private $inputUsername;
    private $password;
    private $tokenPass;
    private $userAgent;
    private $userIp;
    private $login;
    private $action;
    private $register;

    public function __construct()
    {
        $this->username = 'username';
        $this->inputUsername = 'inputUsername';
        $this->password = 'password';
        $this->tokenPass = 'tokenPass';
        $this->userAgent = 'HTTP_USER_AGENT';
        $this->userIp = 'REMOTE_ADDR';
        $this->login = 'login';
        $this->action = 'action';
        $this->register = 'register';
    }

	//Get username from post
	public function getUsername()
    {
		if (isset($_POST[$this->username]))
        {
			return trim($_POST[$this->username]);
		}
		
		return '';
	}

	//Get password from post
	public function getPassword()
    {
		if (isset($_POST[$this->password]) && $_POST[$this->password] != '')
        {
			return trim($_POST[$this->password]);
		}

		return '';
	}

	//Get client browser info
	public function getUserAgent()
    {
		return $_SERVER[$this->userAgent];
	}

    //Get client ip
    public function getUserIp()
    {
        return $_SERVER[$this->userIp];
    }

	//Did user request login?
	public function didLogin()
    {
		if (isset($_POST[$this->login]))
        {
			return true;
		}
        else
        {
			return false;
		}
	}

    //Did user go to register page
    public function goToRegister()
    {
        if(isset($_GET[$this->action]) && $_GET[$this->action] == $this->register)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

	//Redirect, to get rid of post
	public function redirect($pageId)
    {
        header("Location:$pageId");
	}

	//Page: login, page for logging in
	public function login()
    {

		$body = "
				<form action='?action=".NavigationView::$actionShowProfile."' class='form-signin' method='post'>
					    <h2 class='form-signin-heading'>Please sign in</h2>
						<input type='text' class='form-control input-lg marginb' placeholder='Username' id=$this->username name=$this->username value='' required autofocus>
						<input type='password' class='form-control input-lg marginb' placeholder='Password' id=$this->password name=$this->password required>
                        <div class='row'>
                            <div class='col-lg-6'>
                                <a href='?action=".NavigationView::$actionRegister."' class='btn btn-primary btn-block'>Register</a>
                            </div>
                            <div class='col-lg-6'>
                                <button type='submit' name=$this->login class='btn btn-primary btn-block'>Sign in</button>
                            </div>
                        </div>
				</form>";
		return $body;
	}
}