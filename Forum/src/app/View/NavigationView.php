<?php

class NavigationView
{
    private static $action = "action";

    public static $actionShowProfile = "profile";
    public static $actionLogout = "logout";
    public static $actionRegister = "register";
    public static $actionShowAllUsers = "show_all_users";
    public static $actionTopic = "topic";
    public static $actionForum = "forum";
    public static $actionCreateThread = "create_thread";

    public static function getAction()
    {
        if(isset($_GET[self::$action]))
        {
            return $_GET[self::$action];
        }

        return self::$actionShowProfile;
    }

    public static function redirectShowAllUsers()
    {
        header('Location: ?'.NavigationView::$action.'='.NavigationView::$actionShowAllUsers.'');
    }

    public static function redirectProfile()
    {
        header('Location: ?'.NavigationView::$action.'='.NavigationView::$actionShowProfile.'');
    }
}