<?php if (!defined('THINK_PATH')) exit();?>	<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>首页 - 素材火 Admin</title>

	<meta name="description" content="overview &amp; stats" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- basic styles -->

	<link href="/Public/Admin/Ace/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="/Public/Admin/Ace/assets/css/font-awesome.min.css" />

	<!--[if IE 7]>
	<link rel="stylesheet" href="/Public/Admin/Ace/assets/css/font-awesome-ie7.min.css" />
	<![endif]-->

	<!-- page specific plugin styles -->

	<!-- fonts -->

	<link rel="stylesheet" href="/Public/Admin/Ace/assets/css/ace-fonts.css" />

	<!-- ace styles -->

	<link rel="stylesheet" href="/Public/Admin/Ace/assets/css/ace.min.css" />
	<link rel="stylesheet" href="/Public/Admin/Ace/assets/css/ace-rtl.min.css" />
	<link rel="stylesheet" href="/Public/Admin/Ace/assets/css/ace-skins.min.css" />
	<style>
			#iframe{border:solid red 1px; width: 100%; height: 1000px; padding-bottom: 50px; background: #fff;}
			*{font-size: 14px; font-family: 微软雅黑;}
		</style>

	<!--[if lte IE 8]>
	<link rel="stylesheet" href="/Public/Admin/Ace/assets/css/ace-ie.min.css" />
	<![endif]-->

	<!-- inline styles related to this page -->

	<!-- ace settings handler -->

	<script src="/Public/Admin/Ace/assets/js/ace-extra.min.js"></script>
	<script src="/Public/Admin/Ace/assets/js/jquery-2.0.3.min.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

	<!--[if lt IE 9]>
	<script src="/Public/Admin/Ace/assets/js/html5shiv.js"></script>
	<script src="/Public/Admin/Ace/assets/js/respond.min.js"></script>
	<![endif]-->


		<script src="/Public/Admin/Ace/assets/js/bootstrap.min.js"></script>
	<script src="/Public/Admin/js/bootbox.js"></script>

</head>

<body>
	<style>body{background: #fff;}</style>

	<!-- 位置区域 -->	
	<div class="breadcrumbs" id="breadcrumbs">
		<script type="text/javascript">try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}</script>

		<ul class="breadcrumb">
			<li> <i class="icon-home home-icon"></i>
				<a href="#">Home</a>
			</li>

			<li>
				<a href="#">Tables</a>
			</li>
			<li class="active">Simple &amp; Dynamic</li>
		</ul>
		<!-- .breadcrumb -->	

		<div class="nav-search" id="nav-search">
			<form class="form-search">
				<span class="input-icon">
					<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" /> <i class="icon-search nav-search-icon"></i>
				</span>
			</form>
		</div>
		<!-- #nav-search -->	
	</div>

	<!-- 位置区域结束 -->	

	

	<!-- 百度编辑器开始 -->	
	<script type="text/javascript" charset="utf-8" src="/Public/Admin/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" charset="utf-8" src="/Public/Admin/ueditor/ueditor.all.min.js"></script>
	<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->	
	<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->	
	<script type="text/javascript" charset="utf-8" src="/Public/Admin/ueditor/lang/zh-cn/zh-cn.js"></script>
	<!-- 百度编辑器结束 -->	

	<script>
	
	  // 实现点击添加相册input框
  function add_pic () {

    // 复制一份tr  将它放在table的后面
    // js当中要实现多行字符串，需要在每一行的结尾添加“\”，“\”后面不能有空格
    var html = '<tr>\
            <td height="20" bgcolor="#FFFFFF" class="STYLE6">\
              <div align="right">\
                <span class="STYLE19" onclick="$(this).parent().parent().parent().remove();" ><i class="icon-trash bigger-130"></i>商品相册:</span>\
              </div>\
            </td>\
            <td height="20" bgcolor="#FFFFFF" class="STYLE6">\
              <div align="left">\
                <span class="STYLE19">\
                  <input type="file" name="goods_pics[]">\</span>\
              </div>\
            </td>\
          </tr>';

    $("#addHTML").append(html);

  }
</script>


<style>
	#addHTML span i{cursor: pointer;}
