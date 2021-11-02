
<?php include('MasterPages/lib.php');?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Video Game Characters View Post</title>

<?php include('MasterPages/HeaderIncludes.php');?>
</head>
<body>

	<?php include('MasterPages/Header.php');?>
	
<div class="container-fluid">
  <div class="row content">

	<?php include('MasterPages/Menu.php');?>

    <div class="col-sm-9">
      <h4><small>SINGLE POST</small></h4>
      <hr>
	  
	  <div id="PostsHTML"> </div>
	          <div id="editvalues" class="form-group">
		  This Post Was Created By You:  <div id="editusername"> </div><br>
		  It Was Created By You On:  <div id="editdate"> </div><br>
		  Character Name:<br>
          <textarea id="charname" class="form-control" rows="1" ></textarea>
		  Universe:<br>
          <textarea id="universe" class="form-control" rows="1"></textarea>
		  Game Creator:<br>
          <textarea id="creator" class="form-control" rows="1"></textarea>
		  Voiced By:<br>
          <textarea id="voiceby" class="form-control" rows="1""></textarea>
		  What I Like About This Character:<br>
          <textarea id="posttext" class="form-control" rows="5"></textarea>
		  <button id="delete" type="button" class="btn btn-default">Delete</button>
		  <button id="update" type="button" class="btn btn-default">Update Values For Post</button>
        </div>
		
		<div id="commentsection">
		</div>
		
      <h4>Leave a Comment:</h4>
      <form role="form">
        <div class="form-group">
          <textarea id="commenttext" class="form-control" rows="3" required></textarea>
        </div>
		<button id="submitcomment" type="button" class="btn btn-default">Submit Comment</button>
      </form>
      <br><br>
    </div>
  </div>
</div>

<div><?php include('MasterPages/Footer.php');?></div>




<script>
var IAmtheOwnerOfthePost = false;
var postid = "";


function updatecomments(){
	var base_url = window.location.origin;
	var url = base_url + "/~srank20/blog/rest/comment.php/?postid=" + postid;
	   $.ajax({
	   type: "GET",
	   url: url,
	   //dataType: "json",
	   success: function (msg) {
			var resp = JSON.parse(msg);
			var HTML = "";
			var base_url = window.location.origin;
			HTML += '<p><span class="badge">';
			HTML += resp.length.toString();
			HTML += '</span> Comments:</p><br>';
			HTML += '<div class="row">';
			$.each(resp, function(index, val){
				//HTML += '<div class="col-sm-2 text-center">';
				//HTML += '</div>';
				HTML += '<div class="col-sm-10">';
				HTML += '<h4>';
				HTML += val.username + "  ";
				HTML += '<small>';
				HTML += val.comment_date;
				HTML += '</small></h4>';
				HTML += '<p>';
				HTML += val.comment_text;
				HTML += '</p>';
				HTML += '<br>';
				HTML += '</div>';
			});
			HTML += '</div>';
			$("#commentsection").html(HTML);
	   },
	  error: function(xhr, status, error) {
		alert("An AJAX error occured: " + status + "\nError: " + error);
	  }
   });
}
$("#delete").click(function(){
	var base_url = window.location.origin;
	var url = base_url + "/~srank20/blog/rest/post.php/";
	//alert("delete not supported yet");
	   var sendInfo = {
	   postid: postid
   };
	var dataString = JSON.stringify(sendInfo);
	   $.ajax({
	   type: "DELETE",
	   url: url,
	   dataType: "json",
	   success: function (msg) {
		//var resp = JSON.parse(msg);
		alert(msg.results);	
		var base_url = window.location.origin;
		window.location.href = base_url + "/~srank20/blog/index.php";
	   },

	   data: dataString,
	  error: function(xhr, status, error) {
		alert("An AJAX error occured: " + status + "\nError: " + error);
	  }
   });
});

$("#submitcomment").click(function(){
	var base_url = window.location.origin;
	var url = base_url + "/~srank20/blog/rest/comment.php/";
	var commenttext2send = $("#commenttext").val();
	//alert(charname2send + universe2send + creator2send + voiceby2send + posttext2send);
	
   var sendInfo = {
	   commenttext: commenttext2send,
	   postid: postid
   };
	var dataString = JSON.stringify(sendInfo);
	//alert(dataString);
   $.ajax({
	   type: "POST",
	   url: url,
	   dataType: "json",
	   success: function (msg) {
		alert(msg.results);	
		location.reload(true);
	   },

	   data: dataString,
	  error: function(xhr, status, error) {
		alert("An AJAX error occured: " + status + "\nError: " + error);
	  }
   });
});

$("#update").click(function(){
	var base_url = window.location.origin;
	var url = base_url + "/~srank20/blog/rest/post.php/";
	var charname2send = $("#charname").val();
	var universe2send = $("#universe").val();
	var creator2send = $("#creator").val();
	var voiceby2send = $("#voiceby").val();
	var posttext2send = $("#posttext").val();
	//alert(charname2send + universe2send + creator2send + voiceby2send + posttext2send);
	
   var sendInfo = {
	   postid: postid,
	   charname: charname2send,
	   universe: universe2send,
	   creator: creator2send,
	   voiceby: voiceby2send,
	   posttext: posttext2send
   };
	var dataString = JSON.stringify(sendInfo);
	//alert(dataString);
   $.ajax({
	   type: "PUT",
	   url: url,
	   dataType: "json",
	   success: function (msg) {
		//var resp = JSON.parse(msg);
		alert(msg.results);	
	   },

	   data: dataString,
	  error: function(xhr, status, error) {
		alert("An AJAX error occured: " + status + "\nError: " + error);
	  }
   });
});
window.onload = function(){
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	var temppostid = urlParams.get('postid');
	postid = temppostid;
	var base_url = window.location.origin;
	var url = base_url + "/~srank20/blog/rest/post.php/?postid=" + postid;
   $.ajax({
	   type: "GET",
	   url: url,
	   //dataType: "json",
	   success: function (msg) {
			var resp = JSON.parse(msg);
			var HTML = "";
			//alert(resp.results);	
			$.each(resp, function(index, val){
				var extra = JSON.parse(val.extra);
				if(val.amItheowner == "yes"){
					IAmtheOwnerOfthePost = true;
				}
				if(IAmtheOwnerOfthePost) {
					$("#charname").val(extra.charname);
					$("#editusername").html(val.username);
					$("#editdate").html(val.post_date);
					$("#universe").val(extra.universe);
					$("#creator").val(extra.creator);
					$("#voiceby").val(extra.voiceby);
					$("#posttext").val(val.post_text);
					
				} else {
				HTML += "<h2>";
				HTML += extra.charname;
				HTML += "</h2>";
				HTML += '<h5><span class="glyphicon glyphicon-time"></span> Post by ';
				HTML += val.username;
				HTML += ", ";
				HTML += val.post_date;
				HTML += "<br>Universe: ";
				HTML += extra.universe;
				HTML += "<br>Creator: ";
				HTML += extra.creator;
				HTML += "<br>Voiced by: ";
				HTML += extra.voiceby;
				
				HTML += '</h5>';
				HTML += '<p>';
				HTML += val.post_text;
				HTML += '</p>';
				$("#editvalues").hide();
				}
			});
			$("#PostsHTML").html(HTML);
			updatecomments();
	   },
	  error: function(xhr, status, error) {
		alert("An AJAX error occured: " + status + "\nError: " + error);
	  }
   });
};

</script>
</body>
</html>
