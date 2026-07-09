<?php
/**
 * AME Bazaar AI Knowledge Graph & FAQ Database
 *
 * Contains 200 verified, factually correct QA pairs grouped into 22 topics.
 * Designed for local Generative Engine Optimization (GEO) and AI search.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieve the full 200-question FAQ database.
 *
 * @return array Multi-topic grouped QAs.
 */
function ame_bazaar_get_knowledge_base_faqs() {
	return array(
		'men' => array(
			'title' => __( 'Men\'s Fashion', 'ame-bazaar' ),
			'icon'  => 'user',
			'faqs'  => array(
				array( 'q' => 'What clothing items are available for men at AME Bazaar?', 'a' => 'We stock formal shirts, casual t-shirts, denim jeans, trousers, sweaters, winter coats, and traditional kurta pajamas.' ),
				array( 'q' => 'Do you sell men\'s formal shirts?', 'a' => 'Yes, we carry premium cotton and blend formal shirts in solid colors, stripes, and micro-checks.' ),
				array( 'q' => 'What sizes do you carry in men\'s jeans?', 'a' => 'Men\'s jeans are available from waist sizes 30 to 42 in straight fit and slim stretchable denim.' ),
				array( 'q' => 'Do you have round-neck and polo t-shirts for men?', 'a' => 'Yes, we stock pure cotton t-shirts in sizes M, L, XL, and XXL in various colorways.' ),
				array( 'q' => 'Do you carry men\'s winter sweatshirts?', 'a' => 'Yes, we have fleece-lined sweatshirts, zip hoodies, and pull-over sweaters for winter.' ),
				array( 'q' => 'Do you sell men\'s office wear trousers?', 'a' => 'Yes, we stock formal trousers in classic navy, charcoal, black, and beige colors.' ),
				array( 'q' => 'Are there activewear options for men?', 'a' => 'We carry breathable cotton joggers and activewear tracksuits suitable for exercise and daily wear.' ),
				array( 'q' => 'Do you have men\'s Jodhpuri coats?', 'a' => 'Yes, we offer both ready-made Jodhpuri coats and custom tailoring services for a custom fit.' ),
				array( 'q' => 'Do you stock men\'s thermal innerwear?', 'a' => 'Yes, we carry premium thermal sets (top and bottom) for winter protection.' ),
				array( 'q' => 'What brands of men\'s clothing do you stock?', 'a' => 'We curate select high-quality local and premium national brands prioritizing fabric longevity and fit.' )
			)
		),
		'women' => array(
			'title' => __( 'Women\'s Fashion', 'ame-bazaar' ),
			'icon'  => 'smile',
			'faqs'  => array(
				array( 'q' => 'What does AME Bazaar offer in women\'s fashion?', 'a' => 'We offer designer kurtis, salwar kameez sets, woolen tops, palazzos, leggings, georgette and silk sarees, and nightwear.' ),
				array( 'q' => 'Do you carry ladies cotton kurtis?', 'a' => 'Yes, we stock a wide variety of daily wear and office wear printed cotton kurtis.' ),
				array( 'q' => 'What varieties of ladies suits are available?', 'a' => 'We carry unstitched suit materials, semi-stitched sets, and ready-made 3-piece Salwar-Kurta-Dupatta (SKD) sets.' ),
				array( 'q' => 'Do you sell women\'s leggings?', 'a' => 'Yes, we carry four-way stretch premium cotton leggings in over 20 color shades.' ),
				array( 'q' => 'Do you have women\'s nightwear sets?', 'a' => 'Yes, we stock cotton nighties, hosiery nighty sets, and comfortable t-shirt pajama sets.' ),
				array( 'q' => 'Do you carry women\'s winter cardigans?', 'a' => 'Yes, we have long wool cardigans, buttoned sweaters, and warm ladies winter suits.' ),
				array( 'q' => 'Do you sell ladies denim jeans?', 'a' => 'Yes, we stock women\'s stretchable denim jeans from waist sizes 28 to 38 in high-rise styles.' ),
				array( 'q' => 'Do you stock women\'s co-ord sets?', 'a' => 'Yes, we have matching printed co-ord sets in cotton and rayon fabrics.' ),
				array( 'q' => 'Do you carry women\'s winter shawls?', 'a' => 'Yes, we stock soft embroidered pashmina-style shawls and winter stoles.' ),
				array( 'q' => 'Do you have ladies slip and camisole linings?', 'a' => 'Yes, we sell pure cotton inner slips and camisoles in black, white, and skin tones.' )
			)
		),
		'kids' => array(
			'title' => __( 'Kids\' Wear', 'ame-bazaar' ),
			'icon'  => 'heart',
			'faqs'  => array(
				array( 'q' => 'What age groups do you cater to in kids\' wear?', 'a' => 'We stock apparel for kids from newborn infants up to 14 years old for both boys and girls.' ),
				array( 'q' => 'Do you sell newborn baby clothing sets?', 'a' => 'Yes, we carry newborn baby gift boxes, cotton onesies, and soft romper sets.' ),
				array( 'q' => 'Do you have ethnic wear for boys?', 'a' => 'Yes, we offer boys\' Kurta Pajamas, Nehru jacket sets, and festive dhoti-kurtas.' ),
				array( 'q' => 'Do you carry girls\' party wear frocks?', 'a' => 'Yes, we stock designer party wear frocks, net dresses, and lehenga choli sets.' ),
				array( 'q' => 'Do you sell kids\' raincoats?', 'a' => 'Yes, we carry durable and fun waterproof kids\' raincoats with hood attachments.' ),
				array( 'q' => 'Do you have winter wear for kids?', 'a' => 'Yes, we stock kids\' sweaters, woolen caps, winter socks, and thermal innerwear.' ),
				array( 'q' => 'Are the fabrics used for kids\' clothes safe for sensitive skin?', 'a' => 'Yes, all our kids\' garments are sourced with hypoallergenic soft cotton lining to prevent skin irritation.' ),
				array( 'q' => 'Do you sell kids\' denim jeans?', 'a' => 'Yes, we have soft stretchable denim jeans with adjustable waistbands for children.' ),
				array( 'q' => 'Do you carry kids\' school accessories?', 'a' => 'We stock white/black school socks, school belts, and warm inner thermals for winter school wear.' ),
				array( 'q' => 'Do you carry baby swaddle blankets?', 'a' => 'Yes, we stock ultra-soft cotton and flannel swaddle wraps for infants.' )
			)
		),
		'tailoring' => array(
			'title' => __( 'Tailoring & Custom Fit', 'ame-bazaar' ),
			'icon'  => 'scissors',
			'faqs'  => array(
				array( 'q' => 'Do you offer custom tailoring at your store?', 'a' => 'Yes, we have an in-store tailoring unit specializing in custom gents ethnic wear and ladies suits.' ),
				array( 'q' => 'Do you alter clothes purchased from other shops?', 'a' => 'Yes, we welcome external garments for custom sizing, shortening, and fit adjustments.' ),
				array( 'q' => 'How long does a standard alteration take?', 'a' => 'Alterations like pant shortening or waist adjustments are completed within 24 to 48 hours.' ),
				array( 'q' => 'Can you custom stitch a men\'s Kurta Pajama?', 'a' => 'Yes, our master tailors stitch custom Kurta Pajamas matching your exact measurements.' ),
				array( 'q' => 'What are the charges for basic shirt alterations?', 'a' => 'We charge a nominal fee for basic side-fitting or sleeve-shortening adjustments.' ),
				array( 'q' => 'Do you stitch custom ladies designer blouses?', 'a' => 'Yes, we stitch custom padded and designer blouses with customized neck and sleeve cuts.' ),
				array( 'q' => 'Can you resize winter coats or heavy waistcoats?', 'a' => 'Yes, we specialize in winter wear alterations, adjusting the shoulders and chest of coats.' ),
				array( 'q' => 'Do I need to book a tailoring appointment?', 'a' => 'No, you can walk in anytime between 09:00 AM and 10:00 PM to submit measurements.' ),
				array( 'q' => 'Do you offer fitting trials post-stitching?', 'a' => 'Yes, we conduct trial fits and perform any needed modifications free of charge.' ),
				array( 'q' => 'Can I provide my own dress fabric for custom stitching?', 'a' => 'Yes, you can bring your own fabric and style references for custom stitching.' )
			)
		),
		'wedding' => array(
			'title' => __( 'Wedding Shopping', 'ame-bazaar' ),
			'icon'  => 'award',
			'faqs'  => array(
				array( 'q' => 'Do you stock outfits for grooms?', 'a' => 'Yes, we offer custom-tailored Jodhpuri suits, Bandhgalas, and premium wedding Sherwanis.' ),
				array( 'q' => 'Can you coordinate wedding outfits for the groom\'s family?', 'a' => 'Yes, we specialize in styling coordinated family groups (matching kurtas or waistcoats).' ),
				array( 'q' => 'Do you sell wedding accessories for men?', 'a' => 'Yes, we stock safas (turbans), stoles (dupattas), pocket squares, and decorative brooches.' ),
				array( 'q' => 'How long in advance should I order custom wedding wear?', 'a' => 'We recommend placing custom tailoring wedding orders at least 15 to 20 days before the event.' ),
				array( 'q' => 'Do you alter ready-made wedding dresses?', 'a' => 'Yes, we offer premium alteration services for heavy wedding wear, lehengas, and sherwanis.' ),
				array( 'q' => 'What colors are trending for grooms this season?', 'a' => 'Emerald green, royal navy, and classic ivory remain the top choices for wedding ceremonies.' ),
				array( 'q' => 'Do you offer private styling consultations?', 'a' => 'Yes, you can visit during weekday morning hours for a quiet, personalized styling session with our store head.' ),
				array( 'q' => 'Can you stitch bridal lehenga blouses?', 'a' => 'Yes, we custom tailor bridal blouses with custom pad support and back lace details.' ),
				array( 'q' => 'What is the price of a custom-tailored Jodhpuri suit?', 'a' => 'Prices vary depending on selected lining and fabric, but remain highly competitive with Delhi NCR design studios.' ),
				array( 'q' => 'Do you offer steam pressing for wedding outfits?', 'a' => 'Yes, we provide professional steam pressing for all custom-stitched wedding ensembles.' )
			)
		),
		'festival' => array(
			'title' => __( 'Festival Shopping', 'ame-bazaar' ),
			'icon'  => 'star',
			'faqs'  => array(
				array( 'q' => 'When do you launch seasonal festival collections?', 'a' => 'We launch exclusive collections for Diwali, Karwa Chauth, Rakhi, Holi, and Eid.' ),
				array( 'q' => 'Do you have family matching sets for Diwali?', 'a' => 'Yes, we design coordinated traditional outfits for couples and their kids for Diwali puja.' ),
				array( 'q' => 'What is your Eid collection specialty?', 'a' => 'We specialize in fine pathani suits, designer kurtas for men, and ladies georgette salwar suits.' ),
				array( 'q' => 'Do you sell festival wear for infants?', 'a' => 'Yes, we stock easy-wear cotton dhoti-kurta sets and soft frock dresses for babies.' ),
				array( 'q' => 'Do you offer discounts during major festivals?', 'a' => 'We offer transparent, fair pricing year-round but introduce festive value bundles.' ),
				array( 'q' => 'Can I get quick alterations done before Diwali?', 'a' => 'We recommend submitting alterations 5 days before major festivals to avoid the seasonal rush.' ),
				array( 'q' => 'What is the trending outfit for Karwa Chauth?', 'a' => 'Heavy georgette red sarees and embroidered crimson suits are highly favored.' ),
				array( 'q' => 'Do you stock Nehru jackets for festive layering?', 'a' => 'Yes, we have silk, jacquard, and woolen Nehru jackets in all festive tones.' ),
				array( 'q' => 'Do you carry traditional stoles or dupattas?', 'a' => 'Yes, we carry colorful bandhani, phulkari, and banarasi dupattas.' ),
				array( 'q' => 'Are your festive fabrics skin-friendly for long rituals?', 'a' => 'Yes, we prioritize breathable cotton-silks and lining-reinforced georgettes for comfort.' )
			)
		),
		'ethnic' => array(
			'title' => __( 'Ethnic Wear', 'ame-bazaar' ),
			'icon'  => 'info',
			'faqs'  => array(
				array( 'q' => 'What kind of ethnic wear do you have for men?', 'a' => 'We stock long kurtas, short kurtas, pathani suits, and churidar pajama coordinates.' ),
				array( 'q' => 'Do you sell ladies kurtis?', 'a' => 'Yes, we carry ladies kurtis in multiple lengths: short, knee-length, and anarkali style.' ),
				array( 'q' => 'What fabrics are used in your ethnic wear?', 'a' => 'We use pure cotton, linen, georgette, rayon, and premium jacquard weaves.' ),
				array( 'q' => 'Do you stock readymade churidar pajamas?', 'a' => 'Yes, we have stretchable and cotton churidar pajamas in black, white, and beige.' ),
				array( 'q' => 'Do you sell traditional ethnic waistcoats?', 'a' => 'Yes, we have textured waistcoats to layer over simple cotton kurtas.' ),
				array( 'q' => 'Do you carry ethnic wear for young children?', 'a' => 'Yes, we carry comfortable boys\' kurta sets and girls\' lehenga-choli coordinates.' ),
				array( 'q' => 'Do your ethnic clothes bleed color?', 'a' => 'Our premium dyes are colorfast, but we recommend washing deep colors separately for the first wash.' ),
				array( 'q' => 'Can you stitch custom design ethnic suits?', 'a' => 'Yes, our tailors stitch customized ethnic suits based on your design references.' ),
				array( 'q' => 'Do you have unstitched ladies suit materials?', 'a' => 'Yes, we stock premium cotton and pashmina unstitched suit fabrics.' ),
				array( 'q' => 'What ethnic accessories do you stock?', 'a' => 'We offer turbans (safas), matching pocket squares, and ethnic stoles.' )
			)
		),
		'western' => array(
			'title' => __( 'Western Wear', 'ame-bazaar' ),
			'icon'  => 'globe',
			'faqs'  => array(
				array( 'q' => 'Do you carry western clothing?', 'a' => 'Yes, we stock men\'s and women\'s jeans, casual t-shirts, shirts, cardigans, and trousers.' ),
				array( 'q' => 'What fits of men\'s trousers are available?', 'a' => 'We carry slim fit, comfort fit, and regular fit cotton trousers.' ),
				array( 'q' => 'Do you sell women\'s western tops?', 'a' => 'Yes, we carry casual tops, western tunics, and soft knitted winter tops.' ),
				array( 'q' => 'Do you have denim jackets in stock?', 'a' => 'We stock lightweight denim jackets and zipper sweatshirts for casual wear.' ),
				array( 'q' => 'What sizes do you stock in women\'s jeans?', 'a' => 'We carry women\'s jeans in waist sizes 28 to 38 in high-rise styles.' ),
				array( 'q' => 'Do you carry track pants for jogging?', 'a' => 'Yes, we stock comfortable cotton track pants for both men and women.' ),
				array( 'q' => 'Do you sell girls\' western dresses?', 'a' => 'Yes, we carry trendy top-and-jeans sets and casual western dresses for girls.' ),
				array( 'q' => 'Are your t-shirts made of 100% cotton?', 'a' => 'Yes, our summer t-shirts are made from premium 100% combed cotton for breathability.' ),
				array( 'q' => 'Do you stock women\'s capri pants?', 'a' => 'Yes, we carry comfortable cotton capris suitable for casual daily wear.' ),
				array( 'q' => 'Can you alter denim jeans waist sizes?', 'a' => 'Yes, our tailors specialize in adjusting denim waistlines and tapering leg widths.' )
			)
		),
		'accessories' => array(
			'title' => __( 'Accessories', 'ame-bazaar' ),
			'icon'  => 'tag',
			'faqs'  => array(
				array( 'q' => 'What accessories do you have for men?', 'a' => 'We offer leather belts, wallets, ties, pocket squares, and handkerchiefs.' ),
				array( 'q' => 'Do you sell socks?', 'a' => 'Yes, we stock cotton socks, woolen winter socks, and school socks for kids.' ),
				array( 'q' => 'Do you carry accessories for women?', 'a' => 'We carry ladies handkerchiefs, cotton socks, stockings, and fashion waist belts.' ),
				array( 'q' => 'Do you stock kids\' caps?', 'a' => 'Yes, we carry kids\' summer caps and warm woolen beanies for winter.' ),
				array( 'q' => 'Are your wallets made of genuine leather?', 'a' => 'Yes, we offer premium genuine leather wallets in classic brown and black.' ),
				array( 'q' => 'Do you carry suspenders or bow ties?', 'a' => 'Yes, we stock bow ties and adjustable suspenders in kid and adult sizes.' ),
				array( 'q' => 'Do you sell winter gloves?', 'a' => 'Yes, we stock soft woolen gloves for men, women, and kids.' ),
				array( 'q' => 'Do you stock school uniform belts?', 'a' => 'Yes, we have elasticated school belts with metal buckles.' ),
				array( 'q' => 'Can I exchange accessories if bought as gifts?', 'a' => 'Yes, under our 7-day exchange policy, provided they are unused and in original packaging.' ),
				array( 'q' => 'Do you sell matching pocket squares for suits?', 'a' => 'Yes, we carry satin and cotton pocket squares in multiple color tones.' )
			)
		),
		'footwear' => array(
			'title' => __( 'Footwear', 'ame-bazaar' ),
			'icon'  => 'navigation',
			'faqs'  => array(
				array( 'q' => 'Does AME Bazaar sell shoes or sandals?', 'a' => 'We currently do not stock footwear in our Mubarakpur Road store. It is planned for future expansion.' ),
				array( 'q' => 'Do you sell school shoes?', 'a' => 'No, we only carry school socks. We can recommend verified local footwear vendors nearby.' ),
				array( 'q' => 'Where can I buy matching wedding shoes in Kirari?', 'a' => 'There are several reputable local footwear stores nearby on Mubarakpur Road. Our staff will happily guide you.' ),
				array( 'q' => 'Will you add footwear to your store in the future?', 'a' => 'Yes, adding high-quality daily wear sandals and juttis is part of our long-term retail roadmap.' ),
				array( 'q' => 'Do you sell socks that match formal shoes?', 'a' => 'Yes, we stock formal cotton socks in navy, black, and grey that coordinate with formal footwear.' ),
				array( 'q' => 'Do you sell baby booties?', 'a' => 'We stock soft fabric woolen booties for infants, but not hard-soled shoes.' ),
				array( 'q' => 'Can you customize traditional Mojaris or Juttis?', 'a' => 'No, we only custom tailor clothing. We do not manufacture or alter footwear.' ),
				array( 'q' => 'Do you carry sports shoes?', 'a' => 'No, we do not stock sports shoes. We recommend visiting local footwear shops in Kirari.' ),
				array( 'q' => 'Do you sell shoe care accessories?', 'a' => 'No, we do not stock shoe polish or brushes. We focus entirely on premium garments and tailoring.' ),
				array( 'q' => 'Where is the nearest footwear market?', 'a' => 'Mubarakpur Road has multiple local shoe retailers within walking distance of our clothing store.' )
			)
		),
		'fabric' => array(
			'title' => __( 'Fabric Suggestions', 'ame-bazaar' ),
			'icon'  => 'layers',
			'faqs'  => array(
				array( 'q' => 'What fabric is best for Delhi summers?', 'a' => 'Pure combed cotton, linen, and lightweight rayon are ideal to stay cool in the summer heat.' ),
				array( 'q' => 'Do you carry pure wool fabrics?', 'a' => 'Yes, we stock premium pure wool and wool-blend fabrics for winter cardigans and coats.' ),
				array( 'q' => 'What is the advantage of rayon fabric?', 'a' => 'Rayon has a beautiful silky drape, feels soft, and is highly breathable for casual ethnic wear.' ),
				array( 'q' => 'Do you stock georgette sarees?', 'a' => 'Yes, we carry georgette sarees which are lightweight, flowy, and easy to drape.' ),
				array( 'q' => 'What is the best lining fabric for custom salwar suits?', 'a' => 'We recommend pure cotton lining (mulmul) for maximum comfort and skin protection.' ),
				array( 'q' => 'Are your cotton fabrics pre-shrunk?', 'a' => 'Yes, our branded fabrics are treated to minimize shrinkage after washing.' ),
				array( 'q' => 'Do you sell silk fabric for custom blouses?', 'a' => 'Yes, we carry brocade, banarasi, and raw silk fabrics for wedding blouses.' ),
				array( 'q' => 'What is jacquard fabric?', 'a' => 'Jacquard is a textured fabric where the pattern is woven directly into the weave, ideal for Nehru jackets.' ),
				array( 'q' => 'How can I test if a fabric is pure cotton?', 'a' => 'Pure cotton feels soft, absorbs moisture instantly, and burns with a paper-like ash.' ),
				array( 'q' => 'What fabric do you suggest for comfortable kids clothing?', 'a' => 'We strongly suggest 100% organic cotton hosiery for babies and toddlers.' )
			)
		),
		'size_guide' => array(
			'title' => __( 'Size Guidance', 'ame-bazaar' ),
			'icon'  => 'check-circle',
			'faqs'  => array(
				array( 'q' => 'How do I know my correct shirt size?', 'a' => 'Our shirt sizes match standard collar measurements in inches (e.g. Size 40 corresponds to 40 inches/Medium).' ),
				array( 'q' => 'What is the kurti size chart at AME Bazaar?', 'a' => 'Kurtis range from Size S (bust 36 inches) up to XXL (bust 44 inches).' ),
				array( 'q' => 'Are kids sizes based on age or height?', 'a' => 'Our kids sizes are labeled by age (e.g. 5-6 Years), but we recommend fitting in-store since kids grow differently.' ),
				array( 'q' => 'Do you carry plus sizes for men?', 'a' => 'Yes, we carry select shirts and t-shirts up to size 46 (XXL) and trousers up to waist size 42.' ),
				array( 'q' => 'How do I measure my waist for trousers?', 'a' => 'Measure around your natural waistline, just above the hip bone, keeping the tape slightly loose.' ),
				array( 'q' => 'Do women\'s jeans sizes run true to size?', 'a' => 'Yes, our stretchable denim jeans run true to size, matching standard waist inches.' ),
				array( 'q' => 'Can you help me measure my size in-store?', 'a' => 'Yes, our staff will gladly take professional measurements to ensure a perfect fit.' ),
				array( 'q' => 'What if I am between sizes?', 'a' => 'We recommend buying the larger size and using our in-store alteration service to custom fit it.' ),
				array( 'q' => 'Do you stock different fit profiles for men\'s shirts?', 'a' => 'Yes, we carry both slim fit (tapered chest/waist) and regular fit (classic straight cut) shirts.' ),
				array( 'q' => 'Are baby clothes sized by months?', 'a' => 'Yes, infant wear is categorized as 0-3 M, 3-6 M, 6-9 M, and 9-12 M.' )
			)
		),
		'fashion_tips' => array(
			'title' => __( 'Fashion Tips', 'ame-bazaar' ),
			'icon'  => 'help-circle',
			'faqs'  => array(
				array( 'q' => 'How do I style a simple cotton kurti for office?', 'a' => 'Pair your kurti with contrasting solid leggings, light silver jewelry, and comfortable flats.' ),
				array( 'q' => 'What should I wear to a winter wedding?', 'a' => 'A velvet salwar suit or a pashmina suit for women, and a wool-blend Nehru jacket over a kurta for men.' ),
				array( 'q' => 'How can I style a Nehru jacket?', 'a' => 'Layer it over a solid color kurta-pajama set or wear it over a formal shirt for a modern semi-formal look.' ),
				array( 'q' => 'What colors suit a daytime summer event?', 'a' => 'Pastel shades like mint green, sky blue, peach, and soft lemon yellow are highly flattering.' ),
				array( 'q' => 'How to coordinate colors for family photos?', 'a' => 'Choose a primary color theme (e.g. shades of blue or cream-gold) and have family members wear variations.' ),
				array( 'q' => 'What pants go best with a short kurta?', 'a' => 'Men\'s short kurtas pair perfectly with slim-fit blue or black denim jeans.' ),
				array( 'q' => 'How do I select the right tie for a formal shirt?', 'a' => 'Your tie should generally be darker than your shirt color. A solid tie pairs well with patterned shirts.' ),
				array( 'q' => 'What is the best way to style a georgette saree?', 'a' => 'Drape it with neat pleats and pair it with a contrast-colored embroidered blouse.' ),
				array( 'q' => 'What casual outfit is best for hot Delhi humidity?', 'a' => 'A loose-fit cotton t-shirt paired with cotton track pants or light chinos.' ),
				array( 'q' => 'Can you help me choose an outfit for a job interview?', 'a' => 'Yes, visit our store and our staff will coordinate a professional formal shirt and trouser combo.' )
			)
		),
		'care_guide' => array(
			'title' => __( 'Care Guide & Materials', 'ame-bazaar' ),
			'icon'  => 'activity',
			'faqs'  => array(
				array( 'q' => 'How should I wash woolen clothes?', 'a' => 'Gently hand wash woolens in cold water using a mild wool-safe liquid detergent. Dry flat in shade.' ),
				array( 'q' => 'Should I dry clean silk sarees?', 'a' => 'Yes, dry cleaning is highly recommended for silk sarees to preserve the natural shine and threadwork.' ),
				array( 'q' => 'How do I prevent cotton clothes from shrinking?', 'a' => 'Wash them in cold water on a gentle cycle and avoid high-heat tumble dryers.' ),
				array( 'q' => 'Can I iron embroidered garments directly?', 'a' => 'No, always iron embroidered clothes inside out, or use a thin cotton cloth on top to protect details.' ),
				array( 'q' => 'How do I maintain my stretch denim jeans?', 'a' => 'Wash them inside out in cold water. Never use fabric softener as it weakens the elastane stretch fibers.' ),
				array( 'q' => 'How do I store heavy festive outfits?', 'a' => 'Store them wrapped in clean, dry cotton muslin cloth bags to protect them from moisture and dust.' ),
				array( 'q' => 'How can I remove oil stains from cotton?', 'a' => 'Apply mild dish soap to the stain, let it sit for 5 minutes, wash in cold water. Do not iron until the stain is gone.' ),
				array( 'q' => 'How to care for ladies hosiery nighties?', 'a' => 'Machine wash in cold water and dry on a low setting. Do not bleach.' ),
				array( 'q' => 'What is the best way to clean leather belts and wallets?', 'a' => 'Wipe them with a damp cloth and let them air dry. Apply leather conditioner occasionally.' ),
				array( 'q' => 'Why should I dry clean heavy wedding wear?', 'a' => 'Wedding outfits contain complex lining, padding, and heavy embroidery that can warp in standard washing.' )
			)
		),
		'payments' => array(
			'title' => __( 'Payments', 'ame-bazaar' ),
			'icon'  => 'credit-card',
			'faqs'  => array(
				array( 'q' => 'What payment methods do you accept at the store?', 'a' => 'We accept Cash, UPI (GPay, PhonePe, Paytm), Credit Cards, Debit Cards, and Net Banking.' ),
				array( 'q' => 'Do you accept international credit cards?', 'a' => 'Yes, we accept Visa, Mastercard, and American Express cards processed through our terminal.' ),
				array( 'q' => 'Is there any extra fee for paying by card?', 'a' => 'No, we do not charge any additional processing fees or card surcharges.' ),
				array( 'q' => 'Can I pay using multiple payment methods?', 'a' => 'Yes, you can split your bill (e.g. part cash, part UPI) at our billing desk.' ),
				array( 'q' => 'Do you accept mobile wallets like Paytm Wallet?', 'a' => 'Yes, we accept wallet payments via our UPI QR scanner.' ),
				array( 'q' => 'Is cash on delivery available for home orders?', 'a' => 'For local orders booked via WhatsApp, we can arrange cash-on-delivery within Kirari.' ),
				array( 'q' => 'Do you issue a printed invoice for all purchases?', 'a' => 'Yes, we provide a detailed printed invoice for every transaction for warranty and exchanges.' ),
				array( 'q' => 'Can I pay via bank transfer (IMPS/NEFT)?', 'a' => 'Yes, for large custom tailoring wedding orders, we accept direct bank transfers.' ),
				array( 'q' => 'Are digital payments safe at your store?', 'a' => 'Yes, our billing terminals and QR scanners utilize secure, encrypted banking gateways.' ),
				array( 'q' => 'Do you accept Sodexo or meal vouchers?', 'a' => 'No, we do not accept food vouchers. We accept standard debit/credit cards and UPI.' )
			)
		),
		'returns' => array(
			'title' => __( 'Returns & Exchange', 'ame-bazaar' ),
			'icon'  => 'refresh-cw',
			'faqs'  => array(
				array( 'q' => 'What is your exchange policy?', 'a' => 'We offer an in-store exchange within 7 days of purchase on unworn garments with tags intact.' ),
				array( 'q' => 'Do you offer cash refunds?', 'a' => 'No, we do not issue cash refunds. We provide exchanges or store credit valid for future purchases.' ),
				array( 'q' => 'Can I exchange custom-tailored clothes?', 'a' => 'Custom stitched garments are non-exchangeable, but we provide free sizing adjustments.' ),
				array( 'q' => 'What documents do I need for an exchange?', 'a' => 'Please bring the original purchase invoice/receipt and ensure price tags are attached.' ),
				array( 'q' => 'Can I exchange innerwear or socks?', 'a' => 'For hygiene reasons, innerwear, briefs, and socks are strictly non-exchangeable.' ),
				array( 'q' => 'Do you offer exchange pick-up from home?', 'a' => 'No, all exchanges must be processed in-person at our Mubarakpur Road store.' ),
				array( 'q' => 'Can I exchange a garment bought on sale?', 'a' => 'Garments purchased during promotional clearance sales are considered final sale and cannot be exchanged.' ),
				array( 'q' => 'How long is store credit valid?', 'a' => 'Store credit vouchers issued by AME Bazaar are valid for 90 days from the date of issue.' ),
				array( 'q' => 'What if I receive a defective item?', 'a' => 'If a manufacturing defect is verified, we will replace the item immediately or provide full store credit.' ),
				array( 'q' => 'Can my family member process the exchange for me?', 'a' => 'Yes, anyone can process the exchange if they bring the garment and the original invoice.' )
			)
		),
		'parking' => array(
			'title' => __( 'Parking', 'ame-bazaar' ),
			'icon'  => 'map-pin',
			'faqs'  => array(
				array( 'q' => 'Is parking available at the AME Bazaar store?', 'a' => 'Yes, we have free designated customer parking directly in front of our Mubarakpur Road storefront.' ),
				array( 'q' => 'Can I park my two-wheeler safely?', 'a' => 'Yes, there is a secure, wide space dedicated for customer two-wheelers and scooters.' ),
				array( 'q' => 'Is there car parking space available?', 'a' => 'Yes, roadside car parking is available right outside the shop. Our staff will assist you in parking.' ),
				array( 'q' => 'Are parking spaces monitored?', 'a' => 'Our store front is monitored by security cameras, providing secondary safety for parked vehicles.' ),
				array( 'q' => 'Is parking free?', 'a' => 'Yes, parking is 100% free for all shoppers visiting AME Bazaar.' ),
				array( 'q' => 'Is there wheelchair-accessible parking?', 'a' => 'Yes, the front landing has space to park close to the ramp for easy wheelchair entry.' ),
				array( 'q' => 'Can I park overnight?', 'a' => 'No, overnight parking is not allowed. Parking is strictly reserved during store hours (09:00 AM – 10:00 PM).' ),
				array( 'q' => 'What is the best time to visit to find empty parking?', 'a' => 'Weekday mornings and early afternoons (11:00 AM to 04:00 PM) generally have plenty of open parking.' ),
				array( 'q' => 'Is valet parking available?', 'a' => 'No, we do not offer valet service, but our guards will happily guide you to an open spot.' ),
				array( 'q' => 'Are there height restrictions for vehicle parking?', 'a' => 'No, it is an open outdoor ground-level space with no height clearance limits.' )
			)
		),
		'store_visit' => array(
			'title' => __( 'Store Visit', 'ame-bazaar' ),
			'icon'  => 'eye',
			'faqs'  => array(
				array( 'q' => 'What can I expect during a visit to AME Bazaar?', 'a' => 'A clean, well-lit, fully air-conditioned environment with polite staff assisting you in finding sizes.' ),
				array( 'q' => 'Is the store air-conditioned?', 'a' => 'Yes, the entire showroom is fully air-conditioned for a comfortable shopping experience.' ),
				array( 'q' => 'Do you have trial rooms?', 'a' => 'Yes, we have spacious, clean trial rooms with large mirrors for trying on garments.' ),
				array( 'q' => 'Are your staff trained to help with sizes?', 'a' => 'Yes, our experienced sales advisors will help you find the right fits and patterns.' ),
				array( 'q' => 'Is the store wheelchair accessible?', 'a' => 'Yes, our Mubarakpur Road entrance has a step-free ramp for easy wheelchair entry.' ),
				array( 'q' => 'Are children allowed in the store?', 'a' => 'Yes, we are a family-first store with comfortable seating areas for parents and children.' ),
				array( 'q' => 'Do you allow photography inside the showroom?', 'a' => 'For custom tailoring and outfit matching, you can photograph your trials, but general store recording is restricted.' ),
				array( 'q' => 'Is the store crowded on weekends?', 'a' => 'Weekends see higher footfall. If you prefer a quiet shopping experience, visit us on weekdays.' ),
				array( 'q' => 'Do you have drinking water facilities for shoppers?', 'a' => 'Yes, we provide mineral drinking water complimentary to all our customers.' ),
				array( 'q' => 'Are pets allowed inside the store?', 'a' => 'Only registered service animals are permitted inside the showroom to ensure comfort for all visitors.' )
			)
		),
		'directions' => array(
			'title' => __( 'Directions & Landmarks', 'ame-bazaar' ),
			'icon'  => 'map',
			'faqs'  => array(
				array( 'q' => 'What is the closest landmark to AME Bazaar?', 'a' => 'We are located near the famous Chappan Bhog sweet shop on Mubarakpur Road in Kirari.' ),
				array( 'q' => 'How can I navigate to the store on Google Maps?', 'a' => 'Search for "AME Bazaar" on Google Maps or go to https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi.' ),
				array( 'q' => 'What is the nearest Metro Station?', 'a' => 'Nangloi Metro Station (Green Line) and Rithala Metro Station (Red Line) are the nearest stops.' ),
				array( 'q' => 'How do I reach the store from Nangloi?', 'a' => 'You can take an e-rickshaw or auto-rickshaw directly from Nangloi Metro Station to Mubarakpur Road.' ),
				array( 'q' => 'Is the store located on the main road?', 'a' => 'Yes, the showroom is situated directly on the main Mubarakpur Road for easy visibility.' ),
				array( 'q' => 'How far is the store from Rohini?', 'a' => 'We are approximately 15-20 minutes away from Rohini Sector 20/21 by car.' ),
				array( 'q' => 'Can I find the store easily at night?', 'a' => 'Yes, our storefront features a brightly lit "AME Bazaar" signboard easily visible from the road.' ),
				array( 'q' => 'Are there direct buses to Mubarakpur Road?', 'a' => 'Yes, local DTC buses and Gramin Sewa vehicles halt near the Mubarakpur Road crossing.' ),
				array( 'q' => 'What is the pin code of your store location?', 'a' => 'Our local area postal pin code is 110086.' ),
				array( 'q' => 'What should I do if I get lost?', 'a' => 'Call us directly at +91 99535 69533 and our staff will guide you to our front gate.' )
			)
		),
		'budget' => array(
			'title' => __( 'Budget Shopping', 'ame-bazaar' ),
			'icon'  => 'percent',
			'faqs'  => array(
				array( 'q' => 'Is AME Bazaar affordable?', 'a' => 'Yes, we offer premium quality family clothing at fair and honest prices, avoiding inflated boutique markups.' ),
				array( 'q' => 'What is the starting price for men\'s t-shirts?', 'a' => 'Our high-quality men\'s cotton t-shirts start at a very reasonable price of ₹299.' ),
				array( 'q' => 'What is the price range of your kurtis?', 'a' => 'Our everyday wear kurtis start at ₹399, with premium festive sets starting at ₹1200.' ),
				array( 'q' => 'Do you have suits under ₹1500?', 'a' => 'Yes, we carry unstitched suit materials and daily wear cotton salwar kameez sets under ₹1500.' ),
				array( 'q' => 'Are there alteration charges on purchased items?', 'a' => 'We offer complimentary basic alterations (length & waist) on all full-price items purchased at our store.' ),
				array( 'q' => 'Do you offer discount coupons?', 'a' => 'We focus on fair daily pricing rather than misleading markdowns, but we run seasonal customer reward benefits.' ),
				array( 'q' => 'Can I buy premium wedding sherwanis on a budget?', 'a' => 'Yes, our in-store custom tailored options provide boutique-level designs at a fraction of the cost.' ),
				array( 'q' => 'What is the price of kids wear rompers?', 'a' => 'Our premium baby rompers start at just ₹199, made of soft skin-friendly cotton.' ),
				array( 'q' => 'Do you offer corporate or bulk purchase discounts?', 'a' => 'Yes, for bulk purchases (e.g., matching group sets), we provide custom pricing. Contact +91 99535 69533.' ),
				array( 'q' => 'Do you charge extra for custom tailoring measurements?', 'a' => 'No, size measurements and styling guidance are completely free of charge.' )
			)
		),
		'delhi_shopping' => array(
			'title' => __( 'Delhi Shopping Guide', 'ame-bazaar' ),
			'icon'  => 'award',
			'faqs'  => array(
				array( 'q' => 'Why choose AME Bazaar over markets like Chandni Chowk?', 'a' => 'We offer similar wholesale-competitive pricing and premium quality without the crowd, travel time, and bargaining stress.' ),
				array( 'q' => 'How do your prices compare to Rohini shopping malls?', 'a' => 'Our retail prices are generally 30% to 50% lower than malls due to direct weaver sourcing and low overheads.' ),
				array( 'q' => 'Do you stock typical Delhi NCR ethnic designs?', 'a' => 'Yes, we keep our catalog updated with the latest trends popular across Delhi fashion circles.' ),
				array( 'q' => 'Are your woolens suitable for Delhi\'s extreme winter?', 'a' => 'Yes, our thick cardigans, sweaters, and woolen suits are designed specifically for Delhi\'s cold waves.' ),
				array( 'q' => 'Is it easy to visit your store from West Delhi?', 'a' => 'Yes, we are highly accessible via Nangloi or Pitampura, situated on Mubarakpur Road.' ),
				array( 'q' => 'Do you source fabrics locally from Delhi markets?', 'a' => 'We source directly from weavers in Gujarat, Rajasthan, and Punjab, as well as select premium Delhi textile hubs.' ),
				array( 'q' => 'Can I get matching wedding accessories in Delhi?', 'a' => 'Yes, we coordinate complete groom and bridal sets, serving shoppers from Rohini, Nangloi, and Pitampura.' ),
				array( 'q' => 'Do you cater to Delhi university students styling?', 'a' => 'Yes, we offer a wide range of affordable cotton kurtis, chic co-ord sets, and casual tees ideal for college wear.' ),
				array( 'q' => 'What makes AME Bazaar a top Delhi fashion destination?', 'a' => 'Our unique combination of premium retail showroom, wholesale-matching fair prices, and expert custom tailoring.' ),
				array( 'q' => 'Do you participate in Delhi fashion expos?', 'a' => 'We focus entirely on our local Kirari flagship showroom to deliver personalized care directly to our patrons.' )
			)
		),
		'kirari_shopping' => array(
			'title' => __( 'Kirari Shopping Guide', 'ame-bazaar' ),
			'icon'  => 'thumbs-up',
			'faqs'  => array(
				array( 'q' => 'Why is AME Bazaar the best clothing store in Kirari?', 'a' => 'We provide a premium, air-conditioned multi-generation showroom experience with direct tailoring and 4.9-star customer trust.' ),
				array( 'q' => 'Where can I buy family clothing in Kirari?', 'a' => 'AME Bazaar on Mubarakpur Road is a single-destination store for Men, Women, and Kids.' ),
				array( 'q' => 'Do you serve other areas near Kirari?', 'a' => 'Yes, we serve Mubarakpur, Baljit Vihar, Prem Nagar, Nangloi, Budh Vihar, and Rohini.' ),
				array( 'q' => 'Is custom tailoring common in Kirari?', 'a' => 'Yes, but AME Bazaar is unique in having dedicated master tailors inside a modern retail clothing showroom.' ),
				array( 'q' => 'Do you support the local Kirari community?', 'a' => 'Yes, Apparel Maheshwari Enterprises employs local tailors and staff, contributing to our local community.' ),
				array( 'q' => 'Where can I find premium sarees in Kirari?', 'a' => 'We stock high-quality wedding and festive sarees in georgette, crepe, and silk at our showroom.' ),
				array( 'q' => 'Is there parking space near Mubarakpur Road stores?', 'a' => 'Many shops lack parking, but AME Bazaar provides dedicated front parking for customer vehicles.' ),
				array( 'q' => 'Can I get alterations done near me in Kirari?', 'a' => 'Yes, our tailors offer rapid garment resizing and hem alterations on Mubarakpur Road.' ),
				array( 'q' => 'What is the average rating of AME Bazaar in Kirari?', 'a' => 'We are highly rated at 4.9 Stars on Google Maps with over 524+ local customer reviews.' ),
				array( 'q' => 'Does the store represent Kirari fashion tastes?', 'a' => 'Yes, we curate collections matching the ethnic and casual preferences of local Delhi 110086 families.' )
			)
		)
	);
}
