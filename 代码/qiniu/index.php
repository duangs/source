<?php
require './bootstrap.php';
$list = $bucket_manager->listFiles($bucket,'plan/');
// var_dump($list);
// exit;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>文件上传</title>
	<link rel="stylesheet" href="">
	<style type="text/css" media="screen">
		html, body {padding: 0; margin: 0; font-size: 14px; color: #333;}
		.panel{ width: 400px; min-height: 100px; margin: 50px auto; box-shadow: 0 0 10px #333; }
		.panel .overflow{ overflow: hidden; }
		.panel .close{ float: right; font-size: 21px; font-weight: 700; line-height: 1; padding:0; color: #999; text-shadow: 0 1px 0 #fff; background-color: transparent; border:0; }
		.panel .close:hover,.panel .close:active{ color: #000; }
		.panel .close:hover,.panel .close:active,.panel .close:focus{ outline: none; }
		.panel .up-btn{ display: block; padding: 5px 8px; text-align: center; border: 1px solid #ccc; cursor: pointer;}
		.panel .form-inline{ padding: 10px; }
		.panel button{ border: 1px solid #ccc; background-color: #fff; padding: 5px 8px; box-shadow: none; cursor: pointer; text-align: center; min-width: 30px;}
		.panel button:hover,.panel .up-btn:hover,.panel .up-btn:active{ background-color: #eee; }
		.panel .sub-btn{ font-size: 1.4em; display: block; width: 100px; float: right; clear: both; }
		.panel .list { list-style: none; margin: 0; padding: 0; overflow-x: hidden; overflow-y: auto; max-height: 400px; }
		.panel .list .list-item{ padding: 6px 12px; font-size: 1.1em; text-align: left; border-width:0 1px 1px;border-style: solid; border-color: #999; }
		.panel .list .list-item:first-child{ border-top: 1px solid #999; }
		.panel .list .list-item .close{ position: relative; top:0; right: 0; }
	</style>
</head>
<body>
	<div style="width:850px; margin: 0 auto;">
		<div class="panel" style="float:left;">
			<div class="form-inline">
				<h3>列表</h3>
				<ul class="list overflow">
					<?php foreach($list[0] as $k=>$v):?>
					<li class="list-item">
						<strong><a href="<?php echo create_url($v['key']);?>"><?php echo $v['key'];?></a></strong>
					</li>
				<?php endforeach;?>
				</ul>
			</div>
		</div>
		<div style="float:left; padding: 10px;"></div>
		<div class="panel" style="float:left;">
			<form action="upload.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
				<div class="form-inline" id="files">
					<input type="file" id="up" placeholder="" style="display: none"/>
					<label class="up-btn" for="up">选择文件</label>
				</div>
				<div class="form-inline">
					<ul class="list overflow">
					</ul>
				</div>
				<div class="form-inline overflow">
					<button type="submit" class="sub-btn">上传</button>
				</div>
				<div class="form-inline"></div>
			</form>
		</div>
	</div>
</body>
</html>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
var id = 1;
function create_input_file($obj){
	var val = $obj.val();
	var path_arr = val.split("\\");
	var filename = path_arr[path_arr.length-1];
	var $file = $obj.clone(true,true);
	var $list_item = $('<li class="list-item"><strong>'+filename+'</strong><button type="button" class="close" data-id="input'+id+'">&times;</button></li>');

	$file.appendTo('#files');
	$file.attr('id','up');
	$file.val('');

	$obj.attr('id','input'+id);
	$obj.attr('name','file[]');

	id++;
	$list_item.appendTo('.panel .list');
	$list_item.children('.close').on('click',function(){
		$close_btn = $(this);
		var inputid = $close_btn.data('id');
		$file = $('#files input#'+inputid);
		$file.remove();
		$close_btn.parents('.list-item').remove();
	});
	
}
$(function(){
	$("#up").change(function(){
		var $file = $(this);
		create_input_file($file);		
	});
})
</script>