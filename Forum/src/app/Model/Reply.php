<?php

class Reply
{
	private $thread_id;
	private $body;
	private $user;

	public function __construct($thread_id, $body, $user)
	{
		$this->thread_id = $thread_id;
		$this->body = $body;
		$this->user = $user;
	}

	public function GetThreadId()
	{
		return $this->thread_id;
	}

	public function GetBody()
	{
		return $this->body;
	}

	public function GetUser()
	{	
		return $this->user;
	}
}