<?php
/**
 * Template Name: Menu
 *
 * Static menu board matching the printed design. Content lives in the
 * $menu_sections array below rather than the database, so items and prices are
 * edited here.
 *
 * Each section takes: title, subtitle, image slug (assets/images/menu/<slug>.png),
 * an optional accent flag for the gold rule under the heading, and items given
 * as array( name, price ) or array( name, price, note ).
 *
 * The Order button is per section. 'brand' is a tf_brand slug and sends the
 * button to that brand page. To point a section somewhere else instead, set
 * 'order_url' (which wins over 'brand'), adding 'new_tab' => true if it leaves
 * the site. 'order_label' overrides the button wording.
 *
 * @package Tutti_Frutti_Cafe
 */

/*
 * Order buttons point at the matching brand page. Resolved from the tf_brand
 * post so the real permalink is used, falling back to /brand/<slug>/ when a
 * brand has not been published yet.
 */
$menu_brand_urls = array();
foreach ( array( 'tutti-frutti', 'pio-coffee', 'my-cookies', 'tf-bites' ) as $menu_brand_slug ) {
	$menu_brand_post = get_page_by_path( $menu_brand_slug, OBJECT, 'tf_brand' );

	$menu_brand_urls[ $menu_brand_slug ] = ( $menu_brand_post && 'publish' === $menu_brand_post->post_status )
		? get_permalink( $menu_brand_post )
		: home_url( '/brand/' . $menu_brand_slug . '/' );
}

$menu_img_base  = get_template_directory_uri() . '/assets/images/menu/';
$menu_img_dir   = get_template_directory() . '/assets/images/menu/';

