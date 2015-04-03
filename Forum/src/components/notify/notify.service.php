<?php

//Should probably be NotifyModel
class Notify
{

	//Constructor, make sure session variable is an array
	public function __construct()
    {
		$this->prepareArray();
	}

	//Clears array
	private function clearArray()
    {
		unset($_SESSION['notifications']);
		$this->prepareArray();
	}

	//Make sure its an array
	private function prepareArray()
    {
		if (!isset($_SESSION['notifications']) || !is_array($_SESSION['notifications']))
        {
			$_SESSION['notifications'] = array();
		}
	}

	//Creates notification objects and adds to array
	private function create($type, $header, $message)
    {
		//Create notification
		$notification = new Notification($type, $header, $message);

		//We need to serialize to save object in array(since we're using session)
		$notification = serialize($notification);

		//Save to array (for some reason array_push didn't work)
		$_SESSION['notifications'][] = $notification;
	}

	//Creates error messages
	public function error($message, $header = 'Mistake')
    {
		$this->create('danger', $header, $message);
	}

	//Creates information messages
	public function info($message, $header = 'Info')
    {
		$this->create('info', $header, $message);
	}

	//Creates success messages
	public function success($message, $header = 'Success')
    {
		$this->create('success', $header, $message);
	}

	//Get all notifications, used by view
	public function getNotifications() {
		$notifications = $_SESSION['notifications'];
		//We only want to see messages once
		$this->clearArray();
		return $notifications;
	}
}