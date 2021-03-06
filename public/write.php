<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $storyid=$_GET["storyid"];
    $storyName="My Three World";
    $nodeID=$_GET["nodeID"];
    setcookie("nodeID", $nodeID);
    setcookie("storyid",$storyid);
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

    <!-- Morris Charts CSS -->
    

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
                    <a href="author.html"><i class="fa fa-fw fa-bar-chart-o"></i> current User:<?php echo $_COOKIE["username"];?></a>
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
                        <?php echo  $storyName?> <small>Know more about this world</small>
                    </h1>

                </div>
            </div>
            <!-- /.row -->
            <form method="post" action="">  
            <div class="row">
                <div class="input-group">
                    <div class="input-group-btn">
                        <!-- Buttons -->
                        <button type="button" class="btn btn-default" aria-label="Bold">
                            <span class="glyphicon glyphicon-bold">
                            </span>
                        </button>
                        <button type="button" class="btn btn-default" aria-label="Italic">
                            <span class="glyphicon glyphicon-italic">
                            </span>
                        </button>
                    </div>

                    <input type="text" name="title" id="title" placeholder=" Node Title you would like to use "class="form-control" aria-label="...">
                </div>
            </div>

            <div class="row" style="padding-top: 10px;">
                <textarea name="content" id="content" rows="60" class="col-lg-12" placeholder=" Tell me about your world... "></textarea>
            </div>
            <div class="row" style="padding-top: 10px;">
                <button type="button" class="btn btn-primary " id="submit-button" aria-haspopup="true" aria-expanded="false">
                    Submit
                </button>
                <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false"> Back </button>
                <input type="hidden" name="storyID" value=<?php echo $storyid?> />
            </div>
            <!-- /.row -->
            </form>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Morris Charts JavaScript -->


<script>
    function getCookie(c_name)
    {
        if (document.cookie.length>0)
        {
            c_start=document.cookie.indexOf(c_name + "=")
            if (c_start!=-1)
            {
                c_start=c_start + c_name.length+1
                c_end=document.cookie.indexOf(";",c_start)
                if (c_end==-1) c_end=document.cookie.length
                return unescape(document.cookie.substring(c_start,c_end))
            }
        }
        return ""
    }
    function setCookie(c_name,value)
    {
        document.cookie=c_name+ "=" + value;
    }
   
    $("#submit-button").click(function(){
        var title=$("#title").val();
        var content=$("#content").val();
        console.log(content);
        var story=getCookie('storyid');
        var userID = getCookie('userID');
         alert("Succeed");
    window.location.href="userhome.php";
});
        // $.post('https://forest-novel.herokuapp.com/node',{
        //         developFrom: getCookie('nodeID'),
        //         nodeContent: content,
        //         nodeTitle:title,
        //         story: story,
        //         writer:'57109ca879bc44005f759c57'
        // });

        /*$.ajax({
            type: 'POST',
            url: 'https://forest-novel.herokuapp.com/node',
            data: {
                developFrom: getCookie('nodeID'),
                nodeContent: content,
                nodeTitle:title,
                story: story,
                writer:userID
            },
            dataType: 'json',
            success: function(responseData, textStatus, jqXHR){
                alert("success!");
                var url = "read.php?";
                console.log(responseData);
            },
            error: function (responseData, textStatus, errorThrown) {
                alert('POST failed.');
            }
        });

    });*/
</script>

</body>

</html>
