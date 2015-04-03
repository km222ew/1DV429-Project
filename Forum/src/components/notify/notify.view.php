<?php

//Presents notifications
class NotifyView
{
	private $notify;

	public function __construct(Notify $notify)
    {
		$this->notify = $notify;
	} 

	//Build html from all notifications and return
	public function showAll()
    {
		$builtNotifications = '';
		$notifications = $this->notify->getNotifications();

		//Make sure there are notifications
		if (count($notifications) > 0)
        {
			foreach ($notifications as $notification)
            {
				//Notifications were serialized to be put in session array
				$notification = unserialize($notification);
				$builtNotifications .= $this->buildNotification($notification);
			}
		}

		return $builtNotifications;
	}

	//Build html from notification (uses bootstrap alerts)
	private function buildNotification($notification)
    {
		$type = $notification->type;
		$header = $notification->header;
		$message = $notification->message;

		return "<div class='alert alert-$type alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                    <h3>$header</h3> <h4>$message</h4>
                </div>";
	}
}