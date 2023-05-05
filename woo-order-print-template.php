<?php
/*
Template Name: Woo Order Print Template
*/

?>
	<html>
	<head>
		<style>
        body {
            font-family: Verdana, sans-serif;
        }
		</style>
	</head>
	<body>
<?php

$order_id = get_query_var('woo_order_print_id', null);

if ($order_id === null) {
	echo "<p>Order #$order_id is not found</p>";
	
	return;
}

$order = wc_get_order($order_id);

if ( ! $order) {
	echo "<p>Order #$order_id is not found</p>";
	
	return;
}

wc_get_template('emails/email-order-details.php', ['order' => $order]);
do_action('woocommerce_email_order_meta', $order);
wc_get_template('emails/email-addresses.php', ['order' => $order,]);

echo "<script>window.print();</script>";
