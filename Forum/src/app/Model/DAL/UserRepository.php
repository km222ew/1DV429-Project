<?php

require_once("Repository.php");

class UserRepository extends Repository
{
    private $db;
    private $dbTable;
    private $dbTableRole;
    private $dbTableLoginLog;
    private $dbTableThread;
    private $dbTableThreadReply;
    private $dbTablePasswordLog;
    private $dbTableReplyLog;
    private $dbTableThreadLog;

    //Db columns 'user'
    private static $username = "username";
    private static $password = "password";

    //DB columns 'user_role'
    //"username"
    private static $role_id = "role_id";

    //DB columns 'user_login_log'
    private static $id = "id";
    //"username"
    private static $time = "time";
    private static $outcome = "outcome";

    //DB columns 'thread'
    private static $topic = "topic";
    private static $creator = "creator";
    private static $creation_time = "creation_time";

    //DB columns 'thread_reply'
    private static $reply_id = "reply_id";
    private static $thread_id = "thread_id";
    private static $body = "body";
    private static $user = "user";
    private static $reply_time = "reply_time";

    //DB columns 'password_change_log'
    private static $pcl_id = "pcl_id";
    private static $time_of_occurance = "time_of_occurance";
    //"useranme"
    private static $ip_adress = "ip_adress";

    //DB columns 'reply_removal_log'
    private static $rr_id = "rr_id";
    //"thread_id"
    //"body"
    //"useranme"
    //"time_of_occurance"

    //DB columns 'password_change_log'
    private static $r_id = "tr_id";
    //"topic"
    //"useranme"
    //"time_of_occurance"

    public function __construct()
    {
        $this->dbTable = "user";
        $this->dbTableRole = "user_role";
        $this->dbTableLoginLog = "user_login_log";
        $this->dbTableThread = "thread";
        $this->dbTableThreadReply = "thread_reply";
        $this->dbTablePasswordLog = "password_change_log";
        $this->dbTableReplyLog = "reply_removal_log";
        $this->dbTableThreadLog = "topic_removal_log";
        $this->db = $this->connectionUser();
    }

    //Insert new user into database
    public function addUser(User $user)
    {
        try
        {
            $sql = "INSERT INTO $this->dbTable (". self::$username . ", " . self::$password . ") VALUES (?, ?)";
            $params = array($user->getUsername(), $user->getPassword());

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $sql = "INSERT INTO $this->dbTableRole (". self::$username . ") VALUES (?)";
            $params = array($user->getUsername());

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 222");
        }
    }

    public function getUserByName($username)
    {
        try
        {
            $sql = "SELECT * FROM $this->dbTable d INNER JOIN $this->dbTableRole c ON d." . self::$username . "=c." . self::$username . " WHERE " . "d." . self::$username . " = ?";
            $params = array($username);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetch();

            if($result)
            {
                $user = new User($result[self::$username],
                                        $result[self::$password],
                                        $result[self::$role_id]);

                return $user;
            }

            return null;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 123");
        }
    }

    public function GetAllUsers()
    {
        try
        {
            $sql = "SELECT * FROM $this->dbTable d INNER JOIN $this->dbTableRole c ON d." . self::$username . "=c." . self::$username . " WHERE c." . self::$role_id . " != " . ROLE::$ADMIN;

            $query = $this->db->prepare($sql);
            $query->execute();  

            $result = $query->fetchAll();

            if (!$result)
                return null;

            $userArray = Array();

            foreach($result as $row)
            {
                $user = new User($row[self::$username], $row[self::$password], $row[self::$role_id]);
                array_push($userArray, $user);
            }

            return $userArray;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 777");
        }
    }

    public function UpdateUserPassword($username, $password)
    {
        try
        {
            $sql = "UPDATE $this->dbTable SET " . self::$password . " = ?" . " WHERE " . self::$username . " = ?";

            $params = array($password, $username);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            return true;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 323");
        }
    }

    public function UpdateUserRole($username, $role_id)
    {
        try
        {
            $sql = "UPDATE $this->dbTableRole SET " . self::$role_id . " = ?" . " WHERE " . self::$username . " = ?";

            $params = array($role_id, $username);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            return true;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 353");
        }
    }

    public function GetUserCurrentPassword($username)
    {
        try
        {
            $sql = "SELECT " . self::$password . " FROM $this->dbTable WHERE " . self::$username . " = ?";

            $params = array($username);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetch();
            if ($result)
                return $result[self::$password];

            return null;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 323");
        }
    }

    //Logging part
    public function LogAttemptAtLogin($username, $outcome)
    {
        try
        {
            $sql = "INSERT INTO $this->dbTableLoginLog (". self::$username . ", " . self::$outcome . ") VALUES (?, ?)";
            $params = array($username, $outcome);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 444");
        }
    }

    public function CheckUserLoginAttempts($username)
    {
        try
        {
            $sql = "SELECT COUNT(*) FROM $this->dbTableLoginLog WHERE " . self::$username . " = ? AND " . self::$time . " > DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND " . self::$outcome . " = ?"; 

            $params = array($username, 0);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetch();

            return $result[0] >= 5;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 555");
        }
    }

