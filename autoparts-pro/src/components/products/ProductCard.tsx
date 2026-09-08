import { useState } from 'react';
import { motion } from 'framer-motion';
import type { Product } from '../../data/mockData';
import { useWishlistStore } from '../../stores/wishlistStore';
import { useCartStore, type CartItem } from '../../stores/cartStore';

interface ProductCardProps {
  product: Product;
}

export function ProductCard({ product }: ProductCardProps) {
  const { addItem: addToWishlist, removeItem: removeFromWishlist, isInWishlist } = useWishlistStore();
  const { addItem: addToCart } = useCartStore();
  const [isHovered, setIsHovered] = useState(false);
  
  const inWishlist = isInWishlist(product.id);
  const hasDiscount = product.salePrice && product.salePrice < product.price;
  const discountPercentage = hasDiscount 
    ? Math.round(((product.price - product.salePrice!) / product.price) * 100)
    : 0;
  
  const handleAddToCart = () => {
    const cartItem: CartItem = {
      id: product.id,
      name: product.name,
      price: product.salePrice || product.price,
      quantity: 1,
      image: product.images[0],
      partNumber: product.partNumber,
    };
    addToCart(cartItem);
  };
  
  const handleWishlistToggle = () => {
    if (inWishlist) {
      removeFromWishlist(product.id);
    } else {
      addToWishlist({
        id: product.id,
        name: product.name,
        price: product.salePrice || product.price,
        image: product.images[0],
        partNumber: product.partNumber,
        inStock: product.inStock,
        addedAt: Date.now(),
      });
    }
  };
  
  return (
    <motion.div
      className="product-card bg-bg-secondary rounded-lg overflow-hidden"
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ duration: 0.3 }}
    >
      {/* Image Container */}
      <div className="relative aspect-square overflow-hidden bg-bg-tertiary">
        <img
          src={product.images[0]}
          alt={product.name}
          className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
        />
        
        {/* Badges */}
        <div className="absolute top-3 left-3 flex flex-col gap-2">
          {hasDiscount && (
            <span className="bg-accent text-white text-xs font-bold px-2 py-1 rounded">
              -{discountPercentage}%
            </span>
          )}
          {!product.inStock && (
            <span className="bg-mid-steel text-white text-xs font-bold px-2 py-1 rounded">
              Out of Stock
            </span>
          )}
          {product.isOEM && (
            <span className="bg-primary text-white text-xs font-bold px-2 py-1 rounded">
              OEM
            </span>
          )}
        </div>
        
        {/* Wishlist Button */}
        <button
          onClick={handleWishlistToggle}
          className="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/90 backdrop-blur flex items-center justify-center hover:bg-primary hover:text-white transition-colors"
        >
          <svg
            className={`w-5 h-5 ${inWishlist ? 'fill-primary text-primary' : 'text-mid-steel'}`}
            fill={inWishlist ? 'currentColor' : 'none'}
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
        </button>
        
        {/* Quick Add to Cart */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: isHovered ? 1 : 0, y: isHovered ? 0 : 20 }}
          className="absolute bottom-3 left-3 right-3"
        >
          <button
            onClick={handleAddToCart}
            disabled={!product.inStock}
            className="w-full btn-primary py-3 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {product.inStock ? 'Add to Cart' : 'Out of Stock'}
          </button>
        </motion.div>
      </div>
      
      {/* Content */}
      <div className="p-4">
        {/* Category & Brand */}
        <div className="flex items-center justify-between mb-2">
          <span className="text-xs text-mid-steel uppercase tracking-wider">
            {product.category}
          </span>
          <span className="text-xs font-heading font-bold text-primary">
            {product.brand}
          </span>
        </div>
        
        {/* Product Name */}
        <h3 className="font-heading font-bold text-lg mb-2 line-clamp-2 min-h-[3rem]">
          {product.name}
        </h3>
        
        {/* Part Number */}
        <p className="text-xs text-mid-steel font-mono mb-3">
          PN: {product.partNumber}
        </p>
        
        {/* Rating */}
        <div className="flex items-center gap-2 mb-3">
          <div className="flex">
            {[...Array(5)].map((_, i) => (
              <svg
                key={i}
                className={`w-4 h-4 ${
                  i < Math.floor(product.rating)
                    ? 'text-accent'
                    : 'text-bg-tertiary'
                }`}
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            ))}
          </div>
          <span className="text-xs text-mid-steel">
            ({product.reviewCount})
          </span>
        </div>
        
        {/* Price */}
        <div className="flex items-baseline gap-2">
          {hasDiscount ? (
            <>
              <span className="text-xl font-heading font-bold text-primary">
                ${product.salePrice!.toFixed(2)}
              </span>
              <span className="text-sm text-mid-steel line-through">
                ${product.price.toFixed(2)}
              </span>
            </>
          ) : (
            <span className="text-xl font-heading font-bold text-primary">
              ${product.price.toFixed(2)}
            </span>
          )}
        </div>
        
        {/* Stock Status */}
        {product.inStock && (
          <div className="mt-2 flex items-center gap-2">
            <span className="w-2 h-2 bg-green-500 rounded-full animate-pulse" />
            <span className="text-xs text-green-500 font-medium">
              In Stock ({product.stockQuantity})
            </span>
          </div>
        )}
      </div>
    </motion.div>
  );
}
