/**
 * Main JavaScript - AutoParts Pro Theme
 * React.js-level interactivity with GSAP animations
 */

(function($) {
    'use strict';

    // Initialize GSAP ScrollTrigger
    gsap.registerPlugin(ScrollTrigger);

    // ==========================================
    // THEME STATE MANAGEMENT
    // ==========================================
    
    const AppState = {
        darkMode: localStorage.getItem('autoparts-dark-mode') === 'true' || 
                  (!localStorage.getItem('autoparts-dark-mode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        wishlist: JSON.parse(localStorage.getItem('autoparts-wishlist')) || [],
        garage: JSON.parse(localStorage.getItem('autoparts-garage')) || [],
        cursorEnabled: true
    };

    // ==========================================
    // DARK/LIGHT MODE TOGGLE
    // ==========================================
    
    function initDarkMode() {
        const toggle = document.querySelector('.dark-mode-toggle');
        const body = document.body;
        
        // Apply initial state
        if (AppState.darkMode) {
            body.classList.add('dark-mode');
        }
        
        // Toggle handler
        if (toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                AppState.darkMode = !AppState.darkMode;
                
                body.classList.toggle('dark-mode');
                localStorage.setItem('autoparts-dark-mode', AppState.darkMode);
                
                // Animate toggle
                gsap.fromTo(toggle, 
                    { rotation: -180, scale: 0.8 },
                    { rotation: 0, scale: 1, duration: 0.5, ease: 'back.out(1.7)' }
                );
                
                // Update icon
                const icon = toggle.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-sun');
                    icon.classList.toggle('fa-moon');
                }
            });
        }
        
        // Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('autoparts-dark-mode')) {
                AppState.darkMode = e.matches;
                body.classList.toggle('dark-mode', AppState.darkMode);
            }
        });
    }

    // ==========================================
    // CUSTOM CURSOR
    // ==========================================
    
    function initCustomCursor() {
        if (window.innerWidth < 768) return; // Disable on mobile
        
        const cursor = document.createElement('div');
        cursor.className = 'custom-cursor';
        cursor.innerHTML = `
            <div class="cursor-default">
                <svg width="24" height="24" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="2" fill="currentColor"/>
                    <line x1="12" y1="2" x2="12" y2="6" stroke="currentColor" stroke-width="1"/>
                    <line x1="12" y1="18" x2="12" y2="22" stroke="currentColor" stroke-width="1"/>
                    <line x1="2" y1="12" x2="6" y2="12" stroke="currentColor" stroke-width="1"/>
                    <line x1="18" y1="12" x2="22" y2="12" stroke="currentColor" stroke-width="1"/>
                </svg>
            </div>
            <div class="cursor-hover">
                <svg width="32" height="32" viewBox="0 0 32 32">
                    <path d="M16 2L18 8L24 8L20 12L22 18L16 14L10 18L12 12L8 8L14 8Z" fill="currentColor"/>
                </svg>
            </div>
        `;
        document.body.appendChild(cursor);

        const cursorDefault = cursor.querySelector('.cursor-default');
        const cursorHover = cursor.querySelector('.cursor-hover');
        
        let mouseX = 0, mouseY = 0;
        let cursorX = 0, cursorY = 0;
        
        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });
        
        function animateCursor() {
            const dx = mouseX - cursorX;
            const dy = mouseY - cursorY;
            
            cursorX += dx * 0.15;
            cursorY += dy * 0.15;
            
            cursor.style.transform = `translate(${cursorX}px, ${cursorY}px)`;
            
            requestAnimationFrame(animateCursor);
        }
        animateCursor();
        
        // Hover states
        const hoverElements = document.querySelectorAll('a, button, .clickable, input, textarea');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorDefault.style.opacity = '0';
                cursorHover.style.opacity = '1';
                cursor.classList.add('hovering');
            });
            el.addEventListener('mouseleave', () => {
                cursorDefault.style.opacity = '1';
                cursorHover.style.opacity = '0';
                cursor.classList.remove('hovering');
            });
        });
        
        // Click effect
        document.addEventListener('click', (e) => {
            const spark = document.createElement('div');
            spark.className = 'cursor-spark';
            spark.style.left = e.clientX + 'px';
            spark.style.top = e.clientY + 'px';
            document.body.appendChild(spark);
            
            gsap.to(spark, {
                scale: 2,
                opacity: 0,
                duration: 0.5,
                onComplete: () => spark.remove()
            });
        });
    }

    // ==========================================
    // SCROLL-TRIGGERED EXPLODED VIEW HERO
    // ==========================================
    
    function initExplodedViewHero() {
        const heroSection = document.querySelector('.hero-exploded-view');
        if (!heroSection) return;
        
        const engineModel = heroSection.querySelector('.engine-model');
        const parts = heroSection.querySelectorAll('.engine-part');
        const labels = heroSection.querySelectorAll('.part-label');
        
        // Initial state - assembled engine
        gsap.set(parts, { 
            x: 0, 
            y: 0, 
            z: 0, 
            rotationX: 0, 
            rotationY: 0, 
            rotationZ: 0,
            opacity: 1 
        });
        
        gsap.set(labels, { opacity: 0, scale: 0 });
        
        // Create timeline linked to scroll
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: heroSection,
                start: 'top top',
                end: 'bottom top',
                scrub: 1,
                pin: true,
                anticipatePin: 1
            }
        });
        
        // Explode animation - parts separate
        parts.forEach((part, index) => {
            const explodeX = (Math.random() - 0.5) * 400;
            const explodeY = (Math.random() - 0.5) * 400;
            const explodeZ = Math.random() * 300 + 200;
            const rotateX = (Math.random() - 0.5) * 90;
            const rotateY = (Math.random() - 0.5) * 90;
            
            tl.to(part, {
                x: explodeX,
                y: explodeY,
                z: explodeZ,
                rotationX: rotateX,
                rotationY: rotateY,
                rotationZ: (Math.random() - 0.5) * 180,
                duration: 2,
                ease: 'power2.inOut'
            }, index * 0.1);
        });
        
        // Show labels at mid-scroll
        tl.to(labels, {
            opacity: 1,
            scale: 1,
            stagger: 0.1,
            duration: 0.5
        }, '-=1');
        
        // Fade out at end
        tl.to([engineModel, labels], {
            opacity: 0,
            duration: 1
        });
    }

    // ==========================================
    // WISHLIST SYSTEM
    // ==========================================
    
    function initWishlist() {
        // Load wishlist from localStorage
        const wishlistBtns = document.querySelectorAll('.wishlist-btn');
        const wishlistCounter = document.querySelector('.wishlist-counter');
        
        function updateCounter() {
            if (wishlistCounter) {
                wishlistCounter.textContent = AppState.wishlist.length;
                gsap.fromTo(wishlistCounter, 
                    { scale: 1.5 },
                    { scale: 1, duration: 0.3, ease: 'back.out' }
                );
            }
        }
        
        updateCounter();
        
        wishlistBtns.forEach(btn => {
            const productId = btn.dataset.productId;
            const isInWishlist = AppState.wishlist.includes(productId);
            
            if (isInWishlist) {
                btn.classList.add('active');
                btn.querySelector('i').classList.remove('fa-heart-o');
                btn.querySelector('i').classList.add('fa-heart');
            }
            
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const index = AppState.wishlist.indexOf(productId);
                
                if (index > -1) {
                    // Remove from wishlist
                    AppState.wishlist.splice(index, 1);
                    this.classList.remove('active');
                    this.querySelector('i').classList.remove('fa-heart');
                    this.querySelector('i').classList.add('fa-heart-o');
                    
                    // Animate removal
                    gsap.to(this, {
                        scale: 0.8,
                        duration: 0.2,
                        yoyo: true,
                        repeat: 1
                    });
                } else {
                    // Add to wishlist
                    AppState.wishlist.push(productId);
                    this.classList.add('active');
                    this.querySelector('i').classList.remove('fa-heart-o');
                    this.querySelector('i').classList.add('fa-heart');
                    
                    // Animation
                    gsap.fromTo(this,
                        { scale: 0.8, rotation: -15 },
                        { scale: 1, rotation: 0, duration: 0.4, ease: 'back.out(1.7)' }
                    );
                    
                    // Show toast notification
                    showToast('Added to wishlist!', 'success');
                }
                
                localStorage.setItem('autoparts-wishlist', JSON.stringify(AppState.wishlist));
                updateCounter();
                
                // Sync with server if logged in
                if (typeof autopartsPro !== 'undefined') {
                    $.ajax({
                        url: autopartsPro.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'autoparts_update_wishlist',
                            product_id: productId,
                            wishlist: AppState.wishlist,
                            nonce: autopartsPro.nonce
                        }
                    });
                }
            });
        });
    }

    // ==========================================
    // TOAST NOTIFICATIONS
    // ==========================================
    
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        gsap.fromTo(toast,
            { x: 100, opacity: 0 },
            { x: 0, opacity: 1, duration: 0.3 }
        );
        
        setTimeout(() => {
            gsap.to(toast, {
                x: 100,
                opacity: 0,
                duration: 0.3,
                onComplete: () => toast.remove()
            });
        }, 3000);
    }

    // ==========================================
    // AI CHATBOT WIDGET
    // ==========================================
    
    function initChatbot() {
        const chatbotToggle = document.querySelector('.chatbot-toggle');
        const chatbotWidget = document.querySelector('.chatbot-widget');
        
        if (!chatbotToggle || !chatbotWidget) return;
        
        let autoOpenTimeout;
        let autoCloseTimeout;
        let hasInteracted = false;
        
        // Auto-open after 3-5 seconds
        autoOpenTimeout = setTimeout(() => {
            if (!hasInteracted) {
                openChatbot();
                
                // Auto-close after 3 seconds if no interaction
                autoCloseTimeout = setTimeout(() => {
                    if (!hasInteracted) {
                        closeChatbot();
                    }
                }, 3000);
            }
        }, 4000);
        
        function openChatbot() {
            gsap.fromTo(chatbotWidget,
                { y: 50, opacity: 0, scale: 0.9 },
                { y: 0, opacity: 1, scale: 1, duration: 0.4, ease: 'back.out(1.2)' }
            );
            chatbotWidget.classList.add('open');
            
            // Play subtle mechanical click sound
            playMechanicalSound();
        }
        
        function closeChatbot() {
            gsap.to(chatbotWidget, {
                y: 50,
                opacity: 0,
                scale: 0.9,
                duration: 0.3
            });
            chatbotWidget.classList.remove('open');
        }
        
        chatbotToggle.addEventListener('click', function() {
            hasInteracted = true;
            clearTimeout(autoOpenTimeout);
            clearTimeout(autoCloseTimeout);
            
            if (chatbotWidget.classList.contains('open')) {
                closeChatbot();
            } else {
                openChatbot();
            }
        });
        
        function playMechanicalSound() {
            // Optional: Add mechanical click sound effect
            // const audio = new Audio('/path/to/click.mp3');
            // audio.volume = 0.3;
            // audio.play();
        }
    }

    // ==========================================
    // BACK TO TOP BUTTON
    // ==========================================
    
    function initBackToTop() {
        const backToTop = document.querySelector('.back-to-top');
        if (!backToTop) return;
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        
        backToTop.addEventListener('click', (e) => {
            e.preventDefault();
            gsap.to(window, {
                scrollTo: 0,
                duration: 1,
                ease: 'power2.inOut'
            });
        });
    }

    // ==========================================
    // MEGA MENU INTERACTIONS
    // ==========================================
    
    function initMegaMenu() {
        const menuItems = document.querySelectorAll('.menu-item-has-children');
        
        menuItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const megaMenu = this.querySelector('.mega-menu');
                if (megaMenu) {
                    gsap.fromTo(megaMenu,
                        { y: 20, opacity: 0 },
                        { y: 0, opacity: 1, duration: 0.3, ease: 'power2.out' }
                    );
                }
            });
            
            item.addEventListener('mouseleave', function() {
                const megaMenu = this.querySelector('.mega-menu');
                if (megaMenu) {
                    gsap.to(megaMenu, {
                        y: 20,
                        opacity: 0,
                        duration: 0.2
                    });
                }
            });
        });
    }

    // ==========================================
    // PRODUCT FILTERING (React-like State)
    // ==========================================
    
    function initProductFiltering() {
        const filterControls = document.querySelectorAll('.product-filter');
        const productGrid = document.querySelector('.products-grid');
        
        if (!filterControls || !productGrid) return;
        
        filterControls.forEach(filter => {
            filter.addEventListener('change', function() {
                const filters = {};
                filterControls.forEach(f => {
                    if (f.value) {
                        filters[f.name] = f.value;
                    }
                });
                
                // Animate products out
                gsap.to(productGrid.querySelectorAll('.product'), {
                    opacity: 0,
                    y: 20,
                    stagger: 0.05,
                    duration: 0.3,
                    onComplete: () => {
                        // In real implementation, this would fetch filtered products via AJAX
                        // For now, we'll just animate them back in
                        gsap.to(productGrid.querySelectorAll('.product'), {
                            opacity: 1,
                            y: 0,
                            stagger: 0.05,
                            duration: 0.3
                        });
                    }
                });
            });
        });
    }

    // ==========================================
    // VEHICLE COMPATIBILITY SELECTOR
    // ==========================================
    
    function initVehicleSelector() {
        const vehicleForm = document.querySelector('.vehicle-selector-form');
        if (!vehicleForm) return;
        
        const yearSelect = vehicleForm.querySelector('[name="vehicle_year"]');
        const makeSelect = vehicleForm.querySelector('[name="vehicle_make"]');
        const modelSelect = vehicleForm.querySelector('[name="vehicle_model"]');
        
        // Cascade filtering logic
        if (yearSelect && makeSelect) {
            yearSelect.addEventListener('change', function() {
                // Filter makes based on year
                const selectedYear = this.value;
                // In real implementation, fetch makes for selected year
                console.log('Year selected:', selectedYear);
            });
        }
        
        if (makeSelect && modelSelect) {
            makeSelect.addEventListener('change', function() {
                // Filter models based on make
                const selectedMake = this.value;
                // In real implementation, fetch models for selected make
                console.log('Make selected:', selectedMake);
            });
        }
    }

    // ==========================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ==========================================
    
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    gsap.to(window, {
                        scrollTo: target,
                        duration: 1,
                        ease: 'power2.inOut',
                        offsetY: 80 // Account for fixed header
                    });
                }
            });
        });
    }

    // ==========================================
    // LAZY LOADING IMAGES
    // ==========================================
    
    function initLazyLoading() {
        const lazyImages = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        }, { threshold: 0.1 });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    }

    // ==========================================
    // COUNTDOWN TIMERS (Flash Deals)
    // ==========================================
    
    function initCountdownTimers() {
        const timers = document.querySelectorAll('.countdown-timer');
        
        timers.forEach(timer => {
            const endTime = new Date(timer.dataset.endTime).getTime();
            
            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    timer.innerHTML = '<span class="expired">EXPIRED</span>';
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                timer.innerHTML = `
                    <div class="countdown-segment"><span class="number">${days}</span><span class="label">Days</span></div>
                    <div class="countdown-segment"><span class="number">${hours}</span><span class="label">Hours</span></div>
                    <div class="countdown-segment"><span class="number">${minutes}</span><span class="label">Minutes</span></div>
                    <div class="countdown-segment"><span class="number">${seconds}</span><span class="label">Seconds</span></div>
                `;
            };
            
            updateTimer();
            setInterval(updateTimer, 1000);
        });
    }

    // ==========================================
    // INITIALIZATION
    // ==========================================
    
    $(document).ready(function() {
        initDarkMode();
        initCustomCursor();
        initExplodedViewHero();
        initWishlist();
        initChatbot();
        initBackToTop();
        initMegaMenu();
        initProductFiltering();
        initVehicleSelector();
        initSmoothScroll();
        initLazyLoading();
        initCountdownTimers();
        
        // Add loaded class to body for CSS transitions
        document.body.classList.add('loaded');
        
        console.log('🏎️ AutoParts Pro - Premium Automotive Theme Loaded');
    });

})(jQuery);
