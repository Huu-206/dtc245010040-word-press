<?php

function nem_xu_thanh_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'nem_xu_thanh_setup');

function nem_xu_thanh_assets() {
    wp_enqueue_style(
        'nem-xu-thanh-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'nem_xu_thanh_assets');

function nem_xu_thanh_products() {
    return [
        'goi-nho' => [
            'name' => 'Gói nếm thử',
            'quantity' => '10 chiếc',
            'price' => '82.000đ',
            'description' => 'Vừa đủ cho một buổi gặp gỡ thân mật.',
        ],
        'hop-qua' => [
            'name' => 'Hộp quà xứ Thanh',
            'quantity' => '20 chiếc',
            'price' => '155.000đ',
            'description' => 'Gọn gàng để mang theo hoặc biếu tặng.',
        ],
        'mam-co' => [
            'name' => 'Mâm vui sum họp',
            'quantity' => '50 chiếc',
            'price' => '360.000đ',
            'description' => 'Chia sẻ cùng gia đình, bạn bè và đồng nghiệp.',
        ],
    ];
}

function nem_xu_thanh_register_orders() {
    register_post_type('nem_order', [
        'labels' => [
            'name' => 'Đơn nem',
            'singular_name' => 'Yêu cầu đặt nem',
            'menu_name' => 'Đơn nem',
            'view_item' => 'Xem yêu cầu',
            'search_items' => 'Tìm yêu cầu',
            'not_found' => 'Chưa có yêu cầu nào',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-carrot',
        'supports' => ['title', 'editor'],
        'capability_type' => 'post',
        'map_meta_cap' => true,
    ]);
}
add_action('init', 'nem_xu_thanh_register_orders');

function nem_xu_thanh_order_redirect($result) {
    wp_safe_redirect(add_query_arg('dat-hang', $result, home_url('/#dat-hang')));
    exit;
}

function nem_xu_thanh_submit_order() {
    if (!isset($_POST['nem_order_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nem_order_nonce'])), 'nem_xu_thanh_order')) {
        wp_die('Phiên đặt hàng không hợp lệ. Vui lòng tải lại trang và thử lại.', 'Yêu cầu không hợp lệ', ['response' => 403]);
    }

    if (!empty($_POST['company_website'])) {
        nem_xu_thanh_order_redirect('received');
    }

    $name = sanitize_text_field(wp_unslash($_POST['customer_name'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['customer_phone'] ?? ''));
    $phone = preg_replace('/[^0-9+().\s-]/', '', $phone);
    $email = sanitize_email(wp_unslash($_POST['customer_email'] ?? ''));
    $product_key = sanitize_key(wp_unslash($_POST['product_package'] ?? ''));
    $quantity = absint($_POST['order_quantity'] ?? 1);
    $note = sanitize_textarea_field(wp_unslash($_POST['delivery_note'] ?? ''));
    $products = nem_xu_thanh_products();

    $consent = isset($_POST['privacy_consent']) && wp_unslash($_POST['privacy_consent']) === '1';

    if ($name === '' || !preg_match('/^[0-9+().\s-]{8,20}$/', $phone) || ($email !== '' && !is_email($email)) || !isset($products[$product_key]) || $quantity < 1 || $quantity > 50 || !$consent) {
        nem_xu_thanh_order_redirect('invalid');
    }

    $product = $products[$product_key];
    $content = implode("\n", [
        'Tên người nhận: ' . $name,
        'Số điện thoại: ' . $phone,
        'Email: ' . ($email ?: 'Không cung cấp'),
        'Sản phẩm: ' . $product['name'] . ' (' . $product['quantity'] . ')',
        'Số phần: ' . $quantity,
        'Ghi chú giao hàng: ' . ($note ?: 'Không có'),
        'Trạng thái: Chờ shop xác nhận. Chưa thanh toán.',
    ]);

    $order_id = wp_insert_post([
        'post_type' => 'nem_order',
        'post_status' => 'private',
        'post_title' => sprintf('Đơn nem - %s - %s', $name, current_time('Y-m-d H:i')),
        'post_content' => $content,
    ], true);

    if (is_wp_error($order_id)) {
        nem_xu_thanh_order_redirect('error');
    }

    update_post_meta($order_id, '_nem_customer_phone', $phone);
    update_post_meta($order_id, '_nem_product_package', $product_key);
    nem_xu_thanh_order_redirect('received');
}
add_action('admin_post_nopriv_nem_xu_thanh_order', 'nem_xu_thanh_submit_order');
add_action('admin_post_nem_xu_thanh_order', 'nem_xu_thanh_submit_order');