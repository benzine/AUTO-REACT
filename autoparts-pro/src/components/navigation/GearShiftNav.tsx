import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useUiStore } from '../../stores/uiStore';

interface MenuItem {
  id: string;
  label: string;
  icon?: string;
  children?: MenuItem[];
  image?: string;
}

const menuData: MenuItem[] = [
  {
    id: 'engine',
    label: 'Engine Parts',
    children: [
      { id: 'pistons', label: 'Pistons & Rings' },
      { id: 'gaskets', label: 'Gaskets & Seals' },
      { id: 'timing', label: 'Timing Belts & Chains' },
      { id: 'camshafts', label: 'Camshafts' },
      { id: 'valves', label: 'Valves & Components' },
    ],
  },
  {
    id: 'brakes',
    label: 'Brakes',
    children: [
      { id: 'pads', label: 'Brake Pads' },
      { id: 'rotors', label: 'Brake Rotors' },
      { id: 'calipers', label: 'Brake Calipers' },
      { id: 'fluid', label: 'Brake Fluid' },
    ],
  },
  {
    id: 'suspension',
    label: 'Suspension',
    children: [
      { id: 'shocks', label: 'Shock Absorbers' },
      { id: 'struts', label: 'Struts' },
      { id: 'springs', label: 'Coil Springs' },
      { id: 'control-arms', label: 'Control Arms' },
    ],
  },
  {
    id: 'electrical',
    label: 'Electrical',
    children: [
      { id: 'batteries', label: 'Batteries' },
      { id: 'alternators', label: 'Alternators' },
      { id: 'starters', label: 'Starters' },
      { id: 'sensors', label: 'Sensors' },
    ],
  },
  {
    id: 'filters',
    label: 'Filters',
    children: [
      { id: 'oil', label: 'Oil Filters' },
      { id: 'air', label: 'Air Filters' },
      { id: 'cabin', label: 'Cabin Filters' },
      { id: 'fuel', label: 'Fuel Filters' },
    ],
  },
];

const GearShiftNav: React.FC = () => {
  const [activeItem, setActiveItem] = useState<string | null>(null);
  const [isHovering, setIsHovering] = useState(false);
  const { isMobileMenuOpen, toggleMobileMenu } = useUiStore();

  // Close menu when clicking outside
  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (!isHovering && activeItem) {
        setActiveItem(null);
      }
    };

    document.addEventListener('click', handleClickOutside);
    return () => document.removeEventListener('click', handleClickOutside);
  }, [activeItem, isHovering]);

  const gearVariants = {
    hidden: { opacity: 0, y: -20 },
    visible: { 
      opacity: 1, 
      y: 0,
      transition: { duration: 0.3, ease: 'easeOut' }
    },
    exit: { 
      opacity: 0, 
      y: -20,
      transition: { duration: 0.2 }
    },
  };

  const itemVariants = {
    hidden: { opacity: 0, x: -10 },
    visible: (i: number) => ({
      opacity: 1,
      x: 0,
      transition: { delay: i * 0.05, duration: 0.2 },
    }),
  };

  return (
    <nav className="gear-shift-nav">
      {/* Desktop Gear Shift Navigation */}
      <div className="gear-shift-container desktop-only">
        <div className="gear-shift-base">
          <div className="gear-shift-pattern">
            {menuData.map((item, index) => (
              <motion.div
                key={item.id}
                className={`gear-position ${activeItem === item.id ? 'active' : ''}`}
                style={{
                  gridRow: Math.floor(index / 2) + 1,
                  gridColumn: (index % 2) + 1,
                }}
                onMouseEnter={() => {
                  setActiveItem(item.id);
                  setIsHovering(true);
                }}
                onMouseLeave={() => setIsHovering(false)}
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
              >
                <div className="gear-knob">
                  <span className="gear-number">{index + 1}</span>
                  <span className="gear-label">{item.label}</span>
                </div>
              </motion.div>
            ))}
            
            {/* Reverse position for brands */}
            <motion.div
              className="gear-position reverse"
              onMouseEnter={() => {
                setActiveItem('brands');
                setIsHovering(true);
              }}
              onMouseLeave={() => setIsHovering(false)}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              <div className="gear-knob">
                <span className="gear-letter">R</span>
                <span className="gear-label">Brands</span>
              </div>
            </motion.div>
          </div>
          
          <div className="gear-shift-stick">
            <div className="shift-handle">
              <div className="handle-top"></div>
              <div className="handle-grip"></div>
            </div>
          </div>
        </div>
      </div>

      {/* Mega Menu Dropdown */}
      <AnimatePresence>
        {activeItem && activeItem !== 'brands' && (
          <motion.div
            className="mega-menu-dropdown"
            variants={gearVariants}
            initial="hidden"
            animate="visible"
            exit="exit"
            onMouseEnter={() => setIsHovering(true)}
            onMouseLeave={() => {
              setIsHovering(false);
              setActiveItem(null);
            }}
          >
            <div className="mega-menu-content">
              {menuData
                .find((item) => item.id === activeItem)
                ?.children?.map((child, index) => (
                  <motion.a
                    key={child.id}
                    href={`/category/${child.id}`}
                    className="mega-menu-item"
                    custom={index}
                    variants={itemVariants}
                    initial="hidden"
                    animate="visible"
                  >
                    <div className="mega-menu-icon">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="3" stroke="currentColor" strokeWidth="2" />
                        <path d="M12 2v4M12 18v4M2 12h4M18 12h4" stroke="currentColor" strokeWidth="2" />
                      </svg>
                    </div>
                    <span className="mega-menu-label">{child.label}</span>
                    <span className="mega-menu-arrow">→</span>
                  </motion.a>
                ))}
              
              <div className="mega-menu-featured">
                <h4>Featured Products</h4>
                <div className="featured-grid">
                  {[1, 2, 3].map((i) => (
                    <div key={i} className="featured-product-mini">
                      <div className="featured-image-placeholder"></div>
                      <p>Premium Part {i}</p>
                      <span className="price">$ {(Math.random() * 200 + 50).toFixed(2)}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </motion.div>
        )}

        {activeItem === 'brands' && (
          <motion.div
            className="mega-menu-dropdown brands-dropdown"
            variants={gearVariants}
            initial="hidden"
            animate="visible"
            exit="exit"
            onMouseEnter={() => setIsHovering(true)}
            onMouseLeave={() => {
              setIsHovering(false);
              setActiveItem(null);
            }}
          >
            <div className="brands-grid">
              {['Bosch', 'Denso', 'NGK', 'Brembo', 'KYB', 'Monroe', 'Gates', 'Dayco'].map(
                (brand, index) => (
                  <motion.a
                    key={brand}
                    href={`/brand/${brand.toLowerCase()}`}
                    className="brand-card"
                    custom={index}
                    variants={itemVariants}
                    initial="hidden"
                    animate="visible"
                    whileHover={{ scale: 1.05, y: -5 }}
                  >
                    <div className="brand-logo-placeholder">{brand.charAt(0)}</div>
                    <span>{brand}</span>
                  </motion.a>
                )
              )}
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Mobile Menu Toggle */}
      <button
        className="mobile-menu-toggle mobile-only"
        onClick={toggleMobileMenu}
        aria-label="Toggle menu"
      >
        <div className={`hamburger ${isMobileMenuOpen ? 'active' : ''}`}>
          <span></span>
          <span></span>
          <span></span>
        </div>
      </button>
    </nav>
  );
};

export default GearShiftNav;
