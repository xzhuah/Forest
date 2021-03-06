'use strict';
var domain = require('domain');
var express = require('express');
var path = require('path');
var bodyParser = require('body-parser');
var todos = require('./routes/todos');
var cloud = require('./cloud');
var AV = require('leanengine');
var request = require("request");
var async = require('async');
var cors = require('cors');
var app = express();
var Comment = AV.Object.extend('Comment');
var Node = AV.Object.extend('Node');
var Story = AV.Object.extend('Story');

// 设置 view 引擎
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');
app.use(express.static('public'));

// 加载云代码方法
app.use(cloud);

// 使用 LeanEngine 中间件
// （如果没有加载云代码方法请使用此方法，否则会导致部署失败，详细请阅读 LeanEngine 文档。）
// app.use(AV.Cloud);

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
app.use(cors());

app.all('/*', function(req, res, next) {
  res.header("Access-Control-Allow-Origin", "*");
  res.header("Access-Control-Allow-Headers", "X-Requested-With");
  next();
});

app.use(function(req, res, next) {
    var oneof = false;
    if(req.headers.origin) {
        res.header('Access-Control-Allow-Origin', req.headers.origin);
        oneof = true;
    }
    if(req.headers['access-control-request-method']) {
        res.header('Access-Control-Allow-Methods', req.headers['access-control-request-method']);
        oneof = true;
    }
    if(req.headers['access-control-request-headers']) {
        res.header('Access-Control-Allow-Headers', req.headers['access-control-request-headers']);
        oneof = true;
    }
    if(oneof) {
        res.header('Access-Control-Max-Age', 60 * 60 * 24 * 365);
    }

    // intercept OPTIONS method
    if (oneof && req.method == 'OPTIONS') {
        res.send(200);
    }
    else {
        next();
    }
});
// 未处理异常捕获 middleware
app.use(function(req, res, next) {
  var d = null;
  if (process.domain) {
    d = process.domain;
  } else {
    d = domain.create();
  }
  d.add(req);
  d.add(res);
  d.on('error', function(err) {
    console.error('uncaughtException url=%s, msg=%s', req.url, err.stack || err.message || err);
    if(!res.finished) {
      res.statusCode = 500;
      res.setHeader('content-type', 'application/json; charset=UTF-8');
      res.end('uncaughtException');
    }
  });
  d.run(next);
});

app.get('/', function(req, res) {
  res.sendFile('index.html', { root: path.join(__dirname, '../public') });
});

//////////////////////////////////Our Functions Start here/////////////////////////////////////
//get user by userID
app.get('/userid/:userid',function(req,res){//OK 2016/4/16
  var userID=req.params.userid;
   var findUserById=new AV.Query(AV.User);
  findUserById.get(userID).then(function(obj){
    res.json(obj);
  },function(error){
     res.json({success: false});
  });
});

