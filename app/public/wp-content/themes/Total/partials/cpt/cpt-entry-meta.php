<?php
/**
 * CTP entry meta
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.4.2
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'partials/meta/meta', get_post_type(), [
    'singular' => false,
] );