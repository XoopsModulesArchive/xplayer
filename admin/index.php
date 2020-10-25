<?php

require __DIR__ . '/admin_header.php';
//function for displaying xplayer administration
function xplayeradmin()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    global $xoopsDB, $genrelist, $xoopsModule;

    $sql = 'SELECT genre FROM ' . $xoopsDB->prefix('xplayer_files') . ' GROUP BY genre';

    $result = $xoopsDB->query($sql);

    while (list($genrelist) = $xoopsDB->fetchRow($result)) {
        $xoopsTpl->append('genrelist', $genrelist);
    }

    $preflink = XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $xoopsModule->getVar('mid');

    $xoopsTpl->assign('preflink', $preflink);

    $xoopsTpl->display('db:xplayer_amxplayer.html');
}

//------------------------------------------
//function for displaying available genres
function genremanager()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    global $xoopsDB;

    $sql = 'SELECT genre FROM ' . $xoopsDB->prefix('xplayer_files') . ' GROUP BY genre';

    $result = $xoopsDB->query($sql);

    while (list($genre) = $xoopsDB->fetchRow($result)) {
        $xoopsTpl->append('genre', $genre);
    }

    $xoopsTpl->display('db:xplayer_amgenremanage.html');
}

//------------------------------------------
function showmpegs()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    global $xoopsDB, $genrelist;

    $filelist = [];

    $sql = 'SELECT id, artist, title FROM ' . $xoopsDB->prefix('xplayer_files') . " WHERE genre='$genrelist' ORDER BY artist ASC ";

    $result = $xoopsDB->query($sql);

    while (false !== ($sqlfetch = $xoopsDB->fetchArray($result))) {
        $filelist['id'] = $sqlfetch['id'];

        $filelist['artist'] = $sqlfetch['artist'];

        $filelist['title'] = $sqlfetch['title'];

        $xoopsTpl->append('filelist', $filelist);
    }

    $xoopsTpl->display('db:xplayer_amshowmpegs.html');
}

function editmpegs()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    global $xoopsDB, $mpegid;

    $sql = 'SELECT id,title,artist,album,year,comment,track,genre FROM ' . $xoopsDB->prefix('xplayer_files') . " WHERE id=$mpegid ";

    $result = $xoopsDB->query($sql);

    while (list($id, $title, $artist, $album, $year, $comment, $track, $genre) = $xoopsDB->fetchRow($result)) {
        $xoopsTpl->assign('id', $id);

        $xoopsTpl->assign('title', $title);

        $xoopsTpl->assign('artist', $artist);

        $xoopsTpl->assign('album', $album);

        $xoopsTpl->assign('year', $year);

        $xoopsTpl->assign('comment', $comment);

        $xoopsTpl->assign('track', $track);

        $xoopsTpl->assign('genre', $genre);
    }

    $xoopsTpl->display('db:xplayer_ameditmpegs.html');
}

//function for saving edited mpegs
function saveeditmpegs()
{
    global $xoopsDB, $mpegid, $title, $artist, $album, $year, $comment, $track, $genre;

    $sql = 'UPDATE ' . $xoopsDB->prefix('xplayer_files') . " SET title='$title',artist='$artist',album='$album',year='$year',comment='$comment',track='$track',genre='$genre' WHERE id='" . $mpegid . "' ";

    $result = $xoopsDB->query($sql);

    redirect_header('index.php', 1, 'Database updated');
}

//------------------------------------------
//function for displaying available players
function playermanager()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    global $xoopsDB;

    $sql = 'SELECT name FROM ' . $xoopsDB->prefix('xplayer_player') . ' ';

    $result = $xoopsDB->query($sql);

    while (list($player) = $xoopsDB->fetchRow($result)) {
        $xoopsTpl->append('player', $player);
    }

    $xoopsTpl->display('db:xplayer_amplaymanage.html');
}

//------------------------------------------
//function for deleting genre when confirmed
function deletegenre()
{
    global $xoopsDB, $genrecat;

    $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xplayer_files') . " WHERE genre='$genrecat'");

    redirect_header('index.php', 1, $genrecat . ' deleted');
}

//------------------------------------------
//function for deleting player when confirmed
function deleteplayer()
{
    global $xoopsDB, $playertype;

    $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xplayer_player') . " WHERE name='$playertype'");

    redirect_header('index.php', 1, $playertype . ' deleted');
}

