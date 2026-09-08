import { create } from 'zustand';

interface UIState {
  isMenuOpen: boolean;
  isMobileMenuOpen: boolean;
  scrollY: number;
  toggleMenu: () => void;
  closeMenu: () => void;
  openMenu: () => void;
  toggleMobileMenu: () => void;
  setScrollY: (scrollY: number) => void;
}

export const useUIStore = create<UIState>((set) => ({
  isMenuOpen: false,
  isMobileMenuOpen: false,
  scrollY: 0,
  
  toggleMenu: () => set((state) => ({ isMenuOpen: !state.isMenuOpen })),
  
  closeMenu: () => set({ isMenuOpen: false }),
  
  openMenu: () => set({ isMenuOpen: true }),
  
  toggleMobileMenu: () => set((state) => ({ isMobileMenuOpen: !state.isMobileMenuOpen })),
  
  setScrollY: (scrollY) => set({ scrollY }),
}));

// Track scroll position
if (typeof window !== 'undefined') {
  window.addEventListener('scroll', () => {
    useUIStore.setState({ scrollY: window.scrollY });
  }, { passive: true });
}
