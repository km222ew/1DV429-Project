<?php

class Reply
{
	private $reply_id;
	private $thread_id;
	private $body;
	private $user;

	public function __construct($reply_id, $thread_id, $body, $user)
	{
		$this->reply_id = $reply_id;
		$this->thread_id = $thread_id;
		$this->body = $body;
		$this->user = $user;
	}

	public function GetReplyId()
	{
		return $this->reply_id;
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

	public function SetBody($body)
	{	
		return $this->body = $body;;
	}
}