'use strict';
var domain = require('domain');
var express = require('express');
var path = require('path');
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser');
var todos = require('./routes/todos');
var cloud = require('./cloud');
var AV = require('leanengine');
AV.initialize('QdSwHCdXnUjjLLGhodgIWhe5-gzGzoHsz', 'bBT9v34EJ8hN6b4jpUre1YeF');
var app = express();

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
app.use(cookieParser());

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
  res.render('index', { currentTime: new Date() });
});

//////////////////////////////////Our Functions Start here/////////////////////////////////////

// get uer by usernpame
app.get('/user/:username', function(req, res) {////////////////OK
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
app.get('/user_email/:theemail', function(req, res) {/////OK
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
app.get('/commentbynodeid/:nodeid',function(req,res){////////OK
  var commentbynodeid=req.params.nodeid;
  var findCommentByNodeID=new AV.Query('Comment');
  findCommentByNodeID.equalTo('nodeID',commentbynodeid);
  findCommentByNodeID.find().then(function(comments) {//quert
    //found
    res.json({success: true, comments: comments});
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
});

//get node by id
app.get('/node/:nodeid2',function(req,res){///////OK
  var querynodeid=req.params.nodeid2;
  var findNodeByNodeID=new AV.Query('Node');
  findNodeByNodeID.get(querynodeid).then(function(obj){
    res.json({success: true, node: obj});
  },function(error){
     res.json({success: false});
  });
});

app.get('story/:storyId', function(req, res) {
  var storyId = req.params.storyId;
  var storyQuery = new AV.Query('Story');
  storyQuery.get(storyId).then(function(story) {
    res.json({success: true, story: story});
  }).catch(function(error;) {
    res.json({success: false, error: error});
  });
});
//get all themes
app.get('/theme',function(req,res){///////OK
   var findAllTheme=new AV.Query('Theme');
   findAllTheme.find().then(function(obj){
    res.json({success: true, theme: obj});
   },function(error){
     res.json({success: false});
   });

});

//Get story by theme
app.get('/storybythemeid/:themeid',function(req,res){////////OK
var querystorybytheme=req.params.themeid;
var findStoryBythemeID=new AV.Query('Story');
findStoryBythemeID.equalTo('theme',querystorybytheme);


 findStoryBythemeID.find().then(function(obj) {//quert
    //found
    res.json({success: true, story: obj});
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
});


//find nodes by story id
app.get('/nodebystoryid/:storyid',function(req,res){//////OK
var querynodebystory=req.params.storyid;
var findNodeByStoryID=new AV.Query('Node');
findNodeByStoryID.equalTo('story',querynodebystory);

findNodeByStoryID.find().then(function(obj) {//quert
  //found
  res.json({success: true, node: obj});
}).catch(function(error) {
  //failed
  res.json({success: false});
});
});


//search story by userid
app.get('/storybyuser/:userid',function(req,res){
  var querystoryidbyuser=req.params.userid;
  var findStoryIdByUserID=new AV.Query(AV.User);
  var stories = {};
  findStoryIdByUserID.get(querystoryidbyuser).then(function(obj){
    var innerQuery = new AV.Query('Story');
    innerQuery.find().then(function(results) {
      results.map(function(result) {
        if (result.get('followUser').indexOf(querystoryidbyuser) > -1) {
          stories[result.get('id')] = result;
        }
      });
      res.json({success: true, story: JSON.stringify(stories)});
    });
  },function(error){
     res.json({success: false, error: error});
  });
});

//Search Story by likenumber ranking
app.get('/beststory/:topnum',function(req,res){
  var topnum = req.params.topnum;
  var findStorybylikerank = new AV.Query('Story');
  findStorybylikerank.find().then(function(results) {
    results.sort(function(x, y) {
      if (x.get('followUser').length > y.get('followUser').length) {
        return -1;
      }
      if (x.get('followUser').length < y.get('followUser').length) {
        return 1;
      }
      return 0;
    });
    res.json({success: true, bestStory: results.slice(0, topnum + 1)});
  }).catch(function(error) {
    res.json({success: false, error: error});
  });
});

app.post('/comment/:nodeId/:userId', function(req, res) {
  var commentContent = req.body.commentContent;
  var userId = req.params.userId;
  var nodeId = req.params.nodeId;
  var Comment = AV.Object.extend('Comment');
  var comment = new Comment();
  comment.set('nodeID', nodeId);
  comment.set('userID', userId);
  comment.set('text', commentContent);
  comment.save().then(function(success) {
    res.json({success: true, comment: comment});
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
    res.json({success: true, user: AV.User.current()});
  }, function(error) {
    // 失败了
    res.json({success: false, error: error});
  });
});

app.get('/node/:developFrom/:linkTo', function(req, res) {
  var developFrom = req.params.developFrom;
  var linkTo = req.params.linkTo;
  var Node = AV.Object.extend('Node');
  var newNode = Node();

});
/////////////////////Post ADD///////////////////////

//////////////////////////////////Our Functions END here/////////////////////////////////////
// 可以将一类的路由单独保存在一个文件中
app.use('/todos', todos);

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
