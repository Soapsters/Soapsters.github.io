
<?php include('MasterPages/lib.php');?>
 
 <?php 
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Video Game Characters Create Post Page</title>

<?php include('MasterPages/HeaderIncludes.php');?>
</head>
<body>

	<?php include('MasterPages/Header.php');?>
<div class="container-fluid">
  <div class="row content">

	<?php include('MasterPages/Menu.php');?>

    <div class="col-sm-9">
      <h4><small>CREATE POST</small></h4>
      <hr>
      <form role="form">
        <div class="form-group">
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
        </div>
		<button id="submit" type="button" class="btn btn-default">Submit</button>
		<button id="clear" type="button" class="btn btn-default">Clear Values</button>
      </form>
              <br>
            </div>
          </div>
<div><?php include('MasterPages/Footer.php');?></div>


<script>

$("#submit").click(function(){
	var charname2send = $("#charname").val();
	var universe2send = $("#universe").val();
	var creator2send = $("#creator").val();
	var voiceby2send = $("#voiceby").val();
	var posttext2send = $("#posttext").val();
	//alert(charname2send + universe2send + creator2send + voiceby2send + posttext2send);
	
   var sendInfo = {
	   charname: charname2send,
	   universe: universe2send,
	   creator: creator2send,
	   voiceby: voiceby2send,
	   posttext: posttext2send
   };
	var dataString = JSON.stringify(sendInfo);
	//alert(dataString);
   $.ajax({
	   type: "POST",
	   url: "rest/post.php",
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

$("#clear").click(function(){
	$("#charname").val("");
	$("#universe").val("");
	$("#creator").val("");
	$("#voiceby").val("");
	$("#posttext").val("");
});
</script>


</body>
</html>