// get uer by usernpame
app.get('/user/:username', function(req, res) {//OK 2016/4/16
  var username = req.params.username;
  var userQuery = new AV.Query(AV.User);//choose table
  userQuery.equalTo('username', username);//Condition
  userQuery.find().then(function(user) {//quert
    //found

    res.json({success: true, user: user});
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
});

// get uer by email
app.get('/user_email/:theemail', function(req, res) {//OK 2016/4/16
  var theemail = req.params.theemail;
  var EmailuserQuery = new AV.Query(AV.User);//choose table
  EmailuserQuery.equalTo('email', theemail);//Condition
  EmailuserQuery.find().then(function(user) {//quert
    //found
    res.json({success: true, user: user});
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
});



//Return comments by a nodeid
app.get('/commentbynodeid/:nodeid',function(req,res){//OK 2016/4/16
    var commentbynodeid=req.params.nodeid;
    var getNodeFirst = new AV.Query("Node");
    getNodeFirst.get(commentbynodeid).then(function(obj){
        var findCommentByNodeID=new AV.Query("Comment");
        findCommentByNodeID.equalTo('nodeID',obj);
        findCommentByNodeID.find().then(function(comments) {//quert
        //found
        res.json(comments);
        }).catch(function(error) {
        //failed
        res.json({success: false});
        });
      },function(err){
      res.json({success: false})
    });
});


//get node by id
app.get('/node/:nodeid2',function(req,res){//OK 2016/4/16
  var querynodeid=req.params.nodeid2;
  var findNodeByNodeID=new AV.Query('Node');
  findNodeByNodeID.get(querynodeid).then(function(obj){
    res.json({success: true, node: obj});
  },function(error){
     res.json({success: false});
  });
});

//get node by parent node id
app.get("/nodechild/:parentid",function(req,res){//OK 2016/4/16

  var queryparentid=req.params.parentid;
   var findNodeByParent=new AV.Query('Node');
   findNodeByParent.get(queryparentid).then(function(objs){

        var findNodeByParentID=new AV.Query("Node");
  findNodeByParentID.equalTo("developFrom",objs);
  findNodeByParentID.find().then(function(objjs) {//quert
    //found
    res.json(objjs);
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
   },function(error){
     res.json({success: false});
  });



});


app.get('/story/:storyId', function(req, res) {//OK 2016/4/16
  var storyId = req.params.storyId;
  var storyQuery = new AV.Query('Story');
  storyQuery.get(storyId).then(function(story) {
    res.json({success: true, story: story});
  }).catch(function(error) {
    res.json({success: false, error: error});
  });
});

//get all themes
app.get('/theme',function(req,res){//OK 2016/4/16
   var findAllTheme=new AV.Query('Theme');
   findAllTheme.find().then(function(obj){
    res.json({success: true, theme: obj});
   },function(error){
     res.json({success: false, error: error});
   });

});

//Get story by theme
app.get('/storybythemeid/:themeid',function(req, res){//OK 2016/4/16
  var querystorybytheme=req.params.themeid;
  var findthemefirst = new AV.Query('Theme');
  findthemefirst.get(querystorybytheme).then(function(obj){
      var findStoryBythemeID=new AV.Query("Story");
      findStoryBythemeID.equalTo('theme',obj);
      findStoryBythemeID.find().then(function(oobj){//quert
      //found
      res.json(oobj);
    },function(error){
    res.json({success: false});
  });
  },function(error){
  res.json({success: false});
});
});





//find nodes by story id
app.get('/nodebystoryid/:storyid',function(req,res){//OK 2016/4/16
var querynodebystory=req.params.storyid;
var findStoryFirst=new AV.Query('Story');
findStoryFirst.get(querynodebystory).then(function(obj){
  var findNodeByStoryID=new AV.Query('Node');
  findNodeByStoryID.equalTo('story',obj);

  findNodeByStoryID.find().then(function(obj) {//quert
  //found
  res.json(obj);
}).catch(function(error) {
  //failed
  res.json({success: false});
});

},function(error){
  res.json({success: false});
});

});


//search story by userid
app.get('/storybyuser/:userid',function(req,res){//OK 2016/4/16
  var querystoryidbyuser=req.params.userid;
  var findStoryIdByUserID=new AV.Query(AV.User);
  var innerQuery = new AV.Query('Story');
  innerQuery.include('theme');
  innerQuery.include('creator');
  var stories = [];
  var themeNames = [];
  var creators = [];
  findStoryIdByUserID.get(querystoryidbyuser).then(function(obj){
    innerQuery.find().then(function(results) {
      results.map(function(result) {
        if (result.get('followUser') != undefined && result.get('followUser').indexOf(querystoryidbyuser) > -1) {
          themeNames.push(result.get('theme').get('name'));
          creators.push({
            id: result.get('creator').id,
            username: result.get('creator').get('username')
          });
          stories.push(result);
        }
      });
      res.json({success: true, story: stories, themeNames: themeNames, creators: creators});
    });
  },function(error){
     res.json({success: false, error: error});
  });
});


//Search Story by likenumber ranking
app.get('/beststory/:topnum',function(req,res){//OK 2016/4/16
  var topnum = req.params.topnum;
  var findStorybylikerank = new AV.Query('Story');
  var creators = [];
  findStorybylikerank.include('creator');
  findStorybylikerank.find().then(function(results) {
    results.sort(function(x, y) {
      if (x.get('followUser') != undefined && y.get('followUser') != undefined && x.get('followUser').length > y.get('followUser').length) {
        return -1;
      }
      if (x.get('followUser') != undefined && y.get('followUser') != undefined && x.get('followUser').length < y.get('followUser').length) {
        return 1;
      }
      return 0;
    });
    if (topnum > results.length) {
      res.json({success: false, error: "topnum too large"});
    }
    var bestStory = results.slice(0, topnum);
    bestStory.map(function(story) {
      creators.push(
        {
          id: story.get('creator').id,
          username: story.get('creator').get('username')
        }
    );
    });
    res.json({success: true, bestStory: bestStory, creators: creators});
  }).catch(function(error) {
    res.json({success: false, error: error});
  });
});

app.post('/signup', function(req, res) {
  var email = req.body.email;
  var username = req.body.username;
  var password = req.body.password;
  var user = new AV.User();
  user.set('username', username);
  user.set('password', password);
  user.set('email', email);
  user.signUp().then(function(user) {
    // 注册成功，可以使用了
    res.json({success: true, user: user});
  }, function(error) {
    // 失败了
    res.json({success: false, error: error});
  });
});

app.post('/login', function(req, res) {
  var username = req.body.username;
  var password = req.body.password;
  AV.User.logIn(username, password).then(function(success) {
    // 成功了，现在可以做其他事情了
    //res.redirect('/userhome');
    res.json({success: true, user: AV.User.current()});
  }, function(error) {
    // 失败了
    //res.redirect('/login');
    res.json({success: false, error: error});
  });
});

app.get('/userhome/:username', function(req, res) {
  var url1 = 'https://forest-novel.herokuapp.com' + '/user/' + req.params.username;
  var user;
  var storyArray;
  var topRatedArray;
  async.series([
      function(callback) {
        request({
            url: url1,
            json: true
        }, function (error, response, body) {
            //var url2 = ;
            if (!error && response.statusCode === 200) {
                user = body['user'];
            }
            callback();
        });

    	},
    	function(callback) {
        request({
            url: 'https://forest-novel.herokuapp.com' + '/storybyuser/' + user[0].objectId,
            json: true
        }, function (error, response, body) {

            if (!error && response.statusCode === 200) {
                storyArray = body;
            }
            callback();
        });

    	},
      function(callback) {
        request({
            url: 'https://forest-novel.herokuapp.com' + '/beststory/' + '1',
            json: true
        }, function (error, response, body) {

            if (!error && response.statusCode === 200) {
                topRatedArray = body;
            }
            callback();
        });
      }
    ],function(err, results) {
      res.render('userhome', {storyArray: storyArray, topRatedArray: topRatedArray, user: user});
    });
});

app.get('/userlike/:userId/:nodeId', function(req, res) {
  var userId = req.params.userId;
  var nodeId = req.params.nodeId;
  if (userId == undefined || nodeId == undefined) {
    res.json({success: false, error: "invalid arguments"});
  }
  var nodeQuery = new AV.Query('Node');
  nodeQuery.get(nodeId).then(function(node) {
    if (node.get('likeBy') == undefined) {
      node.set('likeBy', [userId]);
    } else {
      var new_arr = node.get('likeBy');
      if (new_arr.indexOf(userId) > -1) {
        res.json({success: false, error: "already liked"});
      }
      new_arr.push(userId);
      node.set('likeBy', new_arr);
    }
    return node.save();
  }).then(function(success) {
    res.json({success: true, node: success});
  }).catch(function(error) {
    res.json({success: false, error: error});
  });
});

app.get('/userfollowstory/:storyId/:userId', function(req, res) {
  var storyId = req.params.storyId;
  var userId = req.params.userId;
  if (storyId == undefined || userId == undefined) {
    res.json({success: false, error: "invalid arguments"});
  }
  var storyQuery = new AV.Query('Story');
  storyQuery.get(storyId).then(function(story) {
    if (story.get('followUser') == undefined) {
      story.set('followUser', [userId]);
    } else {
      var new_arr = story.get('followUser');
      new_arr.push(userId);
      story.set('followUser', new_arr);
    }
    return story.save();
  }).then(function(success) {
    res.json({success: true, story: success});
  }).catch(function(error) {
    res.json({success: false, error: error});
  });
});

app.get('/userfollowuser/:followerId/:followeeId', function(req, res) {
  var followerId = req.params.followerId;
  var followeeId = req.params.followeeId;
  if (followerId == undefined || followeeId == undefined) {
    res.json({success: false, error: "invalid arguments"});
  }
  var userQuery = new AV.Query(AV.User);
  userQuery.get(followerId).then(function(follower) {
    if (follower.get('followee') == undefined) {
      follower.set('followee', [followeeId]);
    } else {
      var new_arr = story.get('followee');
      new_arr.push(followeeId);
      follower.set('followee', new_arr);
    }
    return follower.save();
  }).then(function(success) {
    userQuery = new AV.Query(AV.User);
    userQuery.get(followeeId).then(function(followee) {
      if (followee.get('follower') == undefined) {
        followee.set('follower', [followerId]);
      } else {
        var new_arr = story.get('follower');
        new_arr.push(followerId);
        follower.set('follower', new_arr);
      }
      return follower.save();
    }).then(function(success) {
      res.json({success: true, story: success});
    }).catch(function(error) {
      res.json({success: false, error: error});
    });
  }).catch(function(error) {
    res.json({success: false, error: error});
  });
});
/////////////////////Post ADD///////////////////////
app.post('/comment/:nodeId/:userId', function(req, res) {
  var commentContent = req.body.commentContent;
  var userId = req.params.userId;
  var nodeId = req.params.nodeId;
  if (userId == undefined || nodeId == undefined || commentContent == undefined) {
    res.json({success: false, error: "invalid arguments"});
  }
  var nodeQuery = new AV.Query('Node');
  var userQuery = new AV.Query(AV.User);
  var comment = new Comment();
  comment.set('text', commentContent);
  userQuery.get(userId).then(function(user) {
    comment.set('userID', user);
    nodeQuery.get(nodeId).then(function(node) {
      comment.set('nodeID', node);
      return comment.save();
    }).then(function(success) {
      res.json({success: true, comment: success});
    }).catch(function(error) {
      res.json({success: false, error: error});
    });
  }).catch(function(error) {
    res.json({success: false, error: error});
  });
});

app.post('/node', function(req, res) {
  var developFrom = req.body.developFrom;
  var linkTo = req.body.linkTo;
  var content = req.body.nodeContent;
  var title = req.body.nodeTitle;
  var story = req.body.story;
  var writer = req.body.writer;
  if (content == undefined || title == undefined || story == undefined || writer == undefined) {
    res.json({success: false, error: "parameter incomplete"});
  }
  var newNode = new Node();
  newNode.set('content', content);
  newNode.set('title', title);
  var nodeQuery = new AV.Query('Node');
  if (developFrom != undefined) {
    newNode.set('developFrom', developFrom);
    nodeQuery.get(developFrom).then(function(foundDevelopFrom) {
      developFrom = foundDevelopFrom;
      nodeQuery = new AV.Query('Node');
      if (linkTo != undefined) {
        newNode.set('linkTo', linkTo);
        nodeQuery.get(linkTo).then(function(foundLinkTo) {
          linkTo = foundLinkTo;
          var storyQuery = new AV.Query('Story');
          storyQuery.get(story).then(function(sto) {
            story = sto;
            var writerQuery = new AV.Query(AV.User);
            writerQuery.get(writer).then(function(wri) {
              writer = wri;
              newNode.set('story', story);
              newNode.set('writer', writer);
              return newNode.save();
            }).then(function(node) {
              res.json({success: true, node: node});
            }).catch(function(error) {
              res.json({success: false, error: error});
            });
          }).catch(function(error) {
            res.json({success: false, error: error});
          });
        }).catch(function(error) {
          res.json({success: false, error: error});
        });
      }
    }).catch(function(error) {
      res.json({success: false, error: error});
    });
  }
});

app.post('/story', function(req, res) {
  var creator = req.body.creator;
  var title = req.body.storyTitle;
  var theme = req.body.theme;
  var intro = req.body.intro;
  if (creator == undefined || title == undefined || theme == undefined || intro == undefined) {
    res.json({success: false, error: "parameter incomplete"});
  }
  //update theme
  var themeQuery = new AV.Query('Theme');
  var writerQuery = new AV.Query(AV.User);
  var newStory = new Story();
  themeQuery.get(theme).then(function(getTheme) {
      theme = getTheme;
      writerQuery.get(creator).then(function(wri) {
        creator = wri;
        newStory.set('creator', creator);
        newStory.set('title', title);
        newStory.set('theme', theme);
        newStory.set('introduction', intro);
        return newStory.save();
      }).then(function(success) {
          res.json({success: true, story: success});
      }).catch(function(error) {
        res.json({success: false, error: error});
      });
  }).catch(function(error) {
    res.json({success: false, error: error});
  });
});
//////////////////////////////////Our Functions END here/////////////////////////////////////
// 可以将一类的路由单独保存在一个文件中
//app.use('/todos', todos);

// 如果任何路由都没匹配到，则认为 404
// 生成一个异常让后面的 err handler 捕获
app.use(function(req, res, next) {
  var err = new Error('Not Found');
  err.status = 404;
  next(err);
});

// error handlers

// 如果是开发环境，则将异常堆栈输出到页面，方便开发调试
if (app.get('env') === 'development') {
  app.use(function(err, req, res, next) { // jshint ignore:line
    var statusCode = err.status || 500;
    if(statusCode === 500) {
      console.error(err.stack || err);
    }
    res.status(statusCode);
    res.render('error', {
      message: err.message || err,
      error: err
    });
  });
}

// 如果是非开发环境，则页面只输出简单的错误信息
app.use(function(err, req, res, next) { // jshint ignore:line
  res.status(err.status || 500);
  res.render('error', {
    message: err.message || err,
    error: {}
  });
});

module.exports = app;
console.log('finished');
