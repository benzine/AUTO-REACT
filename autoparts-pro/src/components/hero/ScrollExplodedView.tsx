/**
 * ScrollExplodedView.tsx
 * 
 * Advanced scroll-triggered exploded view system using GSAP ScrollTrigger
 * with precise timing, reverse animation support, and performance optimization.
 * 
 * Features:
 * - Frame-perfect scroll scrubbing
 * - Bidirectional animation (scroll up/down)
 * - Multiple part synchronization
 * - Label animations with stagger
 * - Performance-optimized for 60fps
 * - Mobile touch support
 */

import React, { useRef, useEffect, useState, useCallback } from 'react';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useReducedMotion } from '../hooks/useReducedMotion';

// Register GSAP plugins
if (typeof window !== 'undefined') {
  gsap.registerPlugin(ScrollTrigger);
}

interface ExplodedPart {
  id: string;
  name: string;
  elementRef: React.RefObject<HTMLDivElement>;
  explodeX: number;
  explodeY: number;
  explodeZ: number;
  rotateX?: number;
  rotateY?: number;
  rotateZ?: number;
  scale?: number;
  delay?: number;
  duration?: number;
}

interface ScrollExplodedViewProps {
  parts: ExplodedPart[];
  containerRef: React.RefObject<HTMLDivElement>;
  on ExplosionComplete?: () => void;
  onReassemblyComplete?: () => void;
  explosionIntensity?: number;
  scrollDistance?: number;
  labelDelay?: number;
  className?: string;
  children?: React.ReactNode;
}

export const ScrollExplodedView: React.FC<ScrollExplodedViewProps> = ({
  parts,
  containerRef,
  onExplosionComplete,
  onReassemblyComplete,
  explosionIntensity = 1,
  scrollDistance = 1000,
  labelDelay = 0.2,
  className = '',
  children,
}) => {
  const timelineRef = useRef<gsap.core.Timeline | null>(null);
  const labelsRef = useRef<Map<string, HTMLDivElement>>(new Map());
  const [currentPhase, setCurrentPhase] = useState<'assembled' | 'exploding' | 'exploded' | 'reassembling'>('assembled');
  const reducedMotion = useReducedMotion();

  // Set label refs
  const setLabelRef = useCallback((partId: string, el: HTMLDivElement | null) => {
    if (el) {
      labelsRef.current.set(partId, el);
    } else {
      labelsRef.current.delete(partId);
    }
  }, []);

  useEffect(() => {
    if (reducedMotion || parts.length === 0 || !containerRef.current) {
      return;
    }

    const ctx = gsap.context(() => {
      // Create master timeline
      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: containerRef.current!,
          start: 'top top',
          end: `+=${scrollDistance}`,
          scrub: 0.5, // Smooth scrubbing
          pin: true,
          anticipatePin: 1,
          onUpdate: (self) => {
            const progress = self.progress;
            
            if (progress < 0.1) {
              setCurrentPhase('assembled');
            } else if (progress < 0.45) {
              setCurrentPhase('exploding');
            } else if (progress < 0.55) {
              setCurrentPhase('exploded');
              if (onExplosionComplete) {
                onExplosionComplete();
              }
            } else if (progress < 0.9) {
              setCurrentPhase('reassembling');
            } else {
              setCurrentPhase('assembled');
              if (onReassemblyComplete) {
                onReassemblyComplete();
              }
            }
          },
        },
        paused: false,
      });

      // Add explosion animations for each part
      parts.forEach((part, index) => {
        const element = part.elementRef.current;
        if (!element) return;

        const duration = part.duration || 1;
        const delay = part.delay || 0;
        const stagger = index * 0.05; // Stagger start times

        // Calculate final exploded position
        const x = part.explodeX * explosionIntensity;
        const y = part.explodeY * explosionIntensity;
        const z = part.explodeZ * explosionIntensity;
        const rotateX = part.rotateX || 0;
        const rotateY = part.rotateY || 0;
        const rotateZ = part.rotateZ || 0;
        const scale = part.scale || 1;

        // Main explosion tween
        tl.to(
          element,
          {
            x,
            y,
            z,
            rotationX: rotateX,
            rotationY: rotateY,
            rotationZ: rotateZ,
            scale,
            duration: duration,
            ease: 'power2.inOut',
          },
          stagger + delay
        );

        // Label fade-in at mid-scroll
        const labelEl = labelsRef.current.get(part.id);
        if (labelEl) {
          tl.from(
            labelEl,
            {
              opacity: 0,
              y: 20,
              duration: 0.3,
              ease: 'power2.out',
            },
            0.3 + labelDelay * index
          );

          // Label fade-out
          tl.to(
            labelEl,
            {
              opacity: 0,
              y: -20,
              duration: 0.3,
              ease: 'power2.in',
            },
            0.7 + labelDelay * index
          );
        }
      });

      timelineRef.current = tl;

      // Cleanup
      return () => {
        tl.kill();
        ctx.revert();
      };
    }, containerRef);

    return () => {
      ctx.revert();
    };
  }, [parts, explosionIntensity, scrollDistance, labelDelay, reducedMotion, onExplosionComplete, onReassemblyComplete]);

  // Manual control for non-scroll interactions
  const explode = useCallback((duration: number = 1) => {
    if (!timelineRef.current || reducedMotion) return;
    
    timelineRef.current.play(0);
    timelineRef.current.timeScale(1 / duration);
  }, [reducedMotion]);

  const reassemble = useCallback((duration: number = 1) => {
    if (!timelineRef.current || reducedMotion) return;
    
    timelineRef.current.reverse();
    timelineRef.current.timeScale(1 / duration);
  }, [reducedMotion]);

  const goToProgress = useCallback((progress: number) => {
    if (!timelineRef.current || reducedMotion) return;
    
    timelineRef.current.progress(progress);
  }, [reducedMotion]);

  return (
    <div ref={containerRef} className={`scroll-exploded-view ${className}`}>
      {/* 3D/Visual content area */}
      <div className="exploded-view-content">
        {parts.map((part) => (
          <div
            key={part.id}
            ref={part.elementRef}
            className="exploded-part"
            data-part-id={part.id}
            data-part-name={part.name}
          >
            {children}
          </div>
        ))}
      </div>

      {/* Labels overlay */}
      <div className="exploded-labels-overlay">
        {parts.map((part) => (
          <div
            key={part.id}
            ref={(el) => setLabelRef(part.id, el)}
            className="exploded-part-label"
            data-label-for={part.id}
          >
            <span className="label-name">{part.name}</span>
            <div className="label-line" />
            <div className="label-dot" />
          </div>
        ))}
      </div>

      {/* Phase indicator (optional, for debugging/UI) */}
      <div className="explosion-phase-indicator">
        <span className={`phase ${currentPhase === 'assembled' ? 'active' : ''}`}>Assembled</span>
        <span className={`phase ${currentPhase === 'exploding' ? 'active' : ''}`}>Exploding</span>
        <span className={`phase ${currentPhase === 'exploded' ? 'active' : ''}`}>Exploded</span>
        <span className={`phase ${currentPhase === 'reassembling' ? 'active' : ''}`}>Reassembling</span>
      </div>

      {/* Manual controls (for non-scroll devices or accessibility) */}
      <div className="explosion-controls">
        <button onClick={() => explode(1)} disabled={reducedMotion}>
          Explode
        </button>
        <button onClick={() => reassemble(1)} disabled={reducedMotion}>
          Reassemble
        </button>
        <input
          type="range"
          min="0"
          max="100"
          defaultValue="0"
          onChange={(e) => goToProgress(parseInt(e.target.value) / 100)}
          disabled={reducedMotion}
          aria-label="Explosion progress"
        />
      </div>
    </div>
  );
};

