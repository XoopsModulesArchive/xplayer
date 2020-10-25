<?php

include '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
$xoopsTpl = new XoopsTpl();
/* establish file id */
$fileid = ($_GET['id']) ?: 1;
$playerid = ($_GET['player']) ?: 1;
/* aktuelle Datei laden */
$filename = '';
$sql = 'SELECT filename,artist,title,album,year,comment,track,genre,length,bitrate,frequence FROM ' . $xoopsDB->prefix('xplayer_files') . ' WHERE id = ' . $fileid . ' ';
$result = $xoopsDB->query($sql);
while (list($filename, $artist, $title, $album, $year, $comment, $track, $genre, $length, $bitrate, $frequence) = $xoopsDB->fetchRow($result)) {
    $mp3file = 'upload/' . $filename;

    $xoopsTpl->assign('artist', $artist);

    $xoopsTpl->assign('title', $title);

    $xoopsTpl->assign('album', $album);

    $xoopsTpl->assign('year', $year);

    $xoopsTpl->assign('comment', $comment);

    $xoopsTpl->assign('track', $track);

    $xoopsTpl->assign('genre', $genre);

    $xoopsTpl->assign('length', $length);

    $xoopsTpl->assign('bitrate', $bitrate);

    $xoopsTpl->assign('frequence', $frequence);
}
// if ( $result === false ) { die ( 'SQL error: '.$sql .''); }
/* player */
$player = '';
$sql = 'SELECT html_code FROM ' . $xoopsDB->prefix('xplayer_player') . ' WHERE id = ' . $playerid . ' ';
$result = $xoopsDB->query($sql);
if ($result) {
    $row = $GLOBALS['xoopsDB']->fetchObject($result);

    $player .= $row->html_code;
}
// if ( $result === false ) { die ( 'SQL error: '.$sql .''); }
/* player selection */
$availableplayer = '';
$sql = 'SELECT id,name FROM ' . $xoopsDB->prefix('xplayer_player') . ' ';
$result = $xoopsDB->query($sql);
if ($result) {
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchObject($result))) {
        if ($row->id == $playerid) {
            $availableplayer .= '-&gt; ';
        }

        $availableplayer .= "<a href='$PHP_SELF?id=$fileid&player=$row->id'>$row->name</a><br>";
    }
}
// if ( $result === false ) { die ( 'SQL error: '.$sql .''); }
$xoopsTpl->assign('availableplayer', $availableplayer);
/* /* generate output */
$player = str_replace('<@mp3file@>', $mp3file, $player);
$xoopsTpl->assign('player', $player);
xoops_header();
$xoopsTpl->display('db:xplayer_player.html');
exit();
xoops_footer();