$menu_sections = array(
	array(
		'title'    => 'Entr&eacute;es &amp; Meals',
		'subtitle' => 'Sandwiches, Pasta &amp; More',
		'image'    => 'entrees',
		'brand'    => 'tf-bites',
		'items'    => array(
			array( 'Spicy Chicken Sandwich', '13.99' ),
			array( 'Classic Chicken Bun', '10.99' ),
            array( 'Deli Sandwich - Pastrami', '13.99' ),
            array( 'Deli Sandwich - Turkey', '13.99' ),
            array( 'Deli Sandwich - Roast Beef', '13.99' ),
			array( 'Cheese Ravioli Marinara', '12.99' ),
			array( 'Penne Pasta Alfredo', '11.99' ),
			array( 'Chicken Parmesan', '13.99' ),
		),
	),
	array(
		'title'    => 'Sides',
		'subtitle' => 'The Perfect Add-Ons',
		'image'    => 'sides',
		'brand'    => 'tf-bites',
		'items'    => array(
			array( 'Waffle Fries', '3.99' ),
			array( 'Chicken Nuggets (5 pcs)', '4.99' ),
			array( 'Garlic Bread (3 pcs)', '3.99' ),
			array( 'Mac &amp; Cheese', '4.99' ),
			array( 'Chicken Nuggets (Kid\'s Meal)', '5.99' ),
		),
	),
	array(
		'title'    => 'Snacks &amp; Bites',
		'subtitle' => 'Twisted Favorites',
		'image'    => 'snacks',
		'brand'    => 'tf-bites',
		'items'    => array(
			array( 'Pretzel with Cheese', '7.99' ),
			array( 'Spicy Pretzel Bun', '7.99' ),
			array( 'Pickled Bombs', '6.99' ),
			array( 'Loaded Tots', '6.99' ),
            array( 'Cheese Sticks (5 pcs)', '7.99' ),
		),
	),
	array(
		'title'    => 'Gourmet Cookies',
		'subtitle' => 'Freshly Baked Happiness',
		'image'    => 'cookies',
		'brand'    => 'my-cookies',
		'items'    => array(
			array( 'Two Chip', '6.49' ),
			array( 'Chocolate Chip Walnut', '6.49' ),
			array( 'Choco Chocolate Chip', '6.49' ),
			array( 'Peanut Butter Chocolate Chip', '6.49' ),
			array( 'Oatmeal Coconut', '6.49' ),
			array( 'Oatmeal Raisin', '6.49' ),
            array( '2 Cookie Deal', '11.49' ),
            array('4 Pack Cookie Box', '21.99' ),
		),
	),
	array(
		'title'    => 'Desserts',
		'subtitle' => 'Sweet Treats For Every Mood',
		'image'    => 'desserts',
		'brand'    => 'my-cookies',
		'accent'   => true,
		'items'    => array(
			array( 'Carrot Cake Bar', '7.99' ),
			array( 'Lemon Bar', '6.99' ),
			array( 'Waffle (Sugar or Nutella)', '4.99' ),
			array( 'Cheesecake Slice', '7.99' ),
			array( 'Caramel Pecan Bar', '6.99' ),
			array( 'Tres Leches (Pistachio)', '7.99' ),
			array( 'Truffle Brownie Bar', '6.99' ),
			array( 'Mini Cream Puffs (5 pcs)', '7.99' ),
            array( 'Muffin - Classico', '4.99' ),
            array( 'Muffin - Cacao', '4.99' ),
            array( 'Muffin - Pistachio', '4.99' ),
		),
	),
	array(
		'title'    => 'Smoothies &amp; Refreshers',
		'subtitle' => 'Real Fruit Real Good',
		'image'    => 'smoothies',
		'brand'    => 'tutti-frutti',
		'items'    => array(
			array( 'Tutti Frutti Smoothies', '7.49 | 8.99' ),
			array( 'Aloha Pineapple', '7.49 | 8.99' ),
			array( 'Mango Sunrise', '7.49 | 8.99' ),
			array( 'Strawberry Banana', '7.49 | 8.99' ),
            array( 'Green Machine', '7.49 | 8.99' ),
			array( 'Refresher', '4.99 | 6.99' ),
			array( 'Iced Matcha', '6.99 | 8.99' ),
		),
	),
	array(
		'title'    => 'Coffee &amp; Chai',
		'subtitle' => 'Hot &amp; Iced Coffee Specialty Drinks',
		'image'    => 'coffee',
		'brand'    => 'pio-coffee',
		'items'    => array(
			array( 'Americano', '4.49 | 5.99' ),
			array( 'Classic Mocha', '6.49 | 7.99' ),
			array( 'Vanilla Bean Latte', '6.49 | 7.99' ),
			array( 'Maple Chai', '6.49 | 7.99' ),
			array( 'Blended Coffee', '6.49 | 7.99' ),
			array( 'Tutti Frutti Special', '5.99 | 7.49' ),
		),
	),
	array(
		'title'    => 'Frozen Yogurt',
		'subtitle' => 'Create Your Perfect Cup',
		'image'    => 'frozen-yogurt',
		'brand'    => 'tutti-frutti',
		'accent'   => true,
		'items'    => array(
			array( 'Frozen Yogurt', '9.49 | 12.49', 'with your choice of toppings' ),
			array( 'A La Mode', '11.99' ),
		),
	),
	array(
		'title'    => 'A&ccedil;a&iacute; Bowls',
		'subtitle' => 'Nourish Your Day',
		'image'    => 'acai',
		'brand'    => 'tutti-frutti',
		'accent'   => true,
		'items'    => array(
			array( 'Build Your Own Bowl', '13.49', 'with your favorite toppings' ),
		),
	),
    array(
		'title'    => 'Beverages',
		'subtitle' => 'Water, Milk Chocolate &amp; Soft Drinks',
		'image'    => 'beverages',
		'brand'    => '',
		'accent'   => true,
		'items'    => array(
			array( 'Soda', '2.50' ),
            array( 'Tractor', '4.99' ),
            array( 'Organic Chocolate Milk', '3.99' ),
            array( 'Organic Low-Fat Milk', '3.99' ),
            array( 'Coconut Water', '2.49' ),
			array( 'Water', '1.99 | 3.99' ),
		),
	),
    array(
		'title'    => 'Specialty Energy Drinks',
		'subtitle' => 'Fuel your day',
		'image'    => 'energy-drinks',
		'brand'    => '',
		'accent'   => true,
		'items'    => array(
			array( 'Protein Shake', '4.49' ),
            array( 'Celcius', '4.49' ),
            array( 'Redbull', '4.49' ),
		),
	),
);

