<!--This page need to valuable in get request to initialize, nodeid and story.-->

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


 if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $nodeID = $_GET["nodeid"];                                                      //$nodeID->     Current Node ID
    $storyTitle=$_GET["storytitle"];                                                //$storyTitle-> Story title
    $obj=getHtml("http://10.89.116.121:3000/node/".$nodeID); 
     $obj= $obj["node"];                                                              //$obj->        The Node Object
    $storyid=$obj["story"]["objectId"];
                           
    $nodeTitle=$obj["title"];                                                       //$nodeTitle->  Node title
    $nodeContent=$obj["content"];                                                   //$nodeContent->Node Content
    $createTime=$obj["createdAt"];                                                 //$createTime-> Create time of the node 
    $writer=getHtml("http://10.89.116.121:3000/userid/".$obj["writer"]["objectId"]);//$writer->     Writer Object
    $likeUser=$obj["likeBy"];                                                       //$likeUser->   All liked users
    $likeNumber=count($likeUser);                                                   //$likeNumber-> Like number
    $string_len=strlen($nodeContent);                                               //$string_len-> Content length
    $wordPerPage=1200;                                                              //$wordPerPage->Word number per page
    $pageNum=(int)($string_len/ $wordPerPage+1);                                    //$pageNum->    Page number we need to create 
                                                   
    $stringsplite=array();
    for($i=0;$i<$pageNum;$i++){
    	 $stringsplite[]=substr($nodeContent,$i*$wordPerPage,$wordPerPage);         //$stringsplite-> String on each page
    }
    $children=getHtml("http://10.89.116.121:3000/nodechild/".$nodeID);              //$children->   all children of the current node
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

    <title>SB Admin - Bootstrap Admin Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/morris.css" rel="stylesheet">

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
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                <ul class="dropdown-menu message-dropdown">
                    <li class="message-preview">
                        <a href="#">
                            <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                <div class="media-body">
                                    <h5 class="media-heading"><strong>John Smith</strong>
                                    </h5>
                                    <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="message-preview">
                        <a href="#">
                            <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                <div class="media-body">
                                    <h5 class="media-heading"><strong>John Smith</strong>
                                    </h5>
                                    <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="message-preview">
                        <a href="#">
                            <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                <div class="media-body">
                                    <h5 class="media-heading"><strong>John Smith</strong>
                                    </h5>
                                    <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="message-footer">
                        <a href="#">Read All New Messages</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                <ul class="dropdown-menu alert-dropdown">
                    <li>
                        <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                    </li>
                    <li>
                        <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                    </li>
                    <li>
                        <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                    </li>
                    <li>
                        <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                    </li>
                    <li>
                        <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                    </li>
                    <li>
                        <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="#">View All</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>
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
                                    <div class="huge"><?php echo $commentNum; ?></div>
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
                            <a href="#" class="like_buttom" onclick="like();">
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
        <!-- /.container-fluid -->
       <div id="comment" style="display:none";padding-left: 15px">
       <?php 
       for($i=0;$i<$commentNum;$i++){
        $comm= $comments[$i]["text"];
        $needURL="http://10.89.116.121:3000/userid/".$comments[$i]["userID"]["objectId"];
      
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
<script src="js/plugins/morris/raphael.min.js"></script>
<script src="js/morris.min.js"></script>
<script src="js/plugins/morris/morris-data.js"></script>
<script>
function like(){
   

}

</script>

</body>

</html>
