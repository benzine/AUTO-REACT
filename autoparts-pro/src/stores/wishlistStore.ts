import { create } from 'zustand';
import { persist, createJSONStorage } from 'zustand/middleware';

export interface WishlistItem {
  id: string;
  name: string;
  price: number;
  image: string;
  partNumber?: string;
  inStock: boolean;
  addedAt: number;
}

interface WishlistState {
  items: WishlistItem[];
  addItem: (item: WishlistItem) => void;
  removeItem: (id: string) => void;
  isInWishlist: (id: string) => boolean;
  clearWishlist: () => void;
  getItemCount: () => number;
}

export const useWishlistStore = create<WishlistState>()(
  persist(
    (set, get) => ({
      items: [],
      
      addItem: (item) => set((state) => {
        if (state.items.find((i) => i.id === item.id)) {
          return state;
        }
        return { items: [...state.items, { ...item, addedAt: Date.now() }] };
      }),
      
      removeItem: (id) => set((state) => ({
        items: state.items.filter((item) => item.id !== id),
      })),
      
      isInWishlist: (id) => {
        return get().items.some((item) => item.id === id);
      },
      
      clearWishlist: () => set({ items: [] }),
      
      getItemCount: () => {
        return get().items.length;
      },
    }),
    {
      name: 'autoparts-wishlist',
      storage: createJSONStorage(() => localStorage),
    }
  )
);
