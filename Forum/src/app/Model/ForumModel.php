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
}