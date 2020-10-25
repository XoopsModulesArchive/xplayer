<?php

function b_xplayer_latestscroll_show($options)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    $sql = 'SELECT title, artist FROM ' . $xoopsDB->prefix('xplayer_files') . ' LIMIT ' . $options[0] . '';

    $result = $xoopsDB->query($sql);

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $mp3files = [];

        $title = htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

        $artist = htmlspecialchars($myrow['artist'], ENT_QUOTES | ENT_HTML5);

        $mp3files['artist'] = $artist;

        $mp3files['title'] = $title;

        $mp3files['height'] = $options[1];

        $block['xplayer_files'][] = $mp3files;
    }

    return $block;
}

function b_xplayer_latestscroll_edit($options)
{
    $form = '' . _MB_XPLAYER_BLOCLATE . "<input type='text' size='3' maxlength='2' name='options[]' value='" . $options[0] . "'>&nbsp;" . _MB_XPLAYER_SONGS . '<br>';

    $form .= '' . _MB_XPLAYER_BLOCKSCROLL . "<input type='text' size='4' maxlength='3' name='options[]' value='" . $options[1] . "'>";

    return $form;
}
