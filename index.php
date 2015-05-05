<?php
$dir = realpath('./uploads');
$file_list = getFileList($dir);

/*
echo "<pre>";
print_r($file_list);
echo "</pre>";
exit();
*/
function getFileList($dir)
{
	$file_list = scandir($dir);
	unset($file_list[0]); // .
	unset($file_list[1]); // ..
	$file_list = array_filter($file_list);
	
	$tmp = array();
	foreach($file_list as $file)
	{
		if(preg_match("/\-([0-9]+)\-([0-9]+)$/", $file, $m))
		{
			$tmp['incompleted'][] = array
			(
				'filename' => preg_replace("/\-[0-9]+\-[0-9]+$/", "", $file),
				'start' => $m[1],
				'end' => $m[2],
				'percent' => intval($m[1] * 100 / $m[2])
			);
		}
		else
			$tmp['completed'][] = $file;
	}
	return $tmp; 
}
?>
<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			.progress
			{
				display: inline-block;
			    background-color: #f5f5f5;
			    border-radius: 4px;
			    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) inset;
			    height: 20px;
			    width: 300px;
			    margin-top: 2px;
			}
			.progress .progress-bar
			{
			    height: 100%;
			    width: 0%;
			    background-color: #5cb85c;
			    border-radius: 4px;
				background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
				background-size: 40px 40px;
				animation: 2s linear 0s normal none infinite running progress-bar-stripes;
			}
			.progress .progress-finished
			{
			    height: 100%;
			    width: 100%;
			    background-color: #5cb85c;
			    border-radius: 4px;
			}
			@keyframes progress-bar-stripes
			{
				0%
				{
			    	background-position: 40px 0;
				}
				100%
				{
			    	background-position: 0 0;
				}
			}
		</style>
		<script type="text/javascript" src="upload.js"></script>
	</head>
	<body>
		<form action="" method="post">
			<input type="file" name="file" multiple="multiple"/>
			<input type="submit" />
		</form>
		<div id="toupload">
			<h2>to upload:</h1>
			<ul>
			</ul>
		</div>
		<div id="incompleted">
			<h2>incompleted:</h1>
			<ul>
			<?php foreach($file_list['incompleted'] as $fl): ?>
				<li data-name="<?=$fl['filename']?>" data-start="<?=$fl['start']?>" data-end="<?=$fl['end']?>">
					<span><?=$fl['filename']?></span>
					<div class="progress">
						<div class="progress-bar" style="width: <?=$fl['percent']?>%">
						</div>
					</div>
				</li>
			<?php endforeach ?>
			</ul>
		</div>
		<div id="completed">
			<h2>completed:</h1>
			<ul>
			<?php foreach($file_list['completed'] as $fl): ?>
				<li><a href="uploads/<?=$fl?>"><?=$fl?></a></li>
			<?php endforeach ?>
			</ul>
		</div>
	</body>
</html>
