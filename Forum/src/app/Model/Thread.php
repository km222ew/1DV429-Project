<?php

class Thread
{
	private $thread_id;
	private $topic;
	private $creator;

	public function __construct($thread_id, $topic, $creator)
	{
		$this->thread_id = $thread_id;
		$this->topic = $topic;
		$this->creator = $creator;
	}

	public function GetThreadId()
	{
		return $this->thread_id;
	}

	public function GetTopic()
	{
		return $this->topic;
	}

	public function GetCreator()
	{	
		return $this->creator;
	}
}