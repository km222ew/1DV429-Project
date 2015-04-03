<?php

class ProfileView
{
    //Strings
    private $currentPassword;
    private $editPassword;
    private $repEditPassword;
    private $edit;

    public function __construct()
    {
        $this->currentPassword = "currentPw";
        $this->editPassword = "editPw";
        $this->repEditPassword = "repEditPw";
        $this->edit = "edit";
    }

    public function DidUserRequestPasswordChange()
    {
        if (isset($_POST[$this->edit]))
            return true;

        return false;
    }

    public function GetCurrentPassword()
    {
        if (isset($_POST[$this->currentPassword]))
            return $_POST[$this->currentPassword];

        return null;
    }

    public function GetPassword()
    {
        if (isset($_POST[$this->editPassword]))
            return $_POST[$this->editPassword];

        return null;
    }

    public function GetRepPassword()
    {
        if (isset($_POST[$this->repEditPassword]))
            return $_POST[$this->repEditPassword];

        return null;
    }

    //Redirect, to get rid of post
    public function redirect($pageId)
    {
        header("Location:$pageId");
    }

    public function renderProfile($username)
    {
        $body = "<div class='row'><div class='col-lg-10'><h2>This is where you change your password.</h2></div></div>
                <hr/>
                <form action='?action=$this->edit' class='form-signin' method='post'>
                        <h2 class='form-signin-heading'>Please enter a new password.</h2>
                        <input type='password' class='form-control input-lg marginb' placeholder='Current Password' id=$this->currentPassword name=$this->currentPassword required autofocus>
                        <input type='password' class='form-control input-lg marginb' placeholder='Password' id=$this->editPassword name=$this->editPassword required>
                        <input type='password' class='form-control input-lg marginb' placeholder='Repeat Password' id=$this->repEditPassword name=$this->repEditPassword required>
                        <div class='row'>
                            <div class='text-center marginb'>
                                <button type='submit' name=$this->edit class='btn btn-primary btn-block'>Change password</button>
                            </div>
                        </div>
                </form>
                ";

        return $body;
    }
}