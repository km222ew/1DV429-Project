<?php

session_set_cookie_params(0);
session_start();

//Pretty much everything in the components folder was not made by me (I changed some things) but the person who
//I got the code from which I built a registration form upon.
// https://github.com/kristofferlind/1dv408-laborationer/tree/master/l2-login

require_once('src/components/response/response.php');
require_once('src/components/notify/notify.service.php');
require_once('src/components/notify/notify.view.php');
require_once('src/components/notify/notification.php');
require_once('src/app/Controller/NavigationController.php');

$response = new Response();
$notify = new Notify();
$notifyView = new NotifyView($notify);

$navigationController = new NavigationController();

//Injecting a UserRepository
//Have to do this because the webhost complained about too many connections used and I had limited time.
$userRep = new UserRepository();

$response->HTMLPage($navigationController->doControl($notify, $userRep), $notifyView, $navigationController->GetUsername(), $navigationController->IsAdmin());
