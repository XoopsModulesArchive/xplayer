<?php

include '../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'xplayer_index.html';
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
/* establish file id */
$fileid = ($_GET['id']) ?: 1;
$playerid = ($_GET['player']) ?: 1;
$genrelist = '';
global $xoopsDB;
$sql = 'SELECT genre FROM ' . $xoopsDB->prefix('xplayer_files') . ' GROUP BY genre';
$result = $xoopsDB->query($sql);
while (list($genrelist) = $xoopsDB->fetchRow($result)) {
    $xoopsTpl->append('genrelist', $genrelist);
}

require_once XOOPS_ROOT_PATH . '/footer.php';
