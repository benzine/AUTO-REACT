# AutoParts Pro - Premium WordPress Theme

**Version:** 1.0.0  
**Price Point:** $25,000 Premium Automotive Theme  
**Compatibility:** WordPress 6.0+, PHP 8.0+, WooCommerce 8.0+

---

## 🏎️ Overview

AutoParts Pro is a high-end WordPress theme designed specifically for automotive spare parts dealers. It delivers an unforgettable automotive experience with visually explosive design, technically sophisticated features, and React.js-level fluidity in every interaction.

## ✨ Key Features

### Signature Hero Section
- **Scroll-Triggered Exploded View Animation** - Cinematic 3D engine/vehicle explosion as users scroll
- Built with GSAP ScrollTrigger + Three.js/React Three Fiber
- Fully reversible animation (scroll up/down)
- Customizable via WordPress admin

### Design & UX
- **Color Palette:** Racing Red (#DC2626), Obsidian Black (#0A0A0A), Charcoal Grey, Pure White
- **Typography:** Rajdhani/Oswald (headings), Inter (body), JetBrains Mono (numbers)
- **Custom Cursor:** Crosshair → Gear on hover → Spark on click
- **Gear Shift Navigation:** Innovative mechanical menu design
- **Light/Dark Mode:** Seamless toggle with localStorage persistence

### E-Commerce Features
- Multi-level product categories with hierarchy
- Vehicle compatibility checker (Year/Make/Model/Engine)
- User garage system for saved vehicles
- Bulk pricing tiers with automatic discounts
- Real-time stock status with color coding
- Wishlist system (guest + logged-in support)
- Product sharing (Facebook, Twitter, WhatsApp, QR code)
- 360° product view support

### Premium Components
1. **AI Chatbot Widget** - Auto-opening automotive assistant
2. **Back-to-Top Button** - Smooth scroll with customizable icon
3. **Google Maps Integration** - Store locator with custom markers
4. **Advanced Contact Forms** - Multi-step with spam protection
5. **Mega Menu System** - Multi-column with images
6. **Dual Slider System** - Native + Revolution Slider support
7. **Order Tracking** - Visual timeline status

### Performance
- Lighthouse 95+ target
- Code splitting & lazy loading
- Critical CSS inlining
- WebP image support
- Minimal jQuery dependency

## 📁 Directory Structure

```
autoparts-pro/
├── assets/
│   ├── css/
│   │   └── main.css          # 1,255 lines of compiled styles
│   ├── js/
│   │   └── main.js           # 645 lines vanilla JS + GSAP
│   ├── images/
│   └── fonts/
├── inc/
│   ├── ajax-handlers.php     # AJAX endpoints
│   ├── customizer.php        # WP Customizer settings
│   ├── post-types.php        # CPTs: Vehicle, Brand
│   ├── template-tags.php     # Template functions
│   ├── theme-options.php     # Admin options panel
│   ├── widgets.php           # Custom widgets
│   └── woocommerce.php       # WC integration
├── src/                      # React/TypeScript source
│   ├── components/
│   │   ├── layout/
│   │   ├── products/
│   │   ├── sections/
│   │   └── ui/
│   ├── stores/               # Zustand state management
│   ├── hooks/
│   └── data/
├── template-parts/
│   ├── hero/
│   │   └── exploded-view-hero.php
│   ├── content/
│   │   ├── categories-section.php
│   │   └── featured-products.php
│   └── page/
├── woocommerce/              # WC template overrides
│   ├── cart/
│   │   └── cart.php
│   ├── checkout/
│   │   └── form-checkout.php
│   ├── single-product/
│   │   └── single-product.php
│   ├── archive-product.php
│   └── content-product.php
├── page-templates/
│   ├── page-brands.php
│   ├── page-contact.php
│   ├── page-order-tracking.php
│   └── page-wishlist.php
├── header.php
├── footer.php
├── index.php
├── functions.php
├── style.css                 # Theme header
├── package.json
├── vite.config.js
├── tailwind.config.js
└── README.md
```

## 🚀 Installation

### Requirements
- WordPress 6.0 or higher
- PHP 8.0 or higher
- WooCommerce 8.0 or higher
- MySQL 5.6+ or MariaDB 10.1+

### Steps
1. Upload `autoparts-pro` folder to `/wp-content/themes/`
2. Activate via WordPress Admin → Appearance → Themes
3. Install required plugins (WooCommerce, Contact Form 7)
4. Import demo content (optional)
5. Configure theme options via Appearance → Customize

## 🛠 Development

### Build Commands
```bash
# Install dependencies
npm install

# Development mode
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

### Tech Stack
- **Frontend:** React 19, TypeScript, Zustand, Framer Motion
- **Styling:** SCSS, Tailwind CSS, CSS Variables
- **Animation:** GSAP, Three.js, React Three Fiber
- **Build:** Vite, Babel

## 🎨 Customization

### Theme Options Panel
Access via **Appearance → AutoParts Pro Options**:
- General Settings (logos, contact info)
- Hero Section (type, video, explosion speed)
- Color Customization (light/dark palettes)
- Typography Settings
- Shop Configuration
- Chatbot Settings
- Footer Settings

### WordPress Customizer
Access via **Appearance → Customize**:
- Site Identity
- Colors
- Typography
- Header Layout
- Footer Layout
- Homepage Settings

## 📦 Included Templates

| Template | File | Description |
|----------|------|-------------|
| Homepage | `index.php` | Exploded view hero, categories, featured products |
| Shop | `woocommerce/archive-product.php` | Advanced filtering, vehicle compatibility bar |
| Single Product | `woocommerce/single-product.php` | Gallery, compatibility checker, bulk pricing |
| Cart | `woocommerce/cart/cart.php` | Fitment verification, trust badges |
| Checkout | `woocommerce/checkout/form-checkout.php` | Progress steps, order summary |
| Wishlist | `page-wishlist.php` | AJAX-powered, multiple lists |
| Brands | `page-brands.php` | A-Z filter, featured partners |
| Contact | `page-contact.php` | Spam-protected form, store locator |
| Order Tracking | `page-order-tracking.php` | Timeline status, order details |

## 🔌 WooCommerce Integration

### Supported Features
- Product galleries (zoom, lightbox, slider)
- Custom product tabs (Description, Specifications, Compatibility, Installation, Reviews)
- Mini cart with AJAX updates
- Cart fragments refresh
- Product quick view
- AJAX add to cart
- Wishlist buttons (loop + single)
- Share buttons with QR code generation
- Stock status display
- Bulk pricing tiers
- Compatibility badges

### Custom Hooks
```php
// Cart
do_action('autoparts_cart_before', $cart);
do_action('autoparts_cart_form_start');
do_action('autoparts_cart_form_end');
do_action('autoparts_cart_after', $cart);

// Checkout
do_action('autoparts_checkout_before', $checkout);
do_action('autoparts_checkout_after', $checkout);

// Single Product
do_action('autoparts_single_product_before', $product);
do_action('autoparts_single_product_after', $product);
```

## 🌐 SEO & Accessibility

### SEO Features
- Yoast & Rank Math compatible
- Schema.org markup (Product, LocalBusiness, Article, FAQ, BreadcrumbList, Review)
- Clean semantic HTML5
- Proper heading hierarchy
- ARIA labels throughout
- XML sitemap ready
- Canonical URLs

### Accessibility
- WCAG 2.1 AA compliant
- Keyboard navigation support
- Screen reader friendly
- Reduced motion support (`prefers-reduced-motion`)
- Focus states visible
- Color contrast ratios meet standards

## 📱 Responsive Breakpoints

```scss
$breakpoint-xs: 480px;   // Small phones
$breakpoint-sm: 768px;   // Tablets
$breakpoint-md: 1024px;  // Small laptops
$breakpoint-lg: 1280px;  // Desktops
$breakpoint-xl: 1440px;  // Large screens
```

## 🔒 Security

- CSRF protection on all forms
- Sanitized inputs (`sanitize_text_field`, `sanitize_email`, etc.)
- Escaped outputs (`esc_html`, `esc_url`, `esc_attr`)
- Nonce verification
- Capability checks
- Regular security audits recommended

## 📄 License

Commercial license required for production use.  
Includes 6 months of support and updates.

## 🤝 Support

- Documentation: `/docs/` folder
- Video tutorials: Available in admin panel
- Support forum: Envato Markets
- Direct support: support@autopartspro.com

---

**Built with ❤️ for automotive enthusiasts**
