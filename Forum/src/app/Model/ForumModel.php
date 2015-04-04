<?php

class ForumModel
{
	private $notify;
	private $userRep;

	public function __construct(Notify $notify, UserRepository $userRep)
    {
        $this->notify = $notify;
        $this->userRep = $userRep;
    }

    public function GetAllThreads()
    {
    	return $this->userRep->GetForumThreads();
    }

    public function CreateThread($thread, $reply)
    {
    	$this->userRep->CreateThread($thread, $reply);
    }
}