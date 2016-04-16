<?php
function getHtml($url){
	$handle = fopen($url,"rb");
$content = "";
while (!feof($handle)) {
    $content .= fread($handle, 10000);
}
fclose($handle);
$json = $content;
$obj = json_decode($json,true);//$obj is the node object
return $obj;
}

$UserId=$_SESSION["user_ID"];
$canLike=false;
$needLogin=false;

 if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $nodeID = $_GET["nodeid"];
  
  $obj=getHtml("http://10.89.116.121:3000/node/".$nodeID);//Get Node Object
 $nodeTitle=$obj["title"];
 $nodeContent=$obj["content"];
 
 $writer=getHtml("http://10.89.116.121:3000/userid/".$obj["writer"]["objectId"]);
 $likeUser=$obj["likeBy"];
 $likeNumber=count($likeUser);
 if(!is_nan($UserId)){
	 $canLike=true;
	foreach($user as $likeUser){
		if($user==$UserId){
			$canLike=false;
			break;	
		}
	}
 }else{
	 $needLogin=true;
 }
 
 $string_len=strlen($nodeContent);
 $wordPerPage=1200;
 $pageNum=(int)($string_len/ $wordPerPage+1);
 $createTime=$obj["createdAt"];
 
 $stringsplite=array();
 for($i=0;$i<$pageNum;$i++){
	//if($i==$pageNum-1){
		 $stringsplite[]=substr($nodeContent,$i*$wordPerPage,$wordPerPage);
	//}
 }
 
 
 
 $children=getHtml("http://10.89.116.121:3000/nodechild/".$nodeID);
 $likeNum=array();
 for($i=0;$i<count($children);$i++){
	$likeNum[]=count($children[$i]["likeBy"]);
 }

 $comments=getHtml("http://10.89.116.121:3000/commentbynodeid/".$nodeID);
 
 $commentNum=count($comments);
 
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://getbootstrap.com/favicon.ico">
  	<link href="css/main.css" rel="stylesheet">
    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
     

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="http://getbootstrap.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="http://getbootstrap.com/assets/js/ie-emulation-modes-warning.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
<script src="http://getbootstrap.com/assets/js/docs.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
    
      <div class="container-fluid">
      
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Forest</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Help</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
     
      <div class="row" style="background:#EEEEEE">
     
        <div class="col-sm-3 col-md-2 sidebar">
        <span style="color:#95AABC">Back to Parent</span>
         <div class="arrow">
        	
             <div class="arrow_button">
             <a id="left-arrow"><img src="images/arrow.png" width="20" class="arrow_img"/></a>
             </div>
             </div>
              
              <ul class="nav nav-sidebar">
                <li><a href="">Writer: <?php echo $writer["username"];?></a></li>
              </ul>
              
              <div class="nodeinfo">
              <img src="images/user.png"/ width="150px"><br><br>
              The writer <?php echo $writer["username"];?> is a very constractive writer<br><br>
              This node is created on <?php echo $createTime?>. It talks about something
              
              </div>
              <div class="like_part">
              <a href="#" class="myButton">Like <?php echo $likeNumber?></a>
              </div>
              <br>
              <br>
              <div>
              Full screen reading helps protect eyesight          
              </div>
            </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
         
         
         
         
        <div class="page-header">
        <h1 style="text-align:center"><?php echo $nodeTitle?></h1>
        <hr style="background:#000000"/>	
      </div>
      
      
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">
        <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
          
        <?php
		for($i=1;$i<$pageNum;$i++){
			echo "<li data-target='#carousel-example-generic' data-slide-to=$i></li>";
		}
		?>
         
        </ol>
        <div class="carousel-inner" role="listbox">
          <div class="item active">
          		<div class="innerText"><?php echo $stringsplite[0]?></div>
            <img data-src="holder.js/1140x600/auto/#F5EFDD:#8494AE/text: " alt="First slide">
            	
          </div>
          <?php
		  	for($i=1;$i<$pageNum;$i++){
				echo "<div class='item'>
          	<div class='innerText'>$stringsplite[$i]</div>
            <img data-src='holder.js/1140x600/auto/#F5EFDD:#8494AE/text: '>
           
          </div>";
			}
		  ?>   
        </div>
        
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div> <!-- /container -->
    
    
    
   
</div>
</div>
<div class="nav_area">
	  <div class="row" id="nav_bar">
        
        <div class="col-sm-4" id="nav_frame">
          <div class="panel panel-warning" id="nav_panel">
            <div class="panel-heading">
              <h3 class="panel-title">Next Node</h3>
            </div>
            <div class="panel-body">
              <div class="next_node">
          <ul class="nav nav-sidebar">
          <?php 
		  for($i=0;$i<count($children);$i++){
			  $title=$children[$i]["title"];
			  $nextID=$children[$i]["objectId"];
			  
			  $nextURL="writeFrame.php?nodeid=$nextID";
			 
			   echo " <li><a href=$nextURL>$title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='images/like.png' width='15' /> $likeNum[$i]</a></li>";
		  }
		 
		  ?>
           
          </ul>
            </div>
          </div>
         
        </div><!-- /.col-sm-4 -->
      </div>
 </div>
         
         

          <br>

          <h2 class="sub-header">Comments</h2>
          
          <div class="table-responsive">
            <table class="table table-striped">
              
              <tbody>
                <tr>
                  <td>1,001</td>
                  <td><a href="">Lorem</a></td>
                   <td class="comment">Hello Hello Good Job</td>
                </tr>
                 <tr>
                  <td>1,001</td>
                  <td><a href="">Joy</a></td>
                   <td class="comment">Not Bad, I like it</td>
                </tr>
               
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
   

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  
    
    <script>
	/*$("#left-arrow").click(function(){
		$(".sidebar").toggle(1000);
	});*/
	
	
	</script>
  </body>
</html>
