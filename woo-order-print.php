<?php

/*
Plugin Name: Woo Order Print
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.1
Author: Ivan Suknovic
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

if ( ! defined('WPINC')) {
	die;
}

if ( ! defined('ABSPATH')) {
	exit;
}

function wop_rewrite_order_print_url(): void
{
	add_rewrite_rule('woo-order-print/([a-z]+)[/]?$', 'index.php?woo_order_print_id=$matches[1]', 'top');
}

add_action('init', 'wop_rewrite_order_print_url');

function wop_add_custom_query_var($vars)
{
	$vars[] = 'woo_order_print_id';
	
	return $vars;
}

add_filter('query_vars', 'wop_add_custom_query_var');

function wop_template_include($template)
{
	if ( ! get_query_var('woo_order_print_id') || get_query_var('woo_order_print_id') == '') {
		return $template;
	}
	
	return plugin_dir_path(__FILE__).'/woo-order-print-template.php';
}

add_action('template_include', 'wop_template_include');


function wop_order_print_action_column($columns): array
{
	$reordered_columns = [];
	
	foreach ($columns as $key => $column) {
		$reordered_columns[$key] = $column;
		if ($key == 'order_total') {
			$reordered_columns['woo-order-print'] = __('Print action', 'woo_order_print');
		}
	}
	
	return $reordered_columns;
}

add_filter('manage_edit-shop_order_columns', 'wop_order_print_action_column', 20);

function wop_order_print_action_field($column, $post_id): void
{
	if ($column == 'woo-order-print') {
		echo "<a href='/woo-order-print?woo_order_print_id=$post_id' target='_blank'>Print</a>";
	}
}

add_action('manage_shop_order_posts_custom_column', 'wop_order_print_action_field', 20, 2);
