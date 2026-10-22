<?php
$products = nem_xu_thanh_products();
$order_status = sanitize_key(wp_unslash($_GET['dat-hang'] ?? ''));
$image_url = get_theme_file_uri('assets/nem-chua.jpg');
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#noi-dung">Bỏ qua điều hướng</a>

<header class="site-header">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Nem Xứ Thanh, về đầu trang">
        <span class="brand-mark" aria-hidden="true">XT</span>
        <span>Nem Xứ Thanh</span>
    </a>
    <nav class="primary-nav" aria-label="Điều hướng chính">
        <a href="#cau-chuyen">Câu chuyện</a>
        <a href="#quy-cach">Quy cách</a>
        <a href="#thuong-thuc">Thưởng thức</a>
        <a class="nav-order" href="#dat-hang">Đặt yêu cầu</a>
    </nav>
</header>

<main id="noi-dung">
    <section class="hero" aria-labelledby="hero-title">
        <img class="hero-photo" src="<?php echo esc_url($image_url); ?>" alt="Nem chua Việt Nam ăn cùng tỏi và ớt" fetchpriority="high">
        <div class="hero-shade" aria-hidden="true"></div>
        <div class="hero-content">
            <p class="eyebrow">Đặc sản Thanh Hóa</p>
            <h1 id="hero-title">Gói trọn<br>vị <span>xứ Thanh.</span></h1>
            <p class="hero-copy">Nem chua mộc mạc, chua dịu, thơm tỏi ớt. Một thức quà quê để nhớ, để chia sẻ, để câu chuyện trên mâm cơm thêm rôm rả.</p>
            <div class="hero-actions">
                <a class="button" href="#quy-cach">Chọn set nem</a>
                <a class="button button-light" href="#cau-chuyen">Khám phá hương vị</a>
            </div>
            <p class="hero-note">Thông tin giá và quy cách hiện là nội dung mẫu cho website demo.</p>
        </div>
    </section>

    <section class="section-wrap" id="quy-cach" aria-labelledby="product-title">
        <div class="intro-row">
            <div>
                <p class="section-kicker">Chọn theo cuộc vui</p>
                <h2 id="product-title">Một vị quê,<br>ba cách sẻ chia.</h2>
            </div>
            <p class="intro-copy">Từ phần nếm thử cho buổi gặp thân tình đến hộp quà gửi người xa quê. Chọn quy cách phù hợp, shop sẽ xác nhận lại trước khi giao.</p>
        </div>
        <div class="product-grid">
            <?php $number = 1; foreach ($products as $key => $product) : ?>
                <article class="product-item">
                    <span class="product-number">0<?php echo esc_html((string) $number); ?></span>
                    <h3><?php echo esc_html($product['name']); ?></h3>
                    <p><?php echo esc_html($product['description']); ?></p>
                    <div class="product-bottom">
                        <div class="product-price"><?php echo esc_html($product['price']); ?><small><?php echo esc_html($product['quantity']); ?> · giá demo</small></div>
                        <a class="product-link" href="#dat-hang" data-package="<?php echo esc_attr($key); ?>">Chọn phần <span aria-hidden="true">↗</span></a>
                    </div>
                </article>
            <?php $number++; endforeach; ?>
        </div>
        <p class="price-note">Giá, thành phần và quy cách là dữ liệu minh họa. Vui lòng thay bằng thông tin sản phẩm thực tế trước khi nhận đơn.</p>
    </section>

    <section class="story-band" id="cau-chuyen" aria-labelledby="story-title">
        <div class="section-wrap story">
            <div>
                <p class="section-kicker">Từ miền đất nắng gió</p>
                <h2 id="story-title">Vị chua dịu.<br>Chút cay nồng.<br>Nhớ rất lâu.</h2>
            </div>
            <div class="story-copy">
                <p>Nem chua Thanh Hóa thường được nhắc đến bởi vị lên men hài hòa, chút thơm của tỏi và độ cay của ớt. Mỗi nhà có một cách nêm nếm, gói ghém riêng.</p>
                <p>Trang này là bản giới thiệu mẫu. Chủ cơ sở nên cập nhật công thức, thành phần, nguồn nguyên liệu, hạn dùng và hướng dẫn bảo quản đúng theo nhãn hàng thực tế.</p>
                <div class="story-points">
                    <div class="story-point"><strong>Chua thanh</strong><span>Vị lên men đặc trưng</span></div>
                    <div class="story-point"><strong>Tỏi · ớt</strong><span>Hương vị quen thuộc</span></div>
                    <div class="story-point"><strong>Quà quê</strong><span>Dễ chia sẻ, dễ nhớ</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-wrap serve-section" id="thuong-thuc" aria-labelledby="serve-title">
        <div class="serve-head">
            <div>
                <p class="section-kicker">Thưởng thức & bảo quản</p>
                <h2 id="serve-title">Ngon hơn khi dùng đúng cách.</h2>
            </div>
            <p>Luôn ưu tiên hướng dẫn trên bao bì của nhà sản xuất và kiểm tra tình trạng sản phẩm trước khi dùng.</p>
        </div>
        <div class="serve-grid">
            <article class="serve-step"><span>01 / Mở gói</span><h3>Dùng trong hạn</h3><p>Kiểm tra ngày sản xuất, hạn sử dụng và tình trạng bao bì trước khi thưởng thức.</p></article>
            <article class="serve-step"><span>02 / Bày mâm</span><h3>Ăn cùng rau thơm</h3><p>Có thể dùng kèm lá đinh lăng, tỏi hoặc ớt nếu phù hợp khẩu vị.</p></article>
            <article class="serve-step"><span>03 / Cất giữ</span><h3>Theo nhãn sản phẩm</h3><p>Bảo quản theo hướng dẫn của cơ sở sản xuất; không dùng nếu có dấu hiệu bất thường.</p></article>
        </div>
    </section>

    <section class="order-band" id="dat-hang" aria-labelledby="order-title">
        <div class="section-wrap order-layout">
            <div class="order-copy">
                <p class="section-kicker">Gửi lời nhắn cho shop</p>
                <h2 id="order-title">Để dành một phần vị quê.</h2>
                <p>Gửi thông tin để shop liên hệ xác nhận quy cách, giá thực tế và thời gian giao. Đây là yêu cầu đặt hàng demo, chưa phát sinh thanh toán.</p>
                <p class="order-aside">Sau khi gửi, quản trị viên xem yêu cầu tại <strong>wp-admin → Đơn nem</strong>.</p>
            </div>
            <form class="order-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php if ($order_status === 'received') : ?>
                    <p class="form-message" role="status">Đã nhận lời nhắn. Shop sẽ xác nhận lại thông tin trước khi chốt đơn.</p>
                <?php elseif ($order_status === 'invalid' || $order_status === 'error') : ?>
                    <p class="form-message error" role="alert">Thông tin chưa hợp lệ hoặc chưa lưu được. Vui lòng kiểm tra tên, số điện thoại và thử lại.</p>
                <?php endif; ?>
                <input type="hidden" name="action" value="nem_xu_thanh_order">
                <?php wp_nonce_field('nem_xu_thanh_order', 'nem_order_nonce'); ?>
                <div class="honeypot" aria-hidden="true"><label for="company-website">Website</label><input id="company-website" name="company_website" tabindex="-1" autocomplete="off"></div>
                <div class="form-field"><label for="customer-name">Tên người nhận *</label><input id="customer-name" name="customer_name" type="text" autocomplete="name" required maxlength="100"></div>
                <div class="form-field"><label for="customer-phone">Số điện thoại *</label><input id="customer-phone" name="customer_phone" type="tel" autocomplete="tel" required minlength="8" maxlength="20"></div>
                <div class="form-field"><label for="customer-email">Email (không bắt buộc)</label><input id="customer-email" name="customer_email" type="email" autocomplete="email" maxlength="150"></div>
                <div class="form-field"><label for="product-package">Quy cách *</label><select id="product-package" name="product_package" required><?php foreach ($products as $key => $product) : ?><option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($product['name'] . ' · ' . $product['quantity']); ?></option><?php endforeach; ?></select></div>
                <div class="form-field"><label for="order-quantity">Số phần *</label><input id="order-quantity" name="order_quantity" type="number" min="1" max="50" value="1" required></div>
                <div class="form-field form-wide"><label for="delivery-note">Địa chỉ hoặc lời nhắn</label><textarea id="delivery-note" name="delivery_note" maxlength="1000" placeholder="Khu vực nhận hàng, thời gian thuận tiện..."></textarea></div>
                <label class="form-consent form-wide"><input name="privacy_consent" type="checkbox" value="1" required><span>Tôi đồng ý để shop dùng thông tin này để liên hệ xác nhận yêu cầu.</span></label>
                <button class="button form-submit" type="submit">Gửi yêu cầu <span aria-hidden="true">↗</span></button>
            </form>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">Nem Xứ Thanh</div>
        <p class="footer-credit">Ảnh nem chua: <a href="https://commons.wikimedia.org/wiki/File:Nem_chua.jpg" target="_blank" rel="noopener noreferrer">Ron Diggity</a>, <a href="https://creativecommons.org/licenses/by/2.0/" target="_blank" rel="noopener noreferrer">CC BY 2.0</a> (đã cắt khung hiển thị).</p>
    </div>
    <p class="copyright">Website giới thiệu sản phẩm mẫu · Giá và thông tin cần được chủ cơ sở xác nhận trước khi sử dụng thực tế.</p>
</footer>

<script>
document.querySelectorAll('[data-package]').forEach(function (link) {
    link.addEventListener('click', function () {
        var packageSelect = document.getElementById('product-package');
        if (packageSelect) packageSelect.value = link.dataset.package;
    });
});
</script>
<?php wp_footer(); ?>
</body>
</html>