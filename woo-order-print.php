<?php

/**
 * Plugin Name: Woo Order Print
 * Plugin URI: https://github.com/ivansuknovic/woo-order-print
 * Description: WooCommerce plugin that allows you to print WooCommerce orders.
 * Version: 1.2
 *
 * Author: Ivan Suknovic
 * Author URI: https://github.com/ivansuknovic
 *
 * Developer: Ivan Suknovic
 * Developer URI: https://github.com/ivansuknovic
 *
 * Requires PHP: 7.2
 *
 * Requires at least: 5.7
 * Tested up to: 6.2
 *
 * WC requires at least: 7.0
 * WC tested up to: 7.6.1
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html≈
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
			$reordered_columns['woo-order-print'] = __('Print action', 'woo-order-print');
		}
	}
	
	return $reordered_columns;
}

add_filter('manage_edit-shop_order_columns', 'wop_order_print_action_column', 20);

function wop_order_print_action_field($column, $post_id): void
{
	if ($column == 'woo-order-print') {
		echo sprintf(
			'<a href="/woo-order-print?woo_order_print_id=%s" target="_blank">%s</a>',
			$post_id,
			__('Print', 'woo-order-print')
		);
	}
}

add_action('manage_shop_order_posts_custom_column', 'wop_order_print_action_field', 20, 2);
