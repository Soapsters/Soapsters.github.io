<?php
/**
A class to wrap REST requests
*/
class RestRequest
{
	const REQ = 'REQUEST_METHOD';
	const GET = 'GET';
	const POST = 'POST';
	const PUT = 'PUT';
	const DEL = 'DELETE';

	private $requestType;

	/**
	Initialize the Rest Request
	*/
	function __construct()
	{
		$this->requestType = $_SERVER[self::REQ];
	}

	/**
	Returns the request variables
	*/
	function getRequestVariables()
	{
		$vars = null;

		//find the get variables
		if($this->isGet())
		{
			$vars = $_GET;
		}
		//otherwise decode the post, put, or delete vars
		else
		{
			$input = file_get_contents('php://input');

			if(strlen($input))
			{
				$vars = json_decode($input, true);
			}
			else
			{
				$vars = [];
			}

			//echo an error for debugging
			if (is_null($vars) && json_last_error()) {
				echo 'JSON Error: '.json_last_error_msg().'<br />'.PHP_EOL;
			}
		}
		
		return $vars;
	}

	/**
	Returns the request type
	*/
	function getRequestType()
	{
		return $this->requestType;
	}

	/**
	Returns true if the request is GET
	*/
	function isGet()
	{
		return $this->requestType === self::GET;
	}

	/**
	Returns true if the request is POST
	*/
	function isPost()
	{
		return $this->requestType === self::POST;
	}

	/**
	Returns true if the request is PUT
	*/
	function isPut()
	{
		return $this->requestType === self::PUT;
	}

	/**
	Returns true if the request is DELETE
	*/
	function isDelete()
	{
		return $this->requestType === self::DEL;
	}
}
function connect_to_db()
{
    $db = new PDO("pgsql:dbname=blog_srank20 host=localhost user=srank20 password=1993648");

    return $db;
}


function found_all_keys($inputs, $keys)
{
    $found_all = true;
    $msgs = array();

    foreach($keys as $key)
    {
        if(!array_key_exists($key, $inputs))
        {
            $found_all = false;

            array_push($msgs, "$key is missing");
        }
    }


    if(!$found_all)
    {
        $message = implode($msgs, ", ");

        $error = array("error_text" => $message);
        echo json_encode($error);
        exit();
    }
    else
    {
        return $found_all;
    }
}

function ensure_no_extra_keys($inputs, $keys)
{
	$msg = array();

	//check that there are no extra input parameters besides what is in keys
	foreach($inputs as $param => $value)
	{
		$param = trim($param);

		if(!in_array($param, $keys))
		{
			array_push($msg, "$param not a valid parameter");
		}
	}

	if(count($msg))
	{
		$message = implode($msg, ", ");

		$error = array("error_text" => $message);
		echo json_encode($error);
		exit();
	}
}


?>