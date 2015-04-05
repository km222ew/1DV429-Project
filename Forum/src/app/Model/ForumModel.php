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
    	//ADD VALIDATION

    	if (strlen($thread->GetTopic()) > 100)
		{
    		$this->notify->error("The reply can be max 100 characters.");
    		return false;
    	}

    	$thread->SetTopic(htmlspecialchars($thread->GetTopic()));

    	if ($thread->GetTopic() == "")
    	{
    		$this->notify->error("Your reply can not be empty.");
    		return false;
    	}

    	if (strlen($reply->GetBody()) > 3000)
    	{
    		$this->notify->error("The reply can be max 3000 characters.");
    		return false;
    	}

    	$reply->SetBody(htmlspecialchars($reply->GetBody()));

    	if ($reply->GetBody() == "")
    	{
    		$this->notify->error("Your reply can not be empty.");
    		return false;
    	}

    	$this->userRep->CreateThread($thread, $reply);
    }

    public function GetTopic($thread_id)
    {
    	$topic = $this->userRep->GetThread($thread_id);

    	if ($topic == null)
    	{
    		$this->notify->error("Unable to find topic.");
    		return false;
    	}

		return $topic;
    }

    public function GetTopicReplies($thread_id)
    {
		return $this->userRep->GetRepliesOnThreadID($thread_id);
    }

    public function ValidateAndPostReply($thread_id, $body, $user)
    {
    	if (strlen($body) > 3000)
    	{
    		$this->notify->error("The reply can be max 3000 characters.");
    		return false;
    	}

    	$body = htmlspecialchars($body);

    	if ($body == "")
    	{
    		$this->notify->error("Your reply can not be empty.");
    		return false;
    	}

    	$this->userRep->AddReplyToThread($thread_id, $body, $user);
    }

    public function RemoveThread($thread_id, $username)
    {
    	$thread = $this->userRep->GetThread($thread_id);

    	$replies = $this->userRep->GetRepliesOnThreadID($thread_id);

    	$this->userRep->RemoveThread($thread_id);

    	//We log the removal of the replies aswell, but we don't need to delete them, the database handles it.
    	if ($replies != null)
    		foreach ($replies as $reply)
    		{
    			$this->userRep->ReplyRemovalLog($reply->GetThreadId(), $reply->GetBody(), $username);
    		}

    	$this->userRep->TopicRemovalLog($thread->GetTopic(), $username);

    	$this->notify->success("Thread removed.");
    }

    public function RemoveReply($reply_id, $username)
    {
    	$reply = $this->userRep->GetReply($thread_id);

    	$this->userRep->RemoveReply($reply_id);

    	$this->userRep->ReplyRemovalLog($reply->GetThreadId(), $reply->GetBody(), $username);

    	$this->notify->success("Reply removed from thread.");
    }
}