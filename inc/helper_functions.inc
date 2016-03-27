<?php
/*
 * Wrapper for var_dump
 */
function tdump ($o)
{
	print "<pre>";
	var_dump($o);
	print "</pre>";
}

/*
 * The exit function will run at the end of normal script operation, and calls the footer, and closes SQL
 */
function normalExit ()
{
	global $tcenter;
	global $noheader;
	global $tcenterVersion; 
	global $title;

	$_SESSION['tcenter'] = $tcenter;

	include "footer.inc";

	if ($noheader)
		ob_clean();

	ob_end_flush();
}

/*
 * Exception handler to catch any uncaught exceptions
 */

function uncaughtException($e)
{
	global $noheader;
	if ($noheader)
		ob_clean();
?>
	<div class="box ERROR" style="width: 60%">
		<div class="boxHeader noFold">Error</div>

		<div class="boxContent">
			<?var_dump($e);?>
		</div>
	</div>
<?
	ob_flush();
	exit;
}

function array_remove_key() {
	$args = func_get_args();
	$arr = $args[0];
	$keys = array_slice($args,1);

	foreach($arr as $k=>$v) {
		if(in_array($k, $keys))
			unset($arr[$k]);
	}
	return $arr;
}

function doubleQuote ($str) {
	$str = str_replace("\"", "''", $str);
	$str = str_replace("\\", "", $str);
	$str = str_replace("\r", "", $str);
	$str = str_replace("\t", "", $str);
	$str = str_replace("\n", "<br/>", $str);
	return $str;
}

function defaultToDo ($os)
{
    $todo = "[{";

    if ($os == "Windows") { $todo .= "\"Disable System Restore\":false,\"Run Anti-Spyware\":false,\"Run Anti-Virus\":false,"; }

    if ($os != "DataBackup")
    {
        $todo .= "\"Reset Passwords\":false,\"Check for Media\":false,";
    }
    else
    {
        $todo .= "\"Backup Data\":false,\"Restore Data\":false,";
    }

    $todo = substr($todo, 0, strlen($todo)-1)."}]";
    return $todo;
}
