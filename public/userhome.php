<?php
//$handle = fopen("http://10.89.116.121:3000/user/cbai","rb");
$handle = fopen("https://forest-novel.herokuapp.com/user/cbai","rb");
$content = "";
while (!feof($handle)) {
    $content .= fread($handle, 10000);
}
fclose($handle);
$json = $content;
$obj = json_decode($json,true);
$follower=$obj["user"][0]['follower'];
$followee=$obj["user"][0]['followee'];
$NumOfFlollower=sizeof($follower);
$NumOfFlollowee=sizeof($followee);
//-- story request
$storyURL="https://forest-novel.herokuapp.com/storybyuser/".$obj["user"][0]['objectId'];
$story = fopen($storyURL,"rb");
$storyContent="";
while (!feof($story)) {
    $storyContent .= fread($story, 10000);
}
$storyArray = json_decode($storyContent,true);
//-- top rated request
$topRatedURL="https://forest-novel.herokuapp.com/beststory/1";
$topRated= fopen($topRatedURL,"rb");
$topRatedContent="";
while (!feof($topRated)) {
    $topRatedContent .= fread($topRated, 10000);
}
$topREatedArray = json_decode($topRatedContent,true);
$counter=0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Custom CSS -->
    <!--<link href="css/modern-business.css" rel="stylesheet">-->

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <style>
        pre {
            border: 1px grey dotted;
            padding: 1em;
        }
        #bs-example-navbar-collapse-1{
            position: relative;
            top:5px;
        }
        .collapse li a{
            font-size: 120%;
            font-family: Arial;
        }
        .homepage-hero-module {
            border-right: none;
            border-left: none;
            position: relative;
        }
        .no-video .video-container video,
        .touch .video-container video {
            display: none;
        }
        .no-video .video-container .poster,
        .touch .video-container .poster {
            display: block !important;
        }
        .video-container {
            position: relative;
            bottom: 0%;
            left: 0%;
            height: 100%;
            width: 100%;
            overflow: hidden;
            background: #000;
        }
        .video-container .poster img {
            width: 100%;
            bottom: 0;
            position: absolute;
        }
        .video-container .filter {
            z-index: 100;
            position: absolute;
            background: rgba(0, 0, 0, 0.4);
            width: 100%;
        }
        .video-container video {
            position: fixed;
            z-index: 0;
            bottom: 0;
        }
        .video-container video.fillWidth {
           width: 100%;
        }
    </style>
</head>

<body >
    <!-- Navigation -->
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
    <div class="homepage-hero-module" style="position: fixed;">
        <div class="video-container">
            <video autoplay loop class="fillWidth" style="opacity: 0.8;">
                <source src="MP4/Up.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.
                <source src="WEBM/Up.webm" type="video/webm" />Your browser does not support the video tag. I suggest you upgrade your browser.
            </video>
            <div class="poster hidden">
                <img src="Snapshots/Up.jpg" alt="">
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container" style="position:relative;top:100px;">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row" >
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="index.html">Home</a>
                    </li>
                    <li class="active">Personal home</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object img-circle" src="images/user.png" alt="..." style="height: 80px;width:80px;">
                        </a>
                    </div>
                    <!-- First Blog Post -->
                    <div class="media-body">
                    <h2>
                       <?php echo $obj["user"][0]['username']." ";?>
                        <button type="button" class="btn btn-danger">Follower: <?php echo $NumOfFlollower ?> </button>
                        <button type="button" class="btn btn-success">Followee:  <?php echo $NumOfFlollowee ?></button>
                    </h2>
                    <p><i class="fa fa-clock-o"></i> <?php echo "last updated at: ".$obj["user"][0]['lastUpdate']['iso'];?></p>
                    </div>
                    <hr>
                </div>

                <!-- Second Blog Post -->
                <h2>
                    What you have followed
                </h2>
                <p><i class="fa fa-clock-o"></i><?php  echo " Last updated at ".date("Y/m/d");?></p>
                <hr>
                <div class="row;">
                    <?php
                    foreach($storyArray["story"] as $row):
                    $NumOfFlollowerOfStory=sizeof($row["followUser"]);
                    ?>
                    <div class="col-md-12">
                        <div class="panel panel-default " >
                            <div class="panel-heading">
                                <h3 style="top: 10px;position: relative;">
                                   <a href="#"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> <?php echo $row["title"] ?></a>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="30%" class="tap">Theme</th>
                                        <th width="70%"><?php echo  $storyArray["themeNames"][$counter]?></th>
                                    </tr>
                                    <tr>
                                        <th width="30%">Creator</th>
                                        <th width="70%"><?php echo  $storyArray["creators"][$counter]["username"]?></th>
                                    </tr>
                                    <tr>
                                        <th width="30%">Created date</th>
                                        <th width="70%"><?php echo $row["createdAt"] ?></th>
                                    </tr>
                                    <tr>
                                        <th width="30%">last update</th>
                                        <th width="70%"><?php echo $row["updatedAt"] ?></th>
                                    </tr>
                                    <tr>
                                        <th width="30%">current contributor</th>
                                        <th width="70%"><?php echo $NumOfFlollowerOfStory?></th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                        <?php
                        $counter++;
                    endforeach;
                    ?>
                </div>
                <hr>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quibusdam, quasi, fugiat, asperiores harum voluptatum tenetur a possimus nesciunt quod accusamus saepe tempora ipsam distinctio minima dolorum perferendis labore impedit voluptates!</p>
                <a class="btn btn-primary" href="#">Read More <i class="fa fa-angle-right"></i></a>

                <hr>

                <!-- Pager -->
                <ul class="pager">
                    <li class="previous">
                        <a href="#">&larr; Older</a>
                    </li>
                    <li class="next">
                        <a href="#">Newer &rarr;</a>
                    </li>
                </ul>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">
                <!-- Blog Search Well -->
                <div class="well">
                    <h4><strong>Novel Search</strong></h4>
                    <div class="input-group" style="position: relative;top:10px;">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search">Search</i></button>
                        </span>
                    </div>
                    <!-- /.input-group -->
                </div>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                    <!-- /.row -->
                </div>
                <div class="row" style="position: relative;top: -20px;">
                    <div class="col-lg-12">
                        <h2 class="page-header">Recommandations</h2>
                    </div>
                    <?php
                    $counter=0;
                    foreach($topREatedArray["bestStory"] as $topTateRow): ?>
                    <div class="col-md-12 text-center">
                        <div class="thumbnail">
                            <div class="caption">
                                <h3> <?php echo $topTateRow["title"] ?><br>
                                    <small><?php echo "--- by ".$topREatedArray["creators"][0]["username"]?></small>
                                </h3>
                                <p> <?php echo $topTateRow["introduction"] ?></p>
                                <ul class="list-inline">
                                    <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-2x fa-twitter-square"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
                    $content++;
                    endforeach;?>
                </div>
            </div>
        </div>
        <!-- /.row -->


        <!-- Footer -->

    </div>
    <!-- /.container -->
    <div class="footer" style="position:relative; top:100px;">
        <div class="container">
            <div class="social">
                <ul>
                    <li><a href="#" class="face"></a></li>
                    <li><a href="#" class="twit"></a></li>
                    <li><a href="#" class="insta"></a></li>
                    <li><a href="#" class="gplus"></a></li>
                    <li><a href="#" class="dribl"></a></li>
                </ul>
            </div>
            <div class="copy-rt">
                <p style="font-family: 'Book Antiqua' ;">Copyright &copy;2016.Forest. All rights reserved.</p>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        //jQuery is required to run this code
        $( document ).ready(function() {

            scaleVideoContainer();

            initBannerVideoSize('.video-container .poster img');
            initBannerVideoSize('.video-container .filter');
            initBannerVideoSize('.video-container video');

            $(window).on('resize', function() {
                scaleVideoContainer();
                scaleBannerVideoSize('.video-container .poster img');
                scaleBannerVideoSize('.video-container .filter');
                scaleBannerVideoSize('.video-container video');
            });

        });

        function scaleVideoContainer() {

            var height = $(window).height() + 5;
            var unitHeight = parseInt(height) + 'px';
            $('.homepage-hero-module').css('height',unitHeight);

        }

        function initBannerVideoSize(element){

            $(element).each(function(){
                $(this).data('height', $(this).height());
                $(this).data('width', $(this).width());
            });

            scaleBannerVideoSize(element);

        }

        function scaleBannerVideoSize(element){

            var windowWidth = $(window).width(),
                    windowHeight = $(window).height() + 5,
                    videoWidth,
                    videoHeight;

            console.log(windowHeight);

            $(element).each(function(){
                var videoAspectRatio = $(this).data('height')/$(this).data('width');

                $(this).width(windowWidth);

                if(windowWidth < 1000){
                    videoHeight = windowHeight;
                    videoWidth = videoHeight / videoAspectRatio;
                    $(this).css({'margin-top' : 0, 'margin-left' : -(videoWidth - windowWidth) / 2 + 'px'});

                    $(this).width(videoWidth).height(videoHeight);
                }

                $('.homepage-hero-module .video-container video').addClass('fadeIn animated');

            });
        }
    </script>

</body>

</html>
