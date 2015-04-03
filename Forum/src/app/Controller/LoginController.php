<?php

require_once('src/app/Model/RegisterModel.php');
require_once('src/app/View/RegisterView.php');


class LoginController {

	private $model;
    private $registerModel;
	private $view;
    private $registerView;

    private $index;

	public function __construct(LoginModel $loginModel, LoginView $loginView, Notify $notify, UserRepository $userRep)
    {
		$this->model = $loginModel;
        $this->registerModel = new RegisterModel($notify, $userRep);
		$this->view = $loginView;
        $this->registerView = new RegisterView();

        $this->index = 'index.php';
	}

	//Try to login with provided credentials
	private function validateLogin()
    {
		$username = $this->view->getUsername();
		$password = $this->view->getPassword();
        $userAgent = $this->view->getUserAgent();
        $userIp = $this->view->getUserIp();

		//Check if credentials are correct
		if ($this->model->validateCredentials($username, $password, $userAgent, $userIp))
        {
			return true;
		}
        else
        {
			return false;
		}
	}

    public function doLogout()
    {
        //Log user out
        $this->model->logOut();
    }

	public function doLogin()
    {
        //Did user want to login?
        if ($this->view->didLogin() && $this->model->CheckBruteforce($this->view->getUsername()))
        {
            //Get rid of post request
            $this->view->redirect($this->index);

            //Validate credentials (post)
            if ($this->validateLogin())
            {
                $this->view->redirect("?action=".NavigationView::$actionShowProfile);
            }
        }
        else if ($this->view->didLogin())
            $this->view->redirect($this->index);

        //Did user want to register
        if($this->registerView->didRegister())
        {
            $username = $this->registerView->getUsername();
            $password = $this->registerView->getPassword();
            $repPassword = $this->registerView->getRepeatedPassword();

            //Check if the inputs are valid
            if($this->registerModel->validateRegister($username, $password, $repPassword))
            {
                //Get rid of post request
                $this->view->redirect($this->index);

                return $this->view->login();
            }

            //Get rid of post request
            $this->view->redirect("?action=register");
            return $this->registerView->register();
        }

        //Did user go to register page
        if($this->view->goToRegister())
        {
            return $this->registerView->register();
        }

		return $this->view->login();
	}
}