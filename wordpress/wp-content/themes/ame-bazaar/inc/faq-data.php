<?php
/**
 * Centralized database of verified FAQs for AME Bazaar.
 * Mapped to Phase 30 requirements.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieve verified FAQs list.
 *
 * @return array List of FAQs with topics, questions, answers, and anchors.
 */
function ame_bazaar_get_verified_faqs() {
	$brand = ame_bazaar_get_brand_name();
	$phone = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
	$address = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road, near Chappan Bhog, Kirari Suleman Nagar, Delhi' ) . ' - ' . ame_bazaar_get_business_setting( 'postal_code', '110086' );
	$hours = ame_bazaar_get_business_setting( 'hours', 'Monday to Sunday: 09:00 AM - 10:00 PM' );
	
	return array(
		array(
			'topic' => 'Store',
			'q'     => sprintf( 'Where is the %s store located?', $brand ),
			'a'     => sprintf( 'Our physical showroom is located on %s. You can visit us to shop our entire catalog in person.', $address ),
			'id'    => 'store-location',
		),
		array(
			'topic' => 'Store Timings',
			'q'     => 'What are the store operating hours?',
			'a'     => sprintf( 'We are open daily. Our business hours are %s, including Sundays and public holidays.', $hours ),
			'id'    => 'store-timings',
		),
		array(
			'topic' => 'Products',
			'q'     => 'What categories of clothing do you sell?',
			'a'     => sprintf( 'We offer premium apparel for the whole family, including Men\'s Wear (shirts, t-shirts, jeans, trousers), Women\'s Wear (Mulmul kurtis, suit sets, palazzos), Kids Wear (boy, girl, and baby sets), Sarees, and fashion Accessories.', $brand ),
			'id'    => 'product-categories',
		),
		array(
			'topic' => 'Men\'s Wear',
			'q'     => 'What kind of fabrics do you use for Men\'s shirts?',
			'a'     => 'We prioritize high-comfort fabrics designed for Delhi\'s weather. Our Men\'s shirts are crafted from premium cotton, linen blends, and breathable pique cotton for polo t-shirts.',
			'id'    => 'mens-wear-fabrics',
		),
		array(
			'topic' => 'Women\'s Wear',
			'q'     => 'What are your specialty collections for women?',
			'a'     => 'Our core specialties include premium Mulmul Cotton Kurtis, designer Georgette sarees, and traditional Banarasi silk sarees, along with everyday matching leggings and palazzos.',
			'id'    => 'womens-wear-specialty',
		),
		array(
			'topic' => 'Kids Wear',
			'q'     => 'Do you sell clothes for infants and toddlers?',
			'a'     => 'Yes, our Kids Wear department includes unisex pure cotton rompers for babies, floral summer frocks for girls, and matching tee-and-shorts coordinates for boys.',
			'id'    => 'kids-wear-sizes',
		),
		array(
			'topic' => 'Tailoring',
			'q'     => 'Do you provide custom tailoring services?',
			'a'     => 'Yes. We provide bespoke measurement sizing and custom stitching for suits, blouses, and trousers at our Mubarakpur Road showroom.',
			'id'    => 'tailoring-services',
		),
		array(
			'topic' => 'Alteration',
			'q'     => 'Do you offer on-site fitting alterations?',
			'a'     => 'Yes. We offer quick 30-minute custom alterations for garments purchased at our store, ensuring your new apparel fits you perfectly before you leave.',
			'id'    => 'alterations-service',
		),
		array(
			'topic' => 'Payments',
			'q'     => 'What payment modes are accepted at the store?',
			'a'     => 'We accept cash, all major credit/debit cards, and instant UPI payments through Paytm, PhonePe, and Google Pay QR codes.',
			'id'    => 'payment-methods',
		),
		array(
			'topic' => 'Exchange',
			'q'     => 'What is your exchange and return policy?',
			'a'     => 'We offer a hassle-free 7-day exchange policy for unused, unwashed garments with tags intact. Please bring the original purchase bill to our Kirari showroom.',
			'id'    => 'exchange-policy',
		),
		array(
			'topic' => 'Parking',
			'q'     => 'Is customer parking available at the store?',
			'a'     => 'Yes. Dedicated two-wheeler parking is available directly outside the store entrance. Four-wheeler parking is available in designated bays along Mubarakpur Road.',
			'id'    => 'store-parking',
		),
		array(
			'topic' => 'Directions',
			'q'     => 'How do I reach the store from Rohini or Nangloi?',
			'a'     => 'We are situated on Mubarakpur Road in Kirari, near Chappan Bhog. You can take the metro to Rithala (Rohini) or Nangloi and take a local connecting auto directly to Chappan Bhog.',
			'id'    => 'directions-info',
		),
		array(
			'topic' => 'Pickup',
			'q'     => 'How does the free 2-hour store pickup work?',
			'a'     => 'You can order through WhatsApp or our digital catalog, and your items will be packed and ready for pickup at our Mubarakpur Road counter within 2 hours.',
			'id'    => 'store-pickup-process',
		),
		array(
			'topic' => 'Festival Shopping',
			'q'     => 'Do you launch special collections for festivals?',
			'a'     => 'Yes. We stock exclusive, curated ethnic wear (embroidered kurtis, Anarkali suits, and festive men\'s kurtas) ahead of major regional celebrations and festivals.',
			'id'    => 'festival-shopping',
		),
		array(
			'topic' => 'Wedding Shopping',
			'q'     => 'Can I get custom-tailored suits for wedding functions?',
			'a'     => 'Absolutely. We specialize in custom-fitting wedding wear, offering tailor-measured suit stitching and Banarasi silk saree pleating adjustments for families.',
			'id'    => 'wedding-shopping',
		),
		array(
			'topic' => 'Local Shopping',
			'q'     => 'Which local areas do you serve in North-West Delhi?',
			'a'     => 'We are the preferred family fashion hub for residents of Kirari, Mubarakpur, Meer Vihar, Baljit Vihar, Prem Nagar, Nangloi, Budh Vihar, and Rohini.',
			'id'    => 'local-areas-served',
		),
	);
}
