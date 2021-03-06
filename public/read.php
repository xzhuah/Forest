<!--This page need to valuable in get request to initialize, nodeid and story.-->
<!--http://10.89.116.121:8080/hackUST/Forest/public/read.php?nodeid=5710c6d771cfe4005b0c2473&title=Hello&storytitle=Three%20Body-->
<?php
//helper function to get json object from a url
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

$canLike=false;
$_SESSION["userid"]="57109ca879bc44005f759c57";


 if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $nodeID = $_GET["nodeid"];                                                      //$nodeID->     Current Node ID
   
    $obj=getHtml("https://forest-novel.herokuapp.com/node/".$nodeID); 
     $obj= $obj["node"];                                                              //$obj->        The Node Object
    $storyid=$obj["story"]["objectId"];
    $story=getHtml("https://forest-novel.herokuapp.com/story/".$storyid);
    $story=$story["story"];
    
    $storyTitle=$story["title"];               
    $nodeTitle=$obj["title"];                                                       //$nodeTitle->  Node title
    $nodeContent=$obj["content"];                                                   //$nodeContent->Node Content
    $createTime=$obj["createdAt"];                                                 //$createTime-> Create time of the node 
    $writer=getHtml("https://forest-novel.herokuapp.com/userid/".$obj["writer"]["objectId"]);//$writer->     Writer Object
    $likeUser=$obj["likeBy"];                                                       //$likeUser->   All liked users
    $likeNumber=count($likeUser);                                                   //$likeNumber-> Like number
    $string_len=strlen($nodeContent);                                               //$string_len-> Content length
    $wordPerPage=1200;                                                              //$wordPerPage->Word number per page
    $pageNum=(int)($string_len/ $wordPerPage+1);                                    //$pageNum->    Page number we need to create 
                                                   
    $stringsplite=array();
    for($i=0;$i<$pageNum;$i++){
    	 $stringsplite[]=substr($nodeContent,$i*$wordPerPage,$wordPerPage);         //$stringsplite-> String on each page
    }
    $children=getHtml("https://forest-novel.herokuapp.com/nodechild/".$nodeID);              //$children->   all children of the current node
    $likeNum=array();
    for($i=0;$i<count($children);$i++){
    $likeNum[]=count($children[$i]["likeBy"]);                                      //$likeNum->    like number of 
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
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Forest</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

 

    <!-- Custom Fonts -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="index.html">Forest</a>

        </div>

        <!-- Top-items -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="height: 60px;">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="Homepage.html"><span class="glyphicon glyphicon-tree-conifer" style="color:#21D176"></span><span style = "font-size:80%;">Forest</span></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                         <li>
                            <a href=<?php echo "write.php?storyid=".$story["objectId"]. "&nodeID=".$obj["objectId"];?>> <strong>+ Create</strong></a>
                        </li>
                        <li>
                            <a href="#">Sign Out</a>
                        </li>
                        <li>
                            <a href="#">NovelMap</a>
                        </li>
                        <li>
                            <a href="#">Guide</a>
                        </li>
                        <li>
                            <a href="#">About</a>
                        </li>
                        <li>
                            <a href="#">Notifications</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="active">
                    <a href="map.php?id=<?php echo $storyid ?>"><i class="fa fa-fw fa-dashboard"></i>Return Story</a>
                </li>
                <li>
                    <a href="author.html"><i class="fa fa-fw fa-bar-chart-o"></i> Author:<?php echo $writer["username"];?></a>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-clipboard"></i> Post ! <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo" class="collapse">
                        <li>
                            <a href="#">Post from current</a>
                        </li>
                        <li>
                            <a href="#">Post from parent</a>
                        </li>
                    </ul>
                </li>
                <!--<li>-->
                <!--<a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>-->
                <!--</li>-->
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                       <?php echo $storyTitle; ?><small> Know more about this world</small>
                    </h1>
                    <!--<ol class="breadcrumb">-->
                        <!--<li class="active">-->
                            <!--<i class="fa fa-dashboard"></i> Dashboard-->
                        <!--</li>-->
                    <!--</ol>-->
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-info-circle"></i>  <strong>Like this post?</strong> Follow the author <a href="#" class="alert-link"><?php echo $writer["username"];?></a> for more stories!
                    </div>
                </div>
            </div>
            <!-- /.row -->


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="glyphicon glyphicon-star-empty"></i><?php echo $nodeTitle?></h3>
                        </div>
                        <div class="panel-body" id="carousel-high">
                            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                    <?php 
                                        for($i=1;$i<count($stringsplite);$i++){
                                            echo " <li data-target='#myCarousel' data-slide-to=$i></li>";
                                        }
                                    ?>
                                </ol>

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner" role="listbox">
                                    <div class="item active"><span style='padding-left: 5px'>Page0</span>
                                        <div class="innerText"><?php echo$stringsplite[0]?></div>
                                        <img data-src="holder.js/1140x600/auto/#F5EFDD:#8494AE/text: " alt=" ">

                                    </div>

                                   <?php
                                        for($i=1;$i<count($stringsplite);$i++){
                                            echo "<div class='item'><span style='padding-left: 5px'>Page$i</span>
                                        <div class='innerText'>$stringsplite[$i]</div>
                                        <img data-src='holder.js/1140x600/auto/#F5EFDD:#8494AE/text:'>

                                    </div>";
                                        }
                                   ?>

                                </div>

                                <!-- Left and right controls -->
                                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->


            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="comdiv"><?php echo $commentNum; ?></div>
                                    <div>New Comments!</div>
                                </div>
                            </div>
                        </div>
                        <a onclick="document.getElementById('comment').style.display='block'">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo count($children); ?></div>
                                    <div>New Child Node!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                            <a  class="like_buttom" onclick="like();">
                                            <div class="col-xs-3">
                                                <i class="fa fa-thumbs-o-up fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $likeNumber ;?></div>
                                                <div> Like!</div>
                                            </div>
                             </a>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-9">
                                    <div> Next Node </div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">

                            <?php 
                            for($i=0;$i<count($children);$i++){
                                    $title=$children[$i]["title"];
                                    $nextID=$children[$i]["objectId"];
                                    $nextURL="read.php?nodeid=$nextID";

                                echo "<span class='pull-left'> $title </span>
                                <a href=$nextURL><span class='pull-right'>Go! <i class='fa fa-arrow-circle-right'></i></span></a>
                                <div class='clearfix'></div>";
                            }
                            ?>
                             
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->



        </div>

        <div>
         <input type="text" id="usercomment" name="title" placeholder=" Add your comments " class="form-control" aria-label="..." /><br>
         <center><input type="submit" value="Add New Comment" onclick="comment();" /><center><br>
         </div>

        <!-- /.container-fluid -->
       <div id="comment" style="display:none;padding-left: 15px">
       <?php 
       for($i=0;$i<$commentNum;$i++){
        $comm= $comments[$i]["text"];
        $needURL="https://forest-novel.herokuapp.com/userid/".$comments[$i]["userID"]["objectId"];
      
        $userN=getHtml($needURL);
        $userN=$userN["username"];
        echo "<div class='panel panel-yellow' style='height:80px'>
                <div class='panel-heading'>
                    <div class='row'>
                        <div class='col-xs-9'>
                            <div> $userN </div>
                        </div>
                 </div>
              </div>
              <div style='padding-top:5px;padding-left: 5px; font-weight: 18px'>
              $comm
            </div>

           </div>";
       }
       ?>
            
    </div>
    <!-- /#page-wrapper -->

</div>

<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Morris Charts JavaScript -->
<script src="js/jquery.min.js"></script>

<script>
function comment(){
    var cont=document.getElementById("usercomment").value;
    if(cont!=""){
        var temp=$.post("https://forest-novel.herokuapp.com/comment/<?php echo $nodeID; ?>/<?php echo $_SESSION["userid"];?>",{commentContent:cont});
        console.log(temp);
        document.getElementById("comdiv").innerText=(parseInt(document.getElementById("comdiv").innerText)+1);
        document.getElementById("usercomment").value="";
    }
    }
function like(){
 
  var temp= $.get("https://forest-novel.herokuapp.com/userlike/<?php echo $_SESSION["userid"];?>/<?php echo $nodeID ?>");

   console.log(temp);

   document.getElementById("likeNUM").innerText=(parseInt(<?php echo $likeNumber ?>)+1);
}


</script>

</body>

</html>
