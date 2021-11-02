
	
	
	<div class="col-sm-3 sidenav">
	    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>

      <ul class="nav nav-pills nav-stacked">
        <li><a href="/~srank20/blog/index.php"><i class="fas fa-home"></i> Home</a></li>
		<li><a href="/~srank20/blog/index.php/?username=<?php echo($_SESSION["username"]); ?>"><i class="fas fa-pencil-alt"></i> My Posts</a></li>
		<li><a href="/~srank20/blog/create_post.php"><i class="fas fa-edit"></i></i> Create Post</a></li>
        <li><a href="/~srank20/blog/login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul><br>
      <div class="input-group">
        <input id="searchusername" type="text" class="form-control" placeholder="Search Blog by Username..">
        <span class="input-group-btn">
          <button id="searchbutton" class="btn btn-default" type="button">
			<i class="fas fa-search"></i>
          </button>
        </span>
      </div>
    </div>
	
	
	<script>
	$("#searchbutton").click(function(){
		var base_url = window.location.origin;
		var username = $('#searchusername').val();
		window.location.href = base_url + "/~srank20/blog/index.php/?username=" + username;
	});
	</script>