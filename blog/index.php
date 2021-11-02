
<?php include('MasterPages/lib.php');?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Video Game Characters Main Page</title>

<?php include('MasterPages/HeaderIncludes.php');?>
</head>
<body>

	<?php include('MasterPages/Header.php');?>
	
<div class="container-fluid">
  <div class="row content">

	<?php include('MasterPages/Menu.php');?>

    <div class="col-sm-9">
      <h4><small>RECENT POSTS</small></h4>
      <hr>
	  
	  <div id="PostsHTML"> </div>
    </div>
  </div>
</div>

<div><?php include('MasterPages/Footer.php');?></div>


<script>

window.onload = function(){
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	var tempusername = urlParams.get('username');
	var base_url = window.location.origin;
	var url = base_url + "/~srank20/blog/rest/post.php/?username=" + tempusername;
	if(tempusername === null){
		url = base_url + "/~srank20/blog/rest/post.php/";
	}
   $.ajax({
	   type: "GET",
	   url: url,
	   //dataType: "json",
	   success: function (msg) {
			var resp = JSON.parse(msg);
			var HTML = "";
			var base_url = window.location.origin;
			if(resp.length == 0) {
				HTML = "There are no posts to display";
			}
			$.each(resp, function(index, val){
				var extra = JSON.parse(val.extra);
				HTML += "<h2>";
				HTML += '<a href="' + base_url + "/~srank20/blog/SinglePost.php/?postid=" + val.postid + '">' + extra.charname + "</a>";
				HTML += "</h2>";
				HTML += '<h5><i class="fas fa-pencil-alt"></i> Post by ';
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
			});
			$("#PostsHTML").html(HTML);
	   },
	  error: function(xhr, status, error) {
		alert("An AJAX error occured: " + status + "\nError: " + error);
	  }
   });
};

</script>
</body>
</html>
