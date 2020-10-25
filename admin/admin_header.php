<?php

require dirname(__DIR__, 3) . '/include/cp_header.php';
require_once dirname(__DIR__, 3) . '/class/module.textsanitizer.php';
if (file_exists('../language/' . $xoopsConfig['language'] . '/admin.php')) {
    include '../language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    include '../language/english/admin.php';
}