$menu_hero = function_exists( 'tutti_frutti_get_page_hero' )
	? tutti_frutti_get_page_hero()
	: array( 'desc' => '', 'buttons' => array() );

get_header();
?>

<main id="primary" class="site-main page-menu site-main--page">
	<section class="page-hero page-hero--brown page-section--top">
		<div class="container page-hero__inner">
			<h1 class="page-hero__title"><?php the_title(); ?></h1>

			<?php if ( $menu_hero['desc'] ) : ?>
				<p class="page-hero__desc"><?php echo esc_html( $menu_hero['desc'] ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $menu_hero['buttons'] ) ) : ?>
				<div class="page-hero__actions">
					<?php foreach ( $menu_hero['buttons'] as $btn ) : ?>
						<a href="<?php echo esc_url( $btn['url'] ); ?>" class="<?php echo esc_attr( $btn['class'] ); ?>"<?php echo $btn['new_tab'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $btn['text'] ); ?><?php if ( $btn['new_tab'] ) : ?><span class="screen-reader-text"><?php esc_html_e( ' (opens in a new tab)', 'tutti-frutti-cafe' ); ?></span><?php endif; ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<div class="menu-board">
		<?php foreach ( $menu_sections as $section ) : ?>
			<?php
			$has_img = file_exists( $menu_img_dir . $section['image'] . '.png' );
			$accent  = ! empty( $section['accent'] );
			?>
			<article class="menu-row<?php echo $has_img ? '' : ' menu-row--no-media'; ?>">
				<div class="menu-row__head">
					<h2 class="menu-row__title<?php echo $accent ? ' menu-row__title--accent' : ''; ?>"><?php echo wp_kses_post( $section['title'] ); ?></h2>
					<?php if ( ! empty( $section['subtitle'] ) ) : ?>
						<p class="menu-row__subtitle"><?php echo wp_kses_post( $section['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $has_img ) : ?>
					<div class="menu-row__media">
						<img src="<?php echo esc_url( $menu_img_base . $section['image'] . '.png' ); ?>"
							alt="<?php echo esc_attr( wp_strip_all_tags( $section['title'] ) ); ?>"
							loading="lazy" decoding="async">
					</div>
				<?php endif; ?>

				<ul class="menu-row__items">
					<?php foreach ( $section['items'] as $item ) : ?>
						<li class="menu-item">
							<span class="menu-item__name">
								<?php echo wp_kses_post( $item[0] ); ?>
								<?php if ( ! empty( $item[2] ) ) : ?>
									<span class="menu-item__note">(<?php echo esc_html( $item[2] ); ?>)</span>
								<?php endif; ?>
							</span>
							<span class="menu-item__price"><?php echo esc_html( $item[1] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>

				<?php
				$order_url = ! empty( $section['order_url'] ) ? $section['order_url'] : '';
				if ( ! $order_url && ! empty( $section['brand'] ) && isset( $menu_brand_urls[ $section['brand'] ] ) ) {
					$order_url = $menu_brand_urls[ $section['brand'] ];
				}
				$order_label   = ! empty( $section['order_label'] ) ? $section['order_label'] : __( 'Order Now', 'tutti-frutti-cafe' );
				$order_new_tab = ! empty( $section['new_tab'] );
				?>
				<div class="menu-row__cta">
					<?php if ( $order_url ) : ?>
						<a href="<?php echo esc_url( $order_url ); ?>" class="menu-order-btn"<?php echo $order_new_tab ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<?php echo esc_html( $order_label ); ?>
							<span class="menu-order-btn__chev" aria-hidden="true"></span>
							<?php if ( $order_new_tab ) : ?>
								<span class="screen-reader-text"><?php esc_html_e( ' (opens in a new tab)', 'tutti-frutti-cafe' ); ?></span>
							<?php endif; ?>
						</a>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>

		<p class="menu-board__tagline">
			<span class="menu-board__rule" aria-hidden="true"></span>
			<span class="menu-board__tagline-text">Good Food &nbsp;&middot;&nbsp; Brighter Moods</span>
			<span class="menu-board__rule" aria-hidden="true"></span>
		</p>
	</div>

	<?php get_template_part( 'template-parts/page-editable-content' ); ?>
</main>

<?php get_footer(); ?>
