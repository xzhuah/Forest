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
$_SESSION["userid"]="57109ca879bc44005f759c57";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $storyid=$_GET["id"];
    $themename=$_GET["name"];
    $intro=$_GET["intro"];
    $url="http://10.89.116.121:3000/storybythemeid/".$storyid;
    $Stories=getHtml($url);
  
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

    <title>Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="css/heroic-features.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Theme Page</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron hero-spacer">
        <div class="titleback">
            <h1><?php echo $themename;?></h1>
            <p><?php echo $intro;?></p>
         </div>
          
           
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Stories</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
      
        <div class="row text-center">
        <?php
        for($i=0;$i<count($Stories);$i++){
            $title=$Stories[$i]['title'];
            $storyiid=$Stories[$i]['objectId'];
            $introd=$Stories[$i]['introduction'];
            $num=$i%4;
            $pic="images/pic$num.jpg";
            echo " <div class='col-md-3 col-sm-6 hero-feature'>
                <div class='thumbnail'>
                    <img src=$pic >
                    <div class='caption'>
                     <a href='map.php?id=$storyiid' class='btn btn-primary'>Enter Now!</a>
                        <h3>$title</h3>
                        <p>$introd</p>
                       
                           
                       
                    </div>
                </div>
            </div>";
        }

        ?>

        </div>
        <!-- /.row -->

        <hr>
       
        <center><h2 style="color:#fff">Add a new Story</h2></center>
        <input type="text" name="title" placeholder=" Story Title you would like to add " class="form-control" aria-label="..." id="storytitle"/><br>
        <input type="text" name="intro" placeholder=" Some short introduction " class="form-control" aria-label="..." id="storyinfo"/><br>
        <center><input type="submit" value="Add New Story" onclick="addStory();" /></center>
        

         <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12"  style="color:#fff">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        
        function addStory(){
            
            var stitle=document.getElementById("storytitle").value;
            var sintro=document.getElementById("storyinfo").value;
            var sid="<?php echo $storyid;?>";
            var cid="<?php echo $_SESSION['userid'];?>";

            if(stitle!=""&&sintro!=""){
              var res=  $.post("https://forest-novel.herokuapp.com/story",{creator:cid,storyTitle:stitle,theme:sid,intro:sintro});
              console.log(res);
            }
            document.getElementById("storytitle").value="";
            document.getElementById("storyinfo").value="";
           
        }
    </script>

</body>

</html>
