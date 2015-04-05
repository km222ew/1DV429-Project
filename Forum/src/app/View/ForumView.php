<?php

class ForumView
{
	private $notify;

	private static $create_thread = "create_new_thread";
	private static $topic = "topic";
	private static $body = "body";
	private static $id = "id";
	private static $reply = "reply";
	private static $remove = "remove";
	private static $remove_reply = "remove_reply";
	private static $reply_id = "reply_id";

	public function __construct(Notify $notify)
    {
        $this->notify = $notify;
    }

    public function AdminDidRemoveReply()
    {
    	if (isset($_POST[self::$remove_reply]))
    		return true;

    	return false;
    }

    public function GetReplyIdForRemove()
    {
    	if (isset($_POST[self::$reply_id]))
    		return $_POST[self::$reply_id];

    	return null;
    }

    public function AdminDidRemoveThread()
    {
    	if (isset($_POST[self::$remove]))
    		return true;

    	return false;
    }

    public function GetThreadIdForRemove()
    {
    	if (isset($_POST[self::$id]))
    		return $_POST[self::$id];

    	return null;
    }

    public function TopicWasSelected()
    {
    	if (isset($_GET[self::$id]))
    		return true;

    	return false;
    }

    public function GetSelectedId()
    {
    	if (isset($_GET[self::$id]))
    		return $_GET[self::$id];

    	return null;
    }

    public function DidUserPostReply()
    {
    	if (isset($_POST[self::$reply]))
    		return true;

    	return false;
    }

    public function GetReplyBody()
    {
    	if (isset($_POST[self::$body]))
    		return $_POST[self::$body];

    	return null;
    }

    public function DidUserCreateThread()
    {
    	if (isset($_POST[self::$create_thread]))
    		return $_POST[self::$create_thread];

    	return null;
    }

    public function GetTopic()
    {
    	if (isset($_POST[self::$topic]))
    		return $_POST[self::$topic];

    	return null;
    }

    public function GetBody()
    {
    	if (isset($_POST[self::$body]))
    		return $_POST[self::$body];

    	return null;
    }

    public function DidUserRequestCreateThread()
    {
    	if (isset($_GET["action"]) && $_GET["action"] == NavigationView::$actionCreateThread)
    		return true;

    	return false;
    }

    public function RenderForum($hasModRights, $threads)
    {
    	$HTML = "
                <div class='row'>
                    <div class=col-lg-5>
                    <h2>Topic</h2>
                    </div>
                    <div class=col-lg-5>
                    <h2>Creator</h2>
                    </div>
                    <div class=col-lg-2>
                    <h2>Actions</h2>
                    </div>
                </div>
                <hr/>
                <div class='row height'>
                    <div class='col-lg-5'>
                    </div>
                    <div class='col-lg-5'>
                    </div>  
                    <div class=col-lg-2>
                    	<a href='?action=".NavigationView::$actionCreateThread."' class='btn btn-lg btn-primary'>New thread</a>
                    </div>
            </div>
            <hr/>";

        if ($threads == null)
        	return $HTML;

        foreach($threads as $thread)
        {
            $topic = $thread->GetTopic();
            $creator = $thread->GetCreator();

            $HTML .= 
            "<div class='row height'>
                <form role='form' action='' method='post'>
                    <div class='col-lg-5'>
                       <h4><a href='?action=". NavigationView::$actionTopic ."&".self::$id."=".$thread->GetThreadId()."'>$topic</a></h4>
                    </div>
                    <div class='col-lg-5'>
                        <h4>$creator</h4>
                    </div>";
                        if ($hasModRights)
                        {
                            $HTML .=
                                    "<input type='hidden' value='".$thread->GetThreadId()."' name='".self::$id."'></input>
                                    <div class='col-lg-2'><button type='submit' name='".self::$remove."' class='btn btn-primary btn-block' value='Remove'>Delete thread</button>
                                    </div>";
                        }
                        $HTML .=
                        "   
                </form>
            </div>";
        }

    	return $HTML;
    }

    public function RenderCreateThread()
    {
    	$HTML = 
    			"<form role='form' action='' method='post' class='form-signin'>
    				<input type='text' class='form-control input-lg marginb' name='".self::$topic."' placeholder='Topic'  maxlength='100' required autofocus></input>
    				<textarea name='".self::$body."' class='form-control input-lg marginb' placeholder='Content'  maxlength='3000' required></textarea>
    				<input type='submit' class='btn btn-primary btn-block' value='Create thread' name='".self::$create_thread."'></input>
    			</form>
    			";

    	return $HTML;
    }

    public function RenderSelectedTopic($topic, $topicReplies, $hasModRights)
    {
    		$HTML = "
    				<div class='panel panel-primary'>
					  <div class='panel-heading'>
					    <h2 class='panel-title panel-title-size'><b>Topic: ".$topic->GetTopic()."</b></h2>
					  </div>
					</div>";

		if ($topicReplies != null)
	    	foreach($topicReplies as $reply)
	    	{
	    		$HTML .= 
	    				"<div class='panel panel-info'>
						  <div class='panel-heading'>
						    <div class='row'>
						    	<div class='col-lg-10'>
						    		<h3 class='panel-title'>Posted by: ".$reply->GetUser()."</h3>
						    	</div>
					    ";

						   	if ($hasModRights)
	                        {
	                            $HTML .= 
	                                    "
	                                    <form role='form' action='' method='post'>
		                                    <input type='hidden' value='".$reply->GetReplyId()."' name='".self::$reply_id."'></input>
		                                    <div class='col-lg-2'><button type='submit' name='".self::$remove_reply."' class='btn btn-primary btn-block' value='Remove'>Delete reply</button></div>
	                                	</form>
	                                	";
	                        }

		    	$HTML .=
					    "</div>
						  </div>
						  <div class='panel-body'>
						    ".$reply->GetBody()."
						  </div>
						</div>

	    				";
	    	}

    	$HTML .=
    			"<form role='form' action='' method='post' class='form-signin'>
    				<h3>Reply to thread</h3>
    				<textarea name='".self::$body."' class='form-control input-lg marginb' placeholder='Reply content'  maxlength='3000' required></textarea>
    				<input type='submit' class='btn btn-primary btn-block' value='Reply' name='".self::$reply."'></input>
    			</form>
    			";

    	return $HTML;
    }
}