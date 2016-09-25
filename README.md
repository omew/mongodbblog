#本博客系统基于mongodb yii2

###主要功能包含

* 个人设置
* 用户管理
* 创建页面
* 文章
* 评论
* 文章分类 文章标签
* 文件管理


### mongodb 导出导入实例

* mongodb导出

```
mongodump -h 127.0.0.1 -u admin -p xxx  -d blog -o '/home/timeless/桌面/mongodump' --authenticationDatabase admin
```
或者可以添加参数 -collection 表示要导出哪个集合


* mongodb恢复实例

```
mongorestore -h ×× -u admin -p ×× -d blog  /home/timeless/桌面/mongodump/blog --authenticationDatabase admin
```

