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
    		$reply = new Reply(null, $this->forumView->GetBody(), $username);
    		$this->forumModel->CreateThread($thread, $reply);
    	}

    	if ($this->forumView->DidUserRequestCreateThread())
    	{
    		return $this->forumView->RenderCreateThread();
    	}

    	//If thread id is set, show the thread instead.

    	return $this->forumView->RenderForum($hasModRights, $this->forumModel->GetAllThreads());
    }
}