<?php

class ForumView
{
	private $notify;

	private static $create_thread = "create_new_thread";
	private static $topic = "topic";
	private static $body = "body";

	public function __construct(Notify $notify)
    {
        $this->notify = $notify;
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
                       <h4><a href='?action=". NavigationView::$actionTopic ."&id=".$thread->GetThreadId()."'>$topic</a></h4>
                    </div>
                    <div class='col-lg-5'>
                        <h4>$creator</h4>
                    </div>";
                    
                        // if ($hasModRights)
                        // {
                        //     $HTML .= 
                        //             "<div class='col-lg-2'><button type='submit' name='".self::$demote."' class='btn btn-primary btn-block' value='Demote'>Demote</button>
                        //             </div>";
                        // }
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
    				<input type='text' class='form-control input-lg marginb' name='".self::$topic."' placeholder='Topic' required autofocus></input>
    				<textarea name='".self::$body."' class='form-control input-lg marginb' placeholder='Content' required></textarea>
    				<input type='submit' class='btn btn-primary btn-block' value='Create thread' name='".self::$create_thread."'></input>
    			</form>
    			";

    	return $HTML;
    }
}