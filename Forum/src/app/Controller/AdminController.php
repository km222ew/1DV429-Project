<?php

require_once('src/app/View/AdminView.php');
require_once('src/app/Model/AdminModel.php');

class AdminController
{
    private $adminView;
    private $adminModel;

    public function __construct(Notify $notify, UserRepository $userRep)
    {
        $this->adminView = new AdminView($notify);
        $this->adminModel = new AdminModel($notify, $userRep);
    }

    public function ShowAllUsers()
    {

        if ($this->adminView->AdminDidPromote())
        {
            $this->adminModel->PromoteUser($this->adminView->GetUsername());
            NavigationView::redirectShowAllUsers();
        }

        if ($this->adminView->AdminDidDemote())
        {
            $this->adminModel->DemoteUser($this->adminView->GetUsername());
            NavigationView::redirectShowAllUsers();
        }

        $users = $this->adminModel->GetAllUsers();



        return $this->adminView->GetAllUsersHTML($users);
    }

}