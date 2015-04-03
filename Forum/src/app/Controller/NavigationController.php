<?php

require_once('LoginController.php');
require_once('ProfileController.php');
require_once('AdminController.php');
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

                //If on another page than play (where you play), remove any trivia from session to combat any cheating (like going back to buy new lifelines)
                // if(NavigationView::getAction() != NavigationView::$actionPlay)
                // {
                //     $controller = new GameController($notify, $userRep);

                //     $controller->removeTrivia();
                // }

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
                    case NavigationView::$actionShowAllUsers:
                        $controller = new AdminController($notify, $userRep);

                        if ($this->IsAdmin())
                        {
                            
                            return $controller->ShowAllUsers();
                        }

                    // case NavigationView::$actionPlay:
                    //     $controller = new GameController($notify, $userRep);

                    //     if($controller->isTriviaNull())
                    //     {
                    //         $controller = new ProfileController($notify, $userRep);
                    //         return $controller->showProfile($username);
                    //     }
                    //     else
                    //     {
                    //         return $controller->showGameField($username);
                    //     }
                    default:
                        $controller = new ProfileController($notify, $userRep);
                        return $controller->showProfile($username);
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

    public function GetLoginStatus()
    {
        return $this->loginModel->getUsername() != '';
    }

    public function IsAdmin()
    {
        return $this->loginModel->IsUserAdmin();
    }
}