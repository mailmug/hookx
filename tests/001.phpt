--TEST--
Check if hookx is loaded
--EXTENSIONS--
hookx
--FILE--
<?php
echo 'The extension "hookx" is available';
?>
--EXPECT--
The extension "hookx" is available
