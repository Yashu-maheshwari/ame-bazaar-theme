<?php
/**
 * OPCache Reset helper script for AME Bazaar.
 */
if ( function_exists( 'opcache_reset' ) ) {
	opcache_reset();
	echo 'OPCache Reset Successful!';
} else {
	echo 'OPCache not enabled or reset function missing.';
}
