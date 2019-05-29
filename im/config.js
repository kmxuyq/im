var config = {};//数据库帐号设置

config['host']  	= 'rm-wz983038i6rm52b43.mysql.rds.aliyuncs.com';//数据库地址
config['port']  	= '';//数据库端口
config['user']  	= 'cys_cgj';//数据库用户名
config['password']  	= 'cys_ynxarAccess';//数据库密码
config['database']  	= 'im';//mysql数据库名
config['tablepre']  	= 'az_';//表前缀
config['insecureAuth']  	= true;//兼容低版本
config['debug']  	= false;//默认false

exports.hostname = '';//授权连接的域名或IP,为空不限制
exports.port = 8090;//服务器所用端口号,默认8090
exports.config = config;
