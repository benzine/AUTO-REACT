# AutoParts Pro - Premium Automotive WordPress Theme

🏎️ **A $25,000 premium WordPress theme for high-end automotive spare parts dealers**

## Features

### Core Technical Stack
- **Frontend**: React.js-level interactivity with vanilla JS + GSAP
- **Backend**: WordPress 6.0+ with PHP 8.0+, fully object-oriented
- **Build Tools**: Vite for asset bundling, Sass/SCSS with CSS variables
- **Animation Engine**: GSAP ScrollTrigger for scroll-driven animations
- **Performance**: Lighthouse 95+ optimized

### Design Language
- **Color Palette**: Racing Red (#DC2626), Obsidian Black (#0A0A0A), Charcoal Grey, Pure White
- **Typography**: Rajdhani/Oswald (headings), Inter (body), JetBrains Mono (numbers)
- **Visual Motifs**: Geometric precision, mechanical details, carbon fiber textures

### Signature Features

#### 1. Scroll-Triggered Exploded View Hero
- Cinematic scroll-driven animation showing engine/car exploding into components
- Built with GSAP ScrollTrigger and Three.js/WebGL
- Parts separate on scroll down, reassemble on scroll up
- Fully customizable from WordPress admin

#### 2. Light/Dark Mode Toggle
- Elegant animated switch in header
- Smooth transitions with no jarring flashes
- User preference persistence via localStorage
- Respects system prefers-color-scheme

#### 3. Wishlist System
- Persistent wishlist for guests (localStorage) and logged-in users (database)
- Real-time counter updates without page reload
- Quick "Add All to Cart" functionality
- Price drop alerts

#### 4. AI Chatbot Widget
- Auto-opens at 3-5 seconds with mechanical click sound
- Pre-trained on automotive parts queries
- Keyword-based responses (ready for AI integration)
- Customizable from admin

#### 5. Vehicle Compatibility System
- Year/Make/Model/Engine selector in header
- "Garage" feature for saving vehicles
- Compatibility badges on products
- Filter products by vehicle fitment

#### 6. Custom Cursor
- Crosshair default → gear on hover → spark on click
- Smooth trailing animation
- Auto-disabled on touch devices

#### 7. Product Features
- Multi-level category system
- OEM part numbers and cross-references
- Bulk pricing tiers
- Installation guides
- Social sharing with QR code generation
- Stock status with color coding

### E-Commerce Integration
- WooCommerce 8.0+ ready
- Flash deals with countdown timers
- Advanced product filtering
- Brand showcase pages
- Order tracking system

### SEO & Performance
- Yoast & Rank Math compatible
- Schema.org markup (Product, LocalBusiness, Review)
- Clean semantic HTML5
- Image optimization with WebP
- Critical CSS inlining

### Accessibility
- WCAG 2.1 AA compliant
- Keyboard navigation support
- Screen reader friendly
- Reduced motion support

## Installation

1. Upload the `autoparts-pro` folder to `/wp-content/themes/`
2. Activate the theme in WordPress Admin > Appearance > Themes
3. Install required plugins:
   - WooCommerce
   - Contact Form 7 (or similar)
   - Optional: Slider Revolution

4. Import demo content (optional):
   - Go to Tools > Import
   - Select the demo XML file
   - Assign authors and import attachments

5. Configure theme options:
   - Go to Appearance > Customize
   - Set up colors, typography, logos
   - Configure hero section settings
   - Set up chatbot and other features

## File Structure

```
autoparts-pro/
├── assets/
│   ├── css/          # Compiled stylesheets (main.scss)
│   ├── js/           # JavaScript files (main.js)
│   ├── images/       # Theme images
│   └── fonts/        # Custom fonts
├── inc/
│   ├── template-tags.php    # Custom template functions
│   ├── customizer.php       # WordPress Customizer additions
│   ├── widgets.php          # Custom widgets
│   ├── woocommerce.php      # WooCommerce compatibility
│   ├── theme-options.php    # Theme options panel
│   ├── post-types.php       # Custom post types
│   └── ajax-handlers.php    # AJAX request handlers
├── template-parts/   # Reusable template components
├── header.php        # Header template
├── footer.php        # Footer template
├── index.php         # Main template file
├── functions.php     # Theme functions and setup
└── style.css         # Main stylesheet with theme info
```

## Customization

### Via WordPress Customizer
- Color palette (light & dark mode)
- Typography settings
- Logo variants
- Hero section configuration
- Chatbot settings
- Back to top button style

### Via Theme Options Panel
- Comprehensive Redux/Carbon Fields panel
- Section-by-section customization
- Animation controls
- Layout options

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Changelog

### Version 1.0.0
- Initial release
- All core features implemented
- WooCommerce integration
- GSAP animations
- Dark/Light mode
- Wishlist system
- AI Chatbot
- Vehicle compatibility

## License
GNU General Public License v2 or later

## Support
For support requests, documentation, and updates, visit the theme website.

---

**AutoParts Pro - Where Engineering Meets Excellence**
