var AV = require('leanengine');
AV.initialize('QdSwHCdXnUjjLLGhodgIWhe5-gzGzoHsz', 'bBT9v34EJ8hN6b4jpUre1YeF');
/**
 * 一个简单的云代码方法
 */
AV.Cloud.define('hello', function(request, response) {
  response.success('Hello world!');
});

module.exports = AV.Cloud;
