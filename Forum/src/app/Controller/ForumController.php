<?php

class ForumController
{
	private $forumView;
    private $forumModel;

    public function __construct(Notify $notify, UserRepository $userRep)
    {
        $this->forumView = new ForumView($notify);
        $this->forumModel = new ForumModel($notify, $userRep);
    }
}