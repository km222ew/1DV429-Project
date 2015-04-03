<?php

require_once('src/app/View/ProfileView.php');
require_once('src/app/Model/ProfileModel.php');

class ProfileController
{
    private $profileView;
    private $profileModel;

    public function __construct(Notify $notify, UserRepository $userRep)
    {
        $this->profileView = new ProfileView();
        $this->profileModel = new ProfileModel($notify, $userRep);
    }

    private function getUser($username)
    {
        return $this->profileModel->getUserData($username);
    }

    public function showProfile($username)
    {
        $user = $this->getUser($username);

        if ($this->profileView->DidUserRequestPasswordChange())
        {
            if ($this->profileModel->ChangeUserPassword($username, $this->profileView->GetCurrentPassword(), $this->profileView->GetPassword(), $this->profileView->GetRepPassword()))
            {
                $this->profileView->redirect("?action=" . NavigationView::$actionLogout);
            }
            else
                $this->profileView->redirect("?action=" . NavigationView::$actionShowProfile);
        }

        return $this->profileView->renderProfile($user->getUsername());
    }
}