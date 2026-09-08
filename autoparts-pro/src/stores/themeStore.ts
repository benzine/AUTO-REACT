import { create } from 'zustand';

interface ThemeState {
  isDark: boolean;
  toggleTheme: () => void;
  setTheme: (isDark: boolean) => void;
}

export const useThemeStore = create<ThemeState>((set) => ({
  isDark: false,
  
  toggleTheme: () => set((state) => {
    const newIsDark = !state.isDark;
    
    // Update document class for Tailwind dark mode
    if (newIsDark) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
    
    // Save to localStorage
    localStorage.setItem('autoparts-theme', newIsDark ? 'dark' : 'light');
    
    return { isDark: newIsDark };
  }),
  
  setTheme: (isDark: boolean) => {
    if (isDark) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
    
    localStorage.setItem('autoparts-theme', isDark ? 'dark' : 'light');
    set({ isDark });
  },
}));

// Initialize theme on load (client-side only)
if (typeof window !== 'undefined') {
  const savedTheme = localStorage.getItem('autoparts-theme');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  const shouldUseDark = savedTheme === 'dark' || (!savedTheme && systemPrefersDark);
  
  if (shouldUseDark) {
    document.documentElement.classList.add('dark');
    useThemeStore.setState({ isDark: true });
  }
}
