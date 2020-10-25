<?php

include 'header.php';
require XOOPS_ROOT_PATH . '/header.php';
$fileid = ($_GET['id']) ?: 1;
// require __DIR__ . '/inc/config.inc.php';
/* $connection = mysql_connect($db['host'],$db['uid'],$db['pwd']);
if($connection)
{
if(mysqli_select_db($GLOBALS['xoopsDB']->conn, $db['database']))
{*/
global $xoopsDB;
$sql = 'SELECT filename,title,artist FROM ' . $xoopsDB->prefix('xplayer_files') . ' ';
$newfilename = '';
$result = $xoopsDB->query($sql);
if ($result) {
    $row = $GLOBALS['xoopsDB']->fetchObject($result);

    $newfilename = $row->artist . ' - ' . $row->title . '.mp3';

    $filename = 'upload/' . $row->filename;
} else {
    die('Die Datei konnte nicht gefunden werden!');
}
/* }
$GLOBALS['xoopsDB']->close($connection);
}*/
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"$newfilename\"");
readfile($filename);
