<?php
include('./functions.php');
general::maintenance();
require_once('./template/template.php');
template::pagetitle('Produkte');
template::get_header();

echo '<a href="/">Fehler</a>';

template::get_footer();
?>