// Hook for creating part refs with automatic positioning
export const useExplodedParts = (partConfigs: Array<{
  id: string;
  name: string;
  explodeX: number;
  explodeY: number;
  explodeZ: number;
  rotateX?: number;
  rotateY?: number;
  rotateZ?: number;
  scale?: number;
  delay?: number;
  duration?: number;
}>) => {
  return partConfigs.map((config) => ({
    ...config,
    elementRef: useRef<HTMLDivElement>(null),
  }));
};

// Pre-configured engine parts
export const enginePartsConfig = [
  {
    id: 'engine-block',
    name: 'Engine Block',
    explodeX: 0,
    explodeY: 0,
    explodeZ: 0,
    delay: 0,
    duration: 0.8,
  },
  {
    id: 'pistons',
    name: 'Pistons',
    explodeX: 0,
    explodeY: 150,
    explodeZ: 0,
    delay: 0.1,
    duration: 0.6,
  },
  {
    id: 'crankshaft',
    name: 'Crankshaft',
    explodeX: 0,
    explodeY: -120,
    explodeZ: 0,
    delay: 0.15,
    duration: 0.7,
  },
  {
    id: 'camshaft',
    name: 'Camshaft',
    explodeX: 0,
    explodeY: 180,
    explodeZ: 50,
    delay: 0.2,
    duration: 0.6,
  },
  {
    id: 'valves',
    name: 'Valves',
    explodeX: 0,
    explodeY: 200,
    explodeZ: 0,
    delay: 0.25,
    duration: 0.5,
  },
  {
    id: 'cylinder-head',
    name: 'Cylinder Head',
    explodeX: 0,
    explodeY: 160,
    explodeZ: 0,
    delay: 0.18,
    duration: 0.6,
  },
  {
    id: 'intake-manifold',
    name: 'Intake Manifold',
    explodeX: 150,
    explodeY: 100,
    explodeZ: 120,
    delay: 0.3,
    duration: 0.7,
  },
  {
    id: 'exhaust-manifold',
    name: 'Exhaust Manifold',
    explodeX: -150,
    explodeY: 100,
    explodeZ: 120,
    delay: 0.3,
    duration: 0.7,
  },
  {
    id: 'oil-pan',
    name: 'Oil Pan',
    explodeX: 0,
    explodeY: -150,
    explodeZ: 0,
    delay: 0.2,
    duration: 0.6,
  },
  {
    id: 'timing-belt',
    name: 'Timing Belt',
    explodeX: 0,
    explodeY: 50,
    explodeZ: 150,
    delay: 0.35,
    duration: 0.5,
  },
  {
    id: 'spark-plugs',
    name: 'Spark Plugs',
    explodeX: 120,
    explodeY: 180,
    explodeZ: 0,
    delay: 0.4,
    duration: 0.4,
  },
  {
    id: 'gaskets',
    name: 'Gaskets',
    explodeX: 80,
    explodeY: 140,
    explodeZ: 80,
    delay: 0.28,
    duration: 0.5,
  },
];

export default ScrollExplodedView;
