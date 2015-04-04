<?php
//Sends out html for the client
class Response
{
	//Renders html page
	public function HTMLPage($body, $notifyView, $isLoggedIn, $isAdmin)
    {
		if ($body === NULL)
        {
			throw new Exception('HTMLView::echoHTML does not allow body to be null');
		}

		$notifications = '';

		//Don't fetch notifications on post, these pages should never be shown
		if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
			$notifications = $notifyView->showAll();
		}

		$HTML = "
			<!DOCTYPE html>
			<html>
			<head>
				<title>1dv408, l2-login</title>
				<meta charset='utf-8'>
				<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
				<link rel='stylesheet' href='style/style.css'>
			</head>
			<body>
                <div class='container'>
                    <div class='page-header'>
                      <h1>Welcome!</h1>
                      <div class='row'>
                      	<div class='col-lg-10'>
	                      <ul class='nav nav-pills'>
							  <li role='presentation'><a href='?action=".NavigationView::$actionForum."'>Forum</a></li>
							  <li role='presentation'><a href='?action=".NavigationView::$actionShowProfile."'>Profile</a></li>";
							  if ($isAdmin)
							  {
							  	$HTML .= "<li role='presentation'><a href='?action=".NavigationView::$actionShowAllUsers."'>Show All users</a></li>";
							  }
					    $HTML .= "
					    	</ul>
						</div>";
						if ($isLoggedIn)
						{
							$HTML .= "
							<div class='col-lg-2'>
		                      <div class='text-center marginb'>
		                          <a href='?action=".NavigationView::$actionLogout."' class='btn btn-primary btn-block'>Sign out</a>
		                      </div>
		                    </div>";
	                	}
                 		$HTML .= "
                 		</div>
                    </div>
                    $notifications
                    $body
				    <br />
				    <hr>
				    <center>Made by Hampus Karlsson (HK222GN) and Kevin Madsen (KM222EW)</center>
				</div>
				<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
				<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
			</body>
			</html>";

			echo $HTML;
	}
}