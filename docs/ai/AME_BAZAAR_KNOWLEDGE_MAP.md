# AME Bazaar AI Knowledge Map

**Purpose:** Source-derived knowledge boundary for AME Bazaar AI/customer-facing agents.

## 1. Authority order

1. `wordpress/wp-content/themes/ame-bazaar/llms.txt` — primary business facts, location, contact, hours, products, tailoring, policies and amenities.
2. `wordpress/wp-content/themes/ame-bazaar/inc/faq-data.php` — 200 FAQ pairs grouped into 22 topics; use for customer-question coverage.
3. Live/product/inventory sources — use only when a current lookup is available. Do not treat static product data as live stock unless explicitly verified.

## 2. Verified business identity

- Brand: **AME Bazaar**
- Legal entity: **Apparel Maheshwari Enterprises**
- Business type: Premium Family Fashion Retail Brand & Custom Tailoring Showroom
- Store description: Multi-generational family clothing showroom on Mubarakpur Road, Kirari, Delhi.
- Google trust metric in source: 4.9 stars, 524+ local customer reviews.

## 3. Store contact & visit facts

- Address: Mubarakpur Road, Near Chappan Bhog, Kirari Suleman Nagar, Delhi - 110086
- PIN: 110086
- Coordinates: 28.7051, 77.0583
- Hours: Monday-Sunday, 09:00 AM-10:00 PM
- Phone/WhatsApp: +91 99535 69533
- Email: info@amebazaar.in
- Website: https://amebazaar.in

## 4. Product/service knowledge

### Men's
- Formal shirts, casual T-shirts, denim jeans, trousers, sweaters, winter coats, traditional Kurta Pajamas.
- Jodhpuri coats: ready-made and custom tailoring.
- Thermal sets, joggers/activewear and other winter/ethnic options are covered in FAQs.

### Women's
- Designer kurtis, salwar-kameez/SKD sets, woolen tops, palazzos, leggings, georgette/silk sarees, nightwear.
- Jeans, co-ord sets, shawls/stoles, slips/camisoles are covered in FAQs.

### Kids
- Newborn/infant clothing, boys' ethnic wear, girls' party wear, raincoats, winter wear/thermals and related children's apparel.
- Source states coverage from newborn infants to 14 years.

### Tailoring
- In-store custom tailoring and alterations for men, women and kids.
- Custom Kurta Pajama, waistcoats, Jodhpuri suits, Bandhgalas and ladies suits are supported by the source.
- Standard alterations are stated as 24-48 hours.
- Walk-in tailoring submission is supported; no appointment is required according to the FAQ source.
- External garments and customer-provided fabric are supported according to the FAQ source.

## 5. Policies & amenities

### Payment
- Cash, UPI (GPay, Paytm, PhonePe), credit cards and debit cards.
- No processing surcharge is stated.

### Exchange
- 7-day in-store exchange for unworn garments with tags intact and receipt.
- No cash refunds; store-credit vouchers with 90-day validity.
- Custom-stitched items and innerwear/socks are non-exchangeable.

### Store amenities
- Free customer parking in front of the storefront.
- Air-conditioned showroom.
- Trial rooms with large mirrors.
- Step-free wheelchair ramp.
- Complimentary drinking water.

## 6. FAQ knowledge domains

The FAQ database is explicitly structured around customer topics including:
- Men's fashion
- Women's fashion
- Kids' wear
- Tailoring & custom fit
- Wedding shopping
- Festival shopping
- Ethnic wear
- Additional topics contained in the source database

## 7. AI response rules

- Prefer the authoritative facts above over model general knowledge.
- Do not invent stock availability, price, size, brand, discount, delivery, or tailoring completion time when the source does not support the exact answer.
- Static product/catalog information must not be presented as real-time inventory.
- If a customer asks for a product's current availability or exact price and no live lookup is available, say that current availability/price should be confirmed with the store.
- For policies, quote the stored policy facts rather than improvising exceptions.
- For unknown questions, ask for the minimum clarification or route to the store rather than hallucinating.
- Keep customer-facing replies concise, natural and helpful.

## 8. Known source-boundary warnings

- This map records what the current repository sources state; it does not independently verify those claims.
- Some FAQ entries contain detailed product/quality/trend claims. Use them only where the FAQ source directly supports the customer's question.
- The source contains both broad category descriptions and detailed FAQ claims; do not silently merge them into stronger claims than either source makes.

## Source files

- `wordpress/wp-content/themes/ame-bazaar/llms.txt`
- `wordpress/wp-content/themes/ame-bazaar/inc/faq-data.php`
