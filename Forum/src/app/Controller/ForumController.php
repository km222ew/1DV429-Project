<?php
require_once('src/app/View/ForumView.php');
require_once('src/app/Model/ForumModel.php');
require_once('src/app/Model/Reply.php');
require_once('src/app/Model/Thread.php');

class ForumController
{
	private $forumView;
    private $forumModel;

    public function __construct(Notify $notify, UserRepository $userRep)
    {
        $this->forumView = new ForumView($notify);
        $this->forumModel = new ForumModel($notify, $userRep);
    }

    public function ShowForum($hasModRights, $username)
    {
		if ($this->forumView->DidUserCreateThread() != null)
    	{
    		$thread = new Thread(null, $this->forumView->GetTopic(), $username);
    		$reply = new Reply(null, null, $this->forumView->GetBody(), $username);
    		$this->forumModel->CreateThread($thread, $reply);
    		NavigationView::redirectForum();
    	}

    	if ($this->forumView->AdminDidRemoveThread())
    	{
    		$this->forumModel->RemoveThread($this->forumView->GetThreadIdForRemove(), $username);
    		NavigationView::redirectForum();
    	}

    	if ($this->forumView->AdminDidRemoveReply())
    	{
    		$this->forumModel->RemoveReply($this->forumView->GetReplyIdForRemove(), $username);
    		NavigationView::redirectForum();
    	}

    	if ($this->forumView->DidUserRequestCreateThread())
    	{
    		return $this->forumView->RenderCreateThread();
    	}

    	if ($this->forumView->DidUserPostReply())
    	{
    		$thread_id = $this->forumView->GetSelectedId();
    		$this->forumModel->ValidateAndPostReply($thread_id, $this->forumView->GetReplyBody(), $username);
    		header("Location: " . $_SERVER['REQUEST_URI']);
    		return $this->forumView->RenderSelectedTopic($this->forumModel->GetTopic($thread_id), $this->forumModel->GetTopicReplies($thread_id), $hasModRights);
    	}

    	if ($this->forumView->TopicWasSelected())
    	{
    		$thread_id = $this->forumView->GetSelectedId();

    		$topic = $this->forumModel->GetTopic($thread_id);

    		if (!$topic)
    			return $this->forumView->RenderForum($hasModRights, $this->forumModel->GetAllThreads());

    		return $this->forumView->RenderSelectedTopic($topic, $this->forumModel->GetTopicReplies($thread_id), $hasModRights);
    	}

    	//If thread id is set, show the thread instead.

    	return $this->forumView->RenderForum($hasModRights, $this->forumModel->GetAllThreads());
    }
}