<?php

function c_boxLink($location, $text, $priv)
{
	global $tcenter;
	if ($tcenter->user->hasPriv($priv))
	{
		print "<span class='boxEntry'><a href='$location'>$text</a></span>";
	}
}

function t_checkbox ($text, $name, $priv = null)
{
	global $tcenter;
	if ($tcenter->user->hasPriv($priv)) {?>
			<td><?=$text?>: </td>
			<td>
				<input type="checkbox" name="<?=$name?>" onclick="userChanged(this);"/>
			</td>
<?
	}
}

function t_editInfo ($info)
{?>
			<a onclick="openInfoPage('<?=$info?>')" style="font-size: 1em; font-weight: normal; text-align: right; margin-bottom: -15px;" class='tContent'>[ edit <?=$info?> ]</a>
<?}?>
