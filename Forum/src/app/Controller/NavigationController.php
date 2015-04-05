<?php

require_once('LoginController.php');
require_once('ProfileController.php');
require_once('AdminController.php');
require_once('ForumController.php');
require_once('src/app/Model/LoginModel.php');
require_once('src/app/View/LoginView.php');
require_once('src/app/View/LoginView.php');
require_once('src/app/View/NavigationView.php');


class NavigationController
{
    private $loginModel;

    public function doControl(Notify $notify, UserRepository $userRep)
    {
        $this->loginModel = new LoginModel($notify, $userRep);
        $loginView = new LoginView($this->loginModel);

        $loginController = new LoginController($this->loginModel, $loginView, $notify, $userRep);

        $controller = null;

        try
        {
            if($this->loginModel->IsLoggedIn($loginView->getUserAgent(), $loginView->getUserIp()))
            {
                $username = $this->loginModel->getUsername();
                
                switch(NavigationView::getAction())
                {
                    case NavigationView::$actionShowProfile:
                        $controller = new ProfileController($notify, $userRep);
                        return $controller->showProfile($username);
                    case NavigationView::$actionLogout:
                        $loginController->doLogout();
                        return $loginController->doLogin();
                    case NavigationView::$actionRegister:
                        //If you are logged in and for some reason move to the registration page, you will be logged out
                        if($this->loginModel->IsLoggedIn($loginView->getUserAgent(), $loginView->getUserIp()))
                        {
                            $loginController->doLogout();
                        }
                        return $loginController->doLogin();
                        break;
                    case NavigationView::$actionForum:
                    case NavigationView::$actionTopic:
                    case NavigationView::$actionCreateThread:
                        $controller = new ForumController($notify, $userRep);

                        return $controller->ShowForum($this->IsModOrHigher(), $username);
                        break;
                    case NavigationView::$actionShowAllUsers:
                        $controller = new AdminController($notify, $userRep);

                        if ($this->IsAdmin())
                        {                            
                            return $controller->ShowAllUsers($username);
                        }
                    default:
                        $controller = new ForumController($notify, $userRep);
                        return $controller->showForum($this->IsModOrHigher(), $username);
                }
            }
            else
            {
                return $loginController->doLogin();
            }
        }
        catch(Exception $e)
        {
            session_destroy();
            return $loginController->doLogin();
            // echo $e->getMessage();
        }

        return $loginController->doLogin();
    }

    public function GetUsername()
    {
        return $this->loginModel->getUsername();
    }

    public function IsAdmin()
    {
        return $this->loginModel->IsUserAdmin();
    }

    public function IsModOrHigher()
    {
        return $this->loginModel->IsModOrHigher();
    }
}