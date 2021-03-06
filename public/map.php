<?php
$_SESSION["userid"]="5712a0b38ac2470064630388";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $storyID = $_GET['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Map</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/map.css" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript">
        var storyId = <?php echo "'".$storyID."'"?>;
        var loggedIn=true;
        
        var curUserId = <?php echo "'".$_SESSION['userid']."'"?>;
        
    </script>
</head>
<body>

<!-- Header -->
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
<!-- Page Content -->
<div class="container" style="padding-top: 90px; position: relative;">

    <!-- Content Row -->
    <div class="row">
        <!-- Content Column -->
        <div class="col-md-9">
            <svg id="map">
                <!-- map of nodes display -->
            </svg>
        </div>
        <div class="col-md-3">
            <div id="node-info">
                <!-- side bar display information of node selected -->
            </div>
        </div>
    </div>


    <!-- /.row -->

    <!-- Footer -->

    <script src="js/jquery.min.js"></script>
    <script src="js/d3.v3.min.js"></script>
    <script src="js/d3.tip.v0.6.3.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/map.js"></script>

</div>

</body>
</html>
