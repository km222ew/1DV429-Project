<?php

class RegisterView
{
    //Strings
    private $regUsername;
    private $regPassword;
    private $repRegPassword;
    private $register;
    private $inputUsername;

    public function __construct()
    {

        $this->regUsername = 'regUsername';
        $this->regPassword = 'regPassword';
        $this->repRegPassword = 'repRegPassword';
        $this->register = 'register';
        $this->inputUsername = 'inputUsername';
    }

    public function getUsername()
    {
        if(isset($_POST[$this->regUsername]))
        {
            return trim($_POST[$this->regUsername]);
        }

        return '';
    }

    public function getPassword()
    {
        if(isset($_POST[$this->regPassword]))
        {
            return trim($_POST[$this->regPassword]);
        }

        return '';
    }

    public function getRepeatedPassword()
    {
        if(isset($_POST[$this->repRegPassword]))
        {
            return trim($_POST[$this->repRegPassword]);
        }

        return '';
    }

    public function didRegister()
    {
        if(isset($_POST[$this->register]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function register() {

        //Replaces all invalid characters with '' and puts the remainder in input
        // $printUsername = preg_replace('/[^a-zåäöA-ZÅÄÖ0-9]/', '', $printUsername);

        $body = "
				<form action='?action=$this->register' class='form-signin' method='post'>
						<h2 class='form-signin-heading'>Please fill in Username and Password</h2>
						<input type='text' class='form-control input-lg marginb' placeholder='Username' id=$this->regUsername name=$this->regUsername value='' required autofocus>
						<input type='password' class='form-control input-lg marginb' placeholder='Password' id=$this->regPassword name=$this->regPassword autofocus>
						<input type='password' class='form-control input-lg marginb' placeholder='Repeat Password' id=$this->repRegPassword name=$this->repRegPassword autofocus>
						<div class='row'>
                            <div class='col-lg-6'>
                                <a href='index.php' class='btn btn-primary btn-block'>Back</a>
                            </div>
                            <div class='col-lg-6'>
                                <button type='submit' name=$this->register class='btn btn-primary btn-block'>Register</button>
                            </div>
                        </div>
				</form>";

        return $body;
    }
}