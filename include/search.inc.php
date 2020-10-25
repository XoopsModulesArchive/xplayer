<?php

function xplayer_search($queryarray, $andor, $limit, $offset)
{
    global $xoopsDB;

    $sql = 'SELECT id, added, title, artist, album, genre FROM ' . $xoopsDB->prefix('xplayer_files') . ' WHERE added>0';

    // because count() returns 1 even if a supplied variable

    // is not an array, we must check if $querryarray is really an array

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((artist LIKE '%$queryarray[0]%' OR title LIKE '%$queryarray[0]%' OR genre LIKE '%$queryarray[0]%' OR album LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(artist LIKE '%$queryarray[$i]%' OR title LIKE '%$queryarray[$i]%' OR genre LIKE '%$queryarray[$i]%' OR album LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= 'ORDER BY added DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $ret = [];

    $i = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'images/forum.gif';

        $ret[$i]['link'] = 'index.php?id=' . $myrow['id'] . '';

        $ret[$i]['title'] = $myrow['artist'] . ' - ' . $myrow['title'];

        $ret[$i]['time'] = $myrow['added'];

        $i++;
    }

    return $ret;
}
