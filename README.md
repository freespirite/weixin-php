## README
可管理多个公众号，快速回复、推文发送等；
在 ThinkCMF 的系统下增加微信管理板块功能，由于编写仓促，很多地方还有待改进；
配置如下：
导入weixin_php.sql数据库文件
data/conf/db.php 配置数据库
application/Common/Conf/config.php 配置第100行的 SERVER_URL 微信接收信息地址，后面的wx.php开始保留
data/runtime、data/upload、ueditor/php/upload 目录设置为可读写，并应用到子目录

后台登录账号密码都是 admin