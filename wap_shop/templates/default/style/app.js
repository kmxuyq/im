/**
 * Created by yangxb on 2016/9/28.
 */
var express=require('express');
var path=require('path');
var ejs=require('ejs');
var app=express();

app.engine('.html',ejs.__express);
app.set('views','./');
app.use(express.static(path.join(__dirname)));
app.set('view engine','html');

app.get('/', function (req,res) {
    res.render('index')
});

app.get('/user',function(req,res){
    res.render('index2')
});

app.get('/list',function(req,res){
    res.render('list')
});


app.listen(3000, function () {
   console.log('node start');
});