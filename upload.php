<?php

include '../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'xplayer_upload.html';
require XOOPS_ROOT_PATH . '/header.php';
//array ($_FILES['mp3file'] = 0);
// && ($_FILES['mp3file']['type'] == "")
if (is_uploaded_file($_FILES['mp3file']['tmp_name']) && (0 == $_FILES['mp3file']['error']) && ('audio/mpeg' == $_FILES['mp3file']['type'])) {
    /* generate file name and move file */

    do {
        $filename = time() . '.mp3';
    } while (file_exists($filename));

    $filepath = XOOPS_ROOT_PATH . "/modules/xplayer/upload/$filename";

    move_uploaded_file($_FILES['mp3file']['tmp_name'], $filepath);

    /* reading information from mp3 */

    require __DIR__ . '/class/mp3info.php';

    $mp3info = new mp3info($filepath);

    $artist = $mp3info->artist;

    $title = $mp3info->title;

    $album = $mp3info->album;

    $year = $mp3info->year;

    $comment = $mp3info->comment;

    $track = $mp3info->track;

    $genre = $mp3info->genreName;

    $length = $mp3info->length;

    $bitrate = $mp3info->bitrate;

    $frequence = $mp3info->frequence;

    if ('' == $genre) {
        $genre = 'Other';
    }

    $xoopsTpl->assign('filepath', $filepath);

    $xoopsTpl->assign('title', $title);

    $xoopsTpl->assign('artist', $artist);

    $xoopsTpl->assign('album', $album);

    $xoopsTpl->assign('year', $year);

    $xoopsTpl->assign('comment', $comment);

    $xoopsTpl->assign('track', $track);

    $xoopsTpl->assign('genre', $genre);

    $xoopsTpl->assign('length', $length);

    $xoopsTpl->assign('bitrate', $bitrate);

    $xoopsTpl->assign('frequence', $frequence);

    $xoopsTpl->assign('lang_file', _MD_XPLAYER_FILE);

    /* insert file into database and show sucess or not */

    global $xoopsDB;

    $creationtime = formatTimestamp(time());

    $sql = 'INSERT INTO '
                    . $xoopsDB->prefix('xplayer_files')
                    . " (filename, added, title, artist, album, year, comment, track, genre, length, bitrate, frequence) VALUES ('"
                    . $filename
                    . "', '$creationtime','"
                    . $title
                    . "','"
                    . $artist
                    . "','"
                    . $album
                    . "', '"
                    . $year
                    . "', '"
                    . $comment
                    . "', '"
                    . $track
                    . "', '"
                    . $genre
                    . "', '"
                    . $length
                    . "', '"
                    . $bitrate
                    . "', '"
                    . $frequence
                    . "')";

    $result = $xoopsDB->query($sql);

    if ($result) {
        $xoopsTpl->assign('dbsucc', $result);
    } else {
        $xoopsTpl->assign('dbfail', $result);
    }
}
require_once XOOPS_ROOT_PATH . '/footer.php';