    //Forum part
    public function CreateThread(Thread $thread, Reply $reply)
    {
        try
        {
            $sql = "INSERT INTO $this->dbTableThread (". self::$topic . ", " . self::$creator . ") VALUES (?, ?)";
            $params = array($thread->GetTopic(), $thread->GetCreator());

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $thread_id = $this->db->lastInsertId();

            $sql = "INSERT INTO $this->dbTableThreadReply (". self::$thread_id . ", " . self::$body . ", " . self::$user . ") VALUES (?, ?, ?)";
            $params = array($thread_id, $reply->GetBody(), $reply->GetUser());

            $query = $this->db->prepare($sql);
            $query->execute($params);

            return $thread_id;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            die("An error has occurred. Error code 267");
        }
    }

    public function GetForumThreads()
    {
        try
        {
            $sql = "SELECT * FROM $this->dbTableThread ORDER BY " . self::$creation_time . " DESC";

            $query = $this->db->prepare($sql);
            $query->execute();  

            $result = $query->fetchAll();

            if (!$result)
                return null;

            $threads = Array();

            foreach($result as $row)
            {
                $thread = new Thread($row[self::$thread_id], $row[self::$topic], $row[self::$creator]);
                array_push($threads, $thread);
            }

            return $threads;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 797");
        }
    }

    public function GetThread($thread_id)
    {
        try
        {
            $sql = "SELECT * FROM $this->dbTableThread WHERE " . self::$thread_id . " = ?";
            $params = array($thread_id);

            $query = $this->db->prepare($sql);
            $query->execute($params);  

            $result = $query->fetch();

            if ($result)
                return new Thread($result[self::$thread_id], $result[self::$topic], $result[self::$creator]);

            return null;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 915");
        }
    }

    public function RemoveThread($thread_id)
    {
        try
        {
            $sql = "DELETE FROM $this->dbTableThread WHERE " . self::$thread_id . " = ?";
            $params = array($thread_id);

            $query = $this->db->prepare($sql);
            $query->execute($params);  
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 955");
        }
    }

    public function GetReply($reply_id)
    {
        try
        {
            $sql = "SELECT * FROM $this->dbTableThreadReply WHERE " . self::$reply_id . " = ?";
            $params = array($reply_id);

            $query = $this->db->prepare($sql);
            $query->execute($params);  

            $result = $query->fetch();

            if ($result)
                return new Reply($result[self::$reply_id], $result[self::$thread_id], $result[self::$topic], $result[self::$creator]);

            return null;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 915");
        }
    }

    public function GetRepliesOnThreadID($thread_id)
    {
        try
        {
            $sql = "SELECT * FROM $this->dbTableThreadReply WHERE " . self::$thread_id . " = ? ORDER BY " . self::$reply_time . " ASC";
            $params = array($thread_id);

            $query = $this->db->prepare($sql);
            $query->execute($params);  

            $result = $query->fetchAll();

            if (!$result)
                return null;

            $replies = Array();

            foreach($result as $row)
            {
                $reply = new Reply($row[self::$reply_id], $row[self::$thread_id], $row[self::$body], $row[self::$user]);
                array_push($replies, $reply);
            }

            return $replies;
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 787");
        }
    }

    public function AddReplyToThread($thread_id, $body, $user)
    {
        try
        {
            $sql = "INSERT INTO $this->dbTableThreadReply (". self::$thread_id . ", " . self::$body . ", " . self::$user . ") VALUES (?, ?, ?)";
            $params = array($thread_id, $body, $user);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 737");
        }
    }

    public function RemoveReply($reply_id)
    {
        try
        {
            $sql = "DELETE FROM $this->dbTableThreadReply WHERE " . self::$reply_id . " = ?";
            $params = array($reply_id);

            $query = $this->db->prepare($sql);
            $query->execute($params);  
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 721");
        }
    }

    //logging
    public function PasswordChangeLog($username, $ip_adress)
    {
        try
        {
            $sql = "INSERT INTO $this->dbTablePasswordLog (" . self::$username . ", " . self::$ip_adress . ") VALUES (?, ?)";
            $params = array($username, $ip_adress);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 737");
        }
    }

    public function TopicRemovalLog($topic, $username)
    {
        try
        {
            $sql = "INSERT INTO $this->dbTableThreadLog (" . self::$topic . ", " . self::$username . ") VALUES (?, ?)";
            $params = array($topic, $username);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 737");
        }
    }

    public function ReplyRemovalLog($thread_id, $body, $username)
    {
        try
        {
            $sql = "INSERT INTO $this->dbTableReplyLog (" . self::$thread_id . ", " . self::$body . ", " . self::$username . ") VALUES (?, ?, ?)";
            $params = array($thread_id, $body, $username);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e)
        {
            die("An error has occurred. Error code 737");
        }
    }
}