//------------------------------------------
//function for deleting mp3 when confirmed
function deletesong()
{
    global $xoopsDB, $mpegid;

    $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xplayer_files') . " WHERE id='$mpegid'");

    redirect_header('index.php', 1, 'MPEG ID: ' . $mpegid . ' deleted');
}

//------------------------------------------
//function for delete confirmation
function delconf()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    global $genrecat, $playertype, $mpegid;

    $xoopsTpl->assign('genrecat', $genrecat);

    $xoopsTpl->assign('playertype', $playertype);

    $xoopsTpl->assign('mpegid', $mpegid);

    $xoopsTpl->display('db:xplayer_amdelconf.html');
}

//------------------------------------------
//function for renaming genre
function editgenre()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    global $genrecat, $genrenew;

    $xoopsTpl->assign('genrecat', $genrecat);

    $xoopsTpl->display('db:xplayer_ameditgenre.html');
}

//------------------------------------------
//function for single upload
function singleupload()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    global $genrecat, $genrenew;

    $xoopsTpl->display('db:xplayer_upload.html');
}

//------------------------------------------
//function for saving renamed genres
function editgenresave()
{
    global $xoopsDB, $genrenew, $genrecat;

    $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('xplayer_files') . " SET genre = '$genrenew' WHERE genre='$genrecat'");

    redirect_header('index.php', 1, 'Database updated');
}

//------------------------------------------
//function for saving player changes
function changeplayer()
{
    global $xoopsDB, $name, $html_code, $playertype, $kacken, $htmlnewer, $htmlnew, $playernew;

    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('xplayer_player') . " SET html_code = '$playernew' WHERE name='$name'");

    redirect_header('index.php', 1, 'Database updated');
}

//------------------------------------------
//function editing players
function editplayer()
{
    xoops_cp_header();

    require_once XOOPS_ROOT_PATH . '/class/template.php';

    if (!isset($xoopsTpl)) {
        $xoopsTpl = new XoopsTpl();
    }

    $myts = MyTextSanitizer::getInstance();

    global $xoopsDB, $playertype, $playnew;

    $result = $xoopsDB->query('SELECT name, html_code FROM ' . $xoopsDB->prefix('xplayer_player') . " WHERE name='" . $playertype . "' ");

    [$name, $html_code] = $xoopsDB->fetchRow($result);

    $xoopsTpl->assign('name', $name);

    $xoopsTpl->assign('html_code', htmlspecialchars($html_code, ENT_QUOTES | ENT_HTML5));

    $xoopsTpl->display('db:xplayer_ameditplay.html');
}

//------------------------------------------
//function for saving new players
function newplayer()
{
    global $xoopsDB, $newplayer, $newplayername;

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('xplayer_player') . " (name, html_code) VALUES ('$newplayername', '$newplayer') ");

    redirect_header('index.php', 1, 'New player added');
}

//------------------------------------------
$op = '';
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
if (isset($_GET['op'])) {
    $op = trim($_GET['op']);

    if (isset($_GET['genrecat'])) {
        $genrecat = ($_GET['genrecat']);
    }

    if (isset($_POST['genrecat'])) {
        $genrecat = ($_POST['genrecat']);
    }

    if (isset($_GET['genrenew'])) {
        $genrenew = ($_GET['genrenew']);
    }

    if (isset($_POST['genrenew'])) {
        $genrenew = ($_POST['genrenew']);
    }
}
switch ($op) {
    case 'genremanager':
        genremanager();
        break;
    case 'showmpegs':
        showmpegs();
        break;
    case 'playermanager':
        playermanager();
        break;
    case 'deletegenre':
        deletegenre();
        break;
    case 'deleteplayer':
        deleteplayer();
        break;
    case 'newplayer':
        newplayer();
        break;
    case 'deletesong':
        deletesong();
        break;
    case 'delconf':
        delconf();
        break;
    case 'editgenre':
        editgenre();
        break;
    case 'editmpegs':
        editmpegs();
        break;
    case 'saveeditmpegs':
        saveeditmpegs();
        break;
    case 'editplayer':
        editplayer();
        break;
    case 'singleupload':
        singleupload();
        break;
    case 'editgenresave':
        editgenresave();
        break;
    case 'changeplayer':
        changeplayer();
        break;
    case 'default':
    default:
        xplayeradmin();
        break;
}
xoops_cp_footer();
