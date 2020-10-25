<?php

include '../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'xplayer_category.html';
require XOOPS_ROOT_PATH . '/header.php';
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        ${$k} = $v;
    }
}
global $xoopsDB;
$filelist = [];
$sql = 'SELECT id, artist, title FROM ' . $xoopsDB->prefix('xplayer_files') . ' WHERE genre="' . $genrelist . '" ORDER BY artist ASC';
$result = $xoopsDB->query($sql);
while (false !== ($sqlfetch = $xoopsDB->fetchArray($result))) {
    $filelist['id'] = $sqlfetch['id'];

    $filelist['artist'] = $sqlfetch['artist'];

    $filelist['title'] = $sqlfetch['title'];

    $xoopsTpl->append('filelist', $filelist);
}
$xoopsTpl->assign('genrelist', $genrelist);
require_once XOOPS_ROOT_PATH . '/footer.php';
