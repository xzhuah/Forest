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

// get uer by username 
app.get('/user/:username', function(req, res) {
  var username = req.params.username;
  var userQuery = new AV.Query(AV.User);//choose table
  userQuery.equalTo('username', username);//Condition
  userQuery.find().then(function(user) {//quert
    //found
    res.json(user);
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
});

// get uer by email
app.get('/user_email/:email', function(req, res) {
  var email = req.params.email;
  var EmailuserQuery = new AV.Query(AV.User);//choose table
  userQuery.equalTo('email', email);//Condition
  userQuery.find().then(function(user) {//quert
    //found
    res.json(user);
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
});



//Return comments by a nodeid
app.get('/commentbynodeid/:nodeid',function(req,res){
  var commentbynodeid=req.params.nodeid;
  var findCommentByNodeID=new AV.Query("Comment");
  findCommentByNodeID.equalTo('nodeID',commentbynodeid);
  findClientByUsername.find().then(function(comments) {//quert
    //found
    res.json(comments);
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
});

//get node by id
app.get('/node/:nodeid',function(req,res){
  var querynodeid=res.params.nodeid;
  var findNodeByNodeID=new AV.Query("Node");
  findNodeByNodeID.get(querynodeid).then(function(obj){
    res.json(obj);
  },function(error){
     res.json({success: false});
  });
});

//get all themes
app.get('/theme',function(req,res){
   var findAllTheme=new AV.Query("Theme");
   findAllTheme.get().then(function(obj){
    res.json(obj);
   },function(error){
     res.json({success: false});
   });
 
});

//Get story by theme
app.get('/storybythemeid/:themeid',function(req,res){
var querystorybytheme=res.params.themeid;
var findStoryBythemeID=new AV.Query("Story");
findStoryBythemeID.equalTo("theme",querystorybytheme);


 findStoryBythemeID.find().then(function(obj) {//quert
    //found
    res.json(obj);
  }).catch(function(error) {
    //failed
    res.json({success: false});
  });
});


//find nodes by story id
app.get('/nodebystoryid/:storyid',function(req,res){
var querynodebystory=res.params.storyid;
var findNodeByStoryID=new AV.Query("Node");
findNodeByStoryID.equalTo("story",querynodebystory);

findNodeByStoryID.find().then(function(obj) {//quert
  //found
  res.json(obj);
}).catch(function(error) {
  //failed
  res.json({success: false});
});
});


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
console.log("finished");
