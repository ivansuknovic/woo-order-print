<?php
/*
Template Name: Woo Order Print Template
*/

if ( ! current_user_can( 'edit_others_shop_orders' ) ) {
	echo "<p>Nothing to see here.</p>";

	return;
}

$order_id = get_query_var( 'woo_order_print_id', null );

if ( $order_id === null ) {
	echo "<p>Order #$order_id is not found</p>";

	return;
}

?>
    <html lang="en">
    <head>
        <style>
            body {
                font-family: Verdana, sans-serif;
            }

            a {
                color: black;
            }

            .custom-logo {
                max-height: 250px;
                max-width: 250px;
            }
        </style>
        <title>
			<?php
			echo "wc-order-$order_id";
			?>
        </title>
    </head>
    <body>
<?php

echo get_custom_logo();

$order = wc_get_order( $order_id );

if ( ! $order ) {
	echo "<p>Order #$order_id is not found</p>";

	return;
}

wc_get_template( 'emails/email-order-details.php', [ 'order' => $order, 'sent_to_admin' => true ] );
do_action( 'woocommerce_email_order_meta', $order );
wc_get_template( 'emails/email-addresses.php', [ 'order' => $order, ] );

echo "<script>window.print();</script>";
