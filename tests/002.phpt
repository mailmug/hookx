--TEST--
test1() Basic test
--EXTENSIONS--
hookx
--FILE--
<?php
$ret = test1();

var_dump($ret);
?>
--EXPECT--
The extension hookx is loaded and working!
NULL
