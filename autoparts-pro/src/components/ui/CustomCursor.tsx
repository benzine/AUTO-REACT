import { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

export function CustomCursor() {
  const [position, setPosition] = useState({ x: 0, y: 0 });
  const [isClicking, setIsClicking] = useState(false);
  const [isHovering, setIsHovering] = useState(false);
  
  useEffect(() => {
    // Only enable on non-touch devices
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      const handleMouseMove = (e: MouseEvent) => {
        setPosition({ x: e.clientX, y: e.clientY });
      };
      
      const handleMouseDown = () => setIsClicking(true);
      const handleMouseUp = () => setIsClicking(false);
      
      const handleMouseOver = (e: MouseEvent) => {
        const target = e.target as HTMLElement;
        if (
          target.tagName === 'A' ||
          target.tagName === 'BUTTON' ||
          target.closest('a') ||
          target.closest('button') ||
          target.classList.contains('cursor-pointer')
        ) {
          setIsHovering(true);
        } else {
          setIsHovering(false);
        }
      };
      
      document.addEventListener('mousemove', handleMouseMove);
      document.addEventListener('mousedown', handleMouseDown);
      document.addEventListener('mouseup', handleMouseUp);
      document.addEventListener('mouseover', handleMouseOver);
      
      return () => {
        document.removeEventListener('mousemove', handleMouseMove);
        document.removeEventListener('mousedown', handleMouseDown);
        document.removeEventListener('mouseup', handleMouseUp);
        document.removeEventListener('mouseover', handleMouseOver);
      };
    }
  }, []);
  
  return (
    <>
      {/* Main Cursor - Crosshair */}
      <motion.div
        className="fixed pointer-events-none z-[9999] hidden md:block"
        style={{
          left: position.x,
          top: position.y,
        }}
        animate={{
          x: '-50%',
          y: '-50%',
          scale: isHovering ? 1.5 : 1,
        }}
        transition={{
          type: 'spring',
          stiffness: 500,
          damping: 28,
        }}
      >
        <div className="relative">
          {/* Crosshair */}
          <div className="w-6 h-6 relative">
            <div className="absolute inset-0 flex items-center justify-center">
              <div className="w-px h-full bg-primary/50" />
              <div className="h-px w-full bg-primary/50 absolute" />
            </div>
            {/* Circle */}
            <div
              className={`absolute inset-0 border-2 rounded-full transition-colors duration-200 ${
                isHovering ? 'border-primary' : 'border-mid-steel'
              }`}
            />
          </div>
          
          {/* Click Spark Effect */}
          <AnimatePresence>
            {isClicking && (
              <motion.div
                initial={{ scale: 0, opacity: 1 }}
                animate={{ scale: 1.5, opacity: 0 }}
                exit={{ opacity: 0 }}
                className="absolute inset-0 w-6 h-6"
              >
                <div className="w-full h-full rounded-full bg-primary/30" />
              </motion.div>
            )}
          </AnimatePresence>
        </div>
      </motion.div>
      
      {/* Trailing Dots */}
      <motion.div
        className="fixed pointer-events-none z-[9998] hidden md:block"
        style={{
          left: position.x,
          top: position.y,
        }}
      >
        {[...Array(3)].map((_, i) => (
          <motion.div
            key={i}
            className="absolute w-1 h-1 rounded-full bg-primary/30"
            style={{
              x: -2 + i * 4,
              y: -2,
            }}
            animate={{
              opacity: [0.3, 0],
              scale: [1, 0.5],
            }}
            transition={{
              duration: 0.5,
              delay: i * 0.1,
              repeat: Infinity,
            }}
          />
        ))}
      </motion.div>
    </>
  );
}