</style>
	<div class="page-content">
		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->	
				<form action="/index.php/Admin/Goods/add" enctype="multipart/form-data"  method="post" class="form-horizontal" role="form">

					<div class="tabbable">
						<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
							<li class="active">
								<a data-toggle="tab" href="#home1">通用信息</a>
							</li>

							<li>
								<a data-toggle="tab" href="#home2">详细描述</a>
							</li>

							<li>
								<a data-toggle="tab" href="#home3">其他信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#home4">商品属性</a>
							</li>
							<li>
								<a data-toggle="tab" href="#home5">商品相册</a>
							</li>
							<li>
								<a data-toggle="tab" href="#home6">关联商品</a>
							</li>

						</ul>

						<div class="tab-content">
							<div id="home1" class="tab-pane in active">
								<div class="form-group">
									<label class="col-sm-1 control-label no-padding-right" for="form-field-1">商品名称</label>
									<div class="col-sm-9">
										<input type="text" name="goods_name" id="goods_name" class="rcol-xs-10 col-sm-5" value="">	
										<span class="help-inline col-xs-12 col-sm-7">
											<span class="middle"></span>
										</span>
									</div>
								</div>
								<div class="space-2"></div>

								<div class="form-group">
									<label class="col-sm-1 control-label no-padding-right" for="form-field-1">价格</label>
									<div class="col-sm-9">
										<input type="text" name="goods_price" id="goods_price" class="rcol-xs-10 col-sm-5" value="">	
										<span class="help-inline col-xs-12 col-sm-7">
											<span class="middle"></span>
										</span>
									</div>
								</div>
								<div class="space-2"></div>

								<div class="form-group">
									<label class="col-sm-1 control-label no-padding-right" for="form-field-1">数量</label>
									<div class="col-sm-9">
										<input type="text" name="goods_number" id="goods_number" class="rcol-xs-10 col-sm-5" value="">	
										<span class="help-inline col-xs-12 col-sm-7">
											<span class="middle"></span>
										</span>
									</div>
								</div>
								<div class="space-2"></div>

								<div class="form-group">
									<label class="col-sm-1 control-label no-padding-right" for="form-field-1">重量</label>
									<div class="col-sm-9">
										<input type="text" name="goods_weight" id="goods_weight" class="rcol-xs-10 col-sm-5" value="">	
										<span class="help-inline col-xs-12 col-sm-7">
											<span class="middle"></span>
										</span>
									</div>
								</div>
								<div class="space-2"></div>

								<div class="form-group">
									<label class="col-sm-1 control-label no-padding-right" name="goods_logo" for="form-field-1">商品logo</label>
									<div class="col-sm-9">
										<input type="file" name="goods_logo" id=""></div>
								</div>
								<div class="space-2"></div>

							</div>

							<div id="home2" class="tab-pane">
								<div class="form-group">
									<div class="col-md-10 col-sm-10">
										<textarea name="goods_introduce" id="content" style="height:400px;"></textarea>
									</div>
								</div>
							</div>

							<div id="home3" class="tab-pane">其他信息</div>

							<div id="home4" class="tab-pane">商品属性</div>

							<div id="home5" class="tab-pane">
								<table  id="addHTML">

									<tr>
										<td height="20" bgcolor="#FFFFFF" class="STYLE6">
											<div align="right">
												<span class="STYLE19" onclick="add_pic()" ><i class="icon-zoom-in bigger-130"></i>商品相册:</span>
											</div>
										</td>
										<td height="20" bgcolor="#FFFFFF" class="STYLE6">
											<div align="left">
												<span class="STYLE19">
													<input type="file" name="goods_pics[]"></span>
											</div>
										</td>
									</tr>
								</table>

							</div>

							<div id="home6" class="tab-pane">关联商品</div>
						</div>
					</div>

					<div class="hr hr-24"></div>

					<input type="button" id="sub"  class="btn btn-info" value="添加商品"></form>
			</div>

		</div>

<!-- 		<script>
	$(function(){
		$('.btn-info').on('click',function(){
		$('form').submit();
	});
	})
</script> -->

		<!-- 百度编辑器 star -->	
		<script>var ue = UE.getEditor('content');</script>
		<!-- 百度编辑器end -->	


		<script>
			
			$("#sub").click(function(){
				// var sid = $("#sid").val();
				// var goods_name = $("#goods_name").val();
				// var content = $("#content").val();
				// if(goods_name==""){
				// 	bootbox.dialog({
				// 		goods_name: '友情提示：',
				// 		message: "商品名称不能为空", 
				// 		buttons: {
				// 			"success" : {
				// 				"label" : "确定",
				// 				"className" : "btn-danger"
				// 			}
				// 		}
				// 	});
				// 	return;
				// }
		
		$("form").submit();
				
			});
		</script>