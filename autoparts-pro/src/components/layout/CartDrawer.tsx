import { motion, AnimatePresence } from 'framer-motion';
import { useCartStore } from '../../stores/cartStore';

export function CartDrawer() {
  const { items, isOpen, closeCart, removeItem, updateQuantity, getTotal } = useCartStore();
  
  const total = getTotal();
  
  return (
    <AnimatePresence>
      {isOpen && (
        <>
          {/* Backdrop */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={closeCart}
            className="fixed inset-0 bg-black/50 z-50"
          />
          
          {/* Drawer */}
          <motion.div
            initial={{ x: '100%' }}
            animate={{ x: 0 }}
            exit={{ x: '100%' }}
            transition={{ type: 'spring', damping: 25, stiffness: 200 }}
            className="fixed right-0 top-0 bottom-0 w-full max-w-md bg-bg-secondary z-50 shadow-2xl flex flex-col"
          >
            {/* Header */}
            <div className="p-6 border-b border-bg-tertiary flex items-center justify-between">
              <h2 className="text-2xl font-heading font-bold">Your Cart</h2>
              <button
                onClick={closeCart}
                className="p-2 hover:bg-bg-tertiary rounded-lg transition-colors"
              >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            {/* Items */}
            <div className="flex-1 overflow-y-auto p-6">
              {items.length === 0 ? (
                <div className="text-center py-12">
                  <svg className="w-16 h-16 mx-auto text-mid-steel mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  <p className="text-mid-steel">Your cart is empty</p>
                  <button
                    onClick={closeCart}
                    className="mt-4 btn-primary"
                  >
                    Continue Shopping
                  </button>
                </div>
              ) : (
                <div className="space-y-4">
                  {items.map((item) => (
                    <motion.div
                      key={item.id}
                      layout
                      initial={{ opacity: 0, y: 20 }}
                      animate={{ opacity: 1, y: 0 }}
                      exit={{ opacity: 0, x: -100 }}
                      className="flex gap-4 bg-bg-tertiary rounded-lg p-4"
                    >
                      <img
                        src={item.image}
                        alt={item.name}
                        className="w-20 h-20 object-cover rounded-lg"
                      />
                      <div className="flex-1">
                        <h3 className="font-heading font-bold text-sm mb-1">{item.name}</h3>
                        {item.partNumber && (
                          <p className="text-xs text-mid-steel font-mono mb-2">PN: {item.partNumber}</p>
                        )}
                        <div className="flex items-center justify-between">
                          <div className="flex items-center gap-2">
                            <button
                              onClick={() => updateQuantity(item.id, item.quantity - 1)}
                              className="w-8 h-8 rounded-lg bg-bg-secondary hover:bg-primary hover:text-white transition-colors flex items-center justify-center"
                            >
                              -
                            </button>
                            <span className="w-8 text-center font-mono">{item.quantity}</span>
                            <button
                              onClick={() => updateQuantity(item.id, item.quantity + 1)}
                              className="w-8 h-8 rounded-lg bg-bg-secondary hover:bg-primary hover:text-white transition-colors flex items-center justify-center"
                            >
                              +
                            </button>
                          </div>
                          <div className="text-right">
                            <p className="font-heading font-bold text-primary">
                              ${(item.price * item.quantity).toFixed(2)}
                            </p>
                            <button
                              onClick={() => removeItem(item.id)}
                              className="text-xs text-mid-steel hover:text-primary transition-colors"
                            >
                              Remove
                            </button>
                          </div>
                        </div>
                      </div>
                    </motion.div>
                  ))}
                </div>
              )}
            </div>
            
            {/* Footer */}
            {items.length > 0 && (
              <div className="p-6 border-t border-bg-tertiary space-y-4">
                {/* Subtotal */}
                <div className="flex items-center justify-between text-lg">
                  <span className="text-mid-steel">Subtotal</span>
                  <span className="font-heading font-bold text-xl">${total.toFixed(2)}</span>
                </div>
                
                {/* Shipping Note */}
                <p className="text-xs text-mid-steel text-center">
                  Free shipping on orders over $99
                </p>
                
                {/* Checkout Button */}
                <button className="w-full btn-primary py-4 text-lg">
                  Proceed to Checkout
                </button>
                
                {/* Continue Shopping */}
                <button
                  onClick={closeCart}
                  className="w-full py-3 text-mid-steel hover:text-primary transition-colors"
                >
                  Continue Shopping
                </button>
              </div>
            )}
          </motion.div>
        </>
      )}
    </AnimatePresence>
  );
}
