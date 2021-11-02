 <?php
	session_start();

	include_once("restlib.php");

	$request = new RestRequest();

	$db = connect_to_db();

	$reqVars = $request->getRequestVariables(); 
	
if($request->isGet()) {
	//curl -X GET 192.168.56.101/blog/rest/post.php/?id=0	
	//curl -X GET http://web.cs.georgefox.edu/~srank20/blog/rest/post.php/?username=sophia
	$usernameexsists = false;
	if(isset($_GET['username'])){
		$username = $_GET['username'];
		$usernameexsists = true;
	}
	
	$useridexsists = false;
	if(isset($_GET['userid'])){
		$userid = $_GET['userid'];
		$useridexsists = true;
	}
	
	$postidexsists = false;
	if(isset($_GET['postid'])){
		$postid = $_GET['postid'];
		$postidexsists = true;
	}
	
	
	//echo $username;
	//neither id exists return everything.
	if(!$useridexsists && !$postidexsists && !$usernameexsists) {
		$sql = "select b.username, b.id userid, a.id postid, a.post_date, a.post_text, a.extra from post a inner join blog_user b on b.id = a.user_id order by a.id desc";
		$statement = $db->prepare($sql);
		$statement->execute([]);
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($results);
	}
	//user id only
	if($useridexsists && !$postidexsists && !$usernameexsists) {
		//echo "\n UserID: ";
		//echo $userid;	
		$sql = "select b.username, b.id userid, a.id postid, a.post_date, a.post_text, a.extra from post a inner join blog_user b on b.id = a.user_id where b.id = ? order by a.id desc";
		$statement = $db->prepare($sql);
		$statement->execute([$userid]);
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($results);
	}
		//username only
	if(!$useridexsists && !$postidexsists && $usernameexsists) {
		//echo "\n UserID: ";
		//echo $userid;	
		$sql = "select b.username, b.id userid, a.id postid, a.post_date, a.post_text, a.extra from post a inner join blog_user b on b.id = a.user_id where b.username = ? order by a.id desc";
		$statement = $db->prepare($sql);
		$statement->execute([$username]);
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($results);
		exit();
	}
	//postid Only
	if(!$useridexsists && $postidexsists && !$usernameexsists) {
		//echo "\n PostID: ";
		//echo $postid;	
		$sql = "select b.username, b.id userid, a.id postid, a.post_date, a.post_text, a.extra from post a inner join blog_user b on b.id = a.user_id where a.id = ? order by a.id desc";

		$statement = $db->prepare($sql);
		$statement->execute([$postid]);
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		//$result[""] = $results[0]["invoice_num"];
		if($results[0]["userid"] == $_SESSION["id"]) {
			$results[0]["amItheowner"] = "yes";
		} else {
			$results[0]["amItheowner"] = "no";
		}
		echo json_encode($results);
	}
	//both exist 
	else {
		echo "";
	}
}

 else if($request->isPost()) {
	 
	 $sessUserID = $_SESSION["id"];
	 //echo "\nUserID:" . strval($sessUserID);
	 
     $charname = trim($reqVars["charname"]);
	 //echo "\nCharName:" . $charname;
	 $universe = trim($reqVars["universe"]);
	 //echo "\nuniverse:" . $universe;
	 $creator = trim($reqVars["creator"]);
	 //echo "\ncreator:" . $creator;
	 $voiceby = trim($reqVars["voiceby"]);
	 //echo "\nvoiceby:" . $voiceby;
	 $post_text = trim($reqVars["posttext"]);
	 //echo "\nposttext:" . $post_text;
	 
	 $sqlid = "select MAX(id) from post";
	 $statementid = $db->prepare($sqlid);
	 $statementid->execute();
	 $resultmax = $statementid->fetch(PDO::FETCH_ASSOC);
	 $tempjson = json_encode($resultmax);
	 $newresult = json_decode($tempjson, true);
	 $resultid = $newresult['max'];
	 $id = (int)$resultid + 1;
	 //echo "\nIncremented ID:" . strval($id);
	 
	 $sql = "insert into post (id, user_id, post_date, post_text, extra) values (?, ?, ?, ?, ?)";
	 
	 $statement = $db->prepare($sql);
	 $arr = array('charname' => $charname, 'universe' => $universe, 'creator' => $creator, 'voiceby' => $voiceby);

	 $extra = json_encode($arr);
	 //echo "\nextra:" . $extra;
	 
	 //echo "\nPost Date:" . strval(date("m/d/Y"));
	 $data = array($id, $sessUserID, date("m/d/Y"), $post_text, $extra);
	 $statement->execute($data);
	 echo json_encode(array('results' => "Success"));
 }
 
 else if($request->isPut()) {
	 //curl -X PUT -H "Content-Type: application/json" 192.168.56.101/blog/rest/post.php -d "{\"charname\":\"Joker\",\"universe\":\"Persona 5\",\"creator\":\"Atlus\",\"voiceby\":\"Xander Mobus\",\"post_text\":\"He is cool and awesome\",\"postid\":\"6\"}"

	 $postid = trim($reqVars["postid"]);
	 $charname = trim($reqVars["charname"]);
	 $universe = trim($reqVars["universe"]);
	 $creator = trim($reqVars["creator"]);
	 $voiceby = trim($reqVars["voiceby"]);
	 $post_text = trim($reqVars["posttext"]);
	 
	 $sql = "update post set post_text = ?, extra = ? where id = ?";
	 
	 $statement = $db->prepare($sql);
	 $arr = array('charname' => $charname, 'universe' => $universe, 'creator' => $creator, 'voiceby' => $voiceby);
	 
	 $extra = json_encode($arr);
	 
	 $data = array($post_text, $extra, $postid);
	 
	 $statement->execute($data);
	 echo json_encode(array('results' => "Success"));
 }
 
 else if($request->isDelete()) {
	 //curl -X DELETE -H "Content-Type: application/json" 192.168.56.101/blog/rest/post.php -d "{\"postid\":\"16\"}"
	 if(array_key_exists("postid", $reqVars)) {
		 $id = $reqVars["postid"];
		 $sql = "delete from blog_comment where post_id = ?";
		 $statement = $db->prepare($sql);
		 $statement->execute([$id]);
		 
		 $sqlii = "delete from post where id = ?";
		 $statementii = $db->prepare($sqlii);
		 $statementii->execute([$id]);
		 echo json_encode(array('results' => "Success"));
	 }
	 
 }
?>