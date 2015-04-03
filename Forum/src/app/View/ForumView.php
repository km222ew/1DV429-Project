<?php

class ForumView
{
	private $notify;

	public function __construct(Notify $notify)
    {
        $this->notify = $notify;
    }
}