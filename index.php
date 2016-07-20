<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-22 15:12:54
 */
#设置字符集
header('Content-Type:text/html;charset=utf8');

define("APP_DEBUG", TRUE);
#定义目录安全文件是否生成（默认是true）
define('BUILD_DIR_SECURE', false);

define('DIR_SECURE_FILENAME', 'default.html');

//当前文件所在的工作目录
define("WORKING_PATH", str_replace("\\", "/", __DIR__));
//上传的根目录

include "ThinkPHP/ThinkPHP.php";