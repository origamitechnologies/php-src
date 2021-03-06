--TEST--
Bug #46164 - 1 (stream_filter_remove() closes the stream)
--FILE--
<?php
class user_filter extends php_user_filter {
	function filter($in, $out, &$consumed, $closing) {
		while($bucket = stream_bucket_make_writeable($in)) {
			$consumed += $bucket->datalen;
			stream_bucket_append($out, $bucket);
		}
		return PSFS_PASS_ON;
	}
}
stream_filter_register('user_filter','user_filter');

$fd = fopen('php://memory','w');
$filter = stream_filter_append($fd, 'user_filter');
stream_filter_remove($filter);
var_dump(fclose($fd));
?>
--EXPECT--
bool(true)
