 <?php
	session_start();

	include_once("restlib.php");

	$request = new RestRequest();

	$db = connect_to_db();	

	//echo "\ntype of request recieved: " . $request->getRequestType();

	$reqVars = $request->getRequestVariables(); 
	
if($request->isGet()) {	
	//curl -X GET http://web.cs.georgefox.edu/~srank20/blog/rest/comment.php/?postid=1
	//curl -X GET 192.168.56.101/blog/rest/post.php/?id=0
	//neither id exists return everything.
	//postid Only
	if(array_key_exists("postid", $reqVars)) {
		$postid = $reqVars["postid"];
		//echo "\n PostID: ";
		//echo $postid;	
		//$sql = "select b.username, b.id userid, a.id postid, a.post_date, a.post_text, a.extra from post a inner join blog_user b on b.id = a.user_id where a.id = ? order by a.id desc";
		//$sql = "select c.username, a.userid, a.comment_text, a.comment_date from blog_comment a inner join blog_user b on b.id = a.userid where id = ? order by a.id desc";
		$sql = "select c.username, a.user_id, a.comment_text, a.comment_date from blog_comment a inner join post b on b.id = a.post_id inner join blog_user c on c.id = a.user_id where b.id = ? order by a.id asc";
		$statement = $db->prepare($sql);
		$statement->execute([$postid]);
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		//$result[""] = $results[0]["invoice_num"];
		//if($results[0]["userid"] == $_SESSION["id"]) {
			//$results[0]["amItheowner"] = "yes";
		//} else {
			//$results[0]["amItheowner"] = "no";
		//}
		echo json_encode($results);
	}
	//both exist 
	else {
		echo "";
	}
}

 else if($request->isPost()) {
	 
	 // DO NOT USE BELOW COMMAND FOR TWO REASONS: 1. EXTRA IS NOT CORRECT OR PRESENT 2. WHEN RUN IN CURL YOU HAVE NO SESSION ID
	 //curl -X POST -H "Content-Type: application/json" 192.168.56.101/blog/rest/post.php -d "{\"charname\":\"Joker\",\"universe\":\"Persona 5\",\"creator\":\"Atlus\",\"voiceby\":\"Xander Mobus\",\"posttext\":\"He is cool\"}"
	 //echo "\nmade into if statement";
	 //define('KEYS',array('post_text', 'extra');
	 //ensure_no_extra_keys($reqVars, KEYS);
	 //found_all_keys($reqVars, KEYS);
	 
	 //$post_date = $reqVars["post_date"];
	 
	 $sessUserID = $_SESSION["id"];
	 //echo "\nUserID:" . strval($sessUserID);
	 $postid = trim($reqVars["postid"]);
	 $comment_text = trim($reqVars["commenttext"]);
	 //echo "\nposttext:" . $post_text;
	 
	 $sqlid = "select MAX(id) from blog_comment";
	 $statementid = $db->prepare($sqlid);
	 $statementid->execute();
	 $resultmax = $statementid->fetch(PDO::FETCH_ASSOC);
	 $tempjson = json_encode($resultmax);
	 $newresult = json_decode($tempjson, true);
	 $resultid = $newresult['max'];
	 $id = (int)$resultid + 1;
	 //echo "\nIncremented ID:" . strval($id);
	 
	 $sql = "insert into blog_comment (id, user_id, post_id, comment_text, comment_date) values (?, ?, ?, ?, ?)";
	 
	 $statement = $db->prepare($sql);
	 
	 //echo "\nPost Date:" . strval(date("m/d/Y"));
	 $data = array($id, $sessUserID, $postid, $comment_text, date("m/d/Y"));
	 $statement->execute($data);
	 echo json_encode(array('results' => "Success"));
 }
 
 else if($request->isPut()) {
	 //curl -X PUT -H "Content-Type: application/json" 192.168.56.101/blog/rest/post.php -d "{\"charname\":\"Joker\",\"universe\":\"Persona 5\",\"creator\":\"Atlus\",\"voiceby\":\"Xander Mobus\",\"post_text\":\"He is cool and awesome\",\"postid\":\"6\"}"

	 $commentid = trim($reqVars["commentid"]);
	 $comment_text = trim($reqVars["commenttext"]);
	 
	 $sql = "update blog_comment set comment_text = ? where id = ?";
	 
	 $statement = $db->prepare($sql);
	 
	 $data = array($comment_text, $commentid);
	 
	 $statement->execute($data);
	 echo json_encode(array('results' => "Success"));
 }
 
 else if($request->isDelete()) {
	 //curl -X DELETE 192.168.56.101/blog/rest/post.php/?id=0
	 //curl -X DELETE -H "Content-Type: application/json" 192.168.56.101/blog/rest/comment.php -d "{\"commentid\":\"16\"}"
	 if(array_key_exists("commentid", $reqVars)) {
		 $id = $reqVars["commentid"];
		 $sql = "delete from blog_comment where id = ?";
	 
		 $statement = $db->prepare($sql);
		 $statement->execute([$id]);
		 echo json_encode(array('results' => "Success"));
	 }
	 
 }
?>