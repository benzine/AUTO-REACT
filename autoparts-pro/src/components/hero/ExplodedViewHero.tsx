/**
 * ExplodedViewHero.tsx
 * 
 * Signature scroll-triggered exploded view animation component.
 * Uses Three.js with React Three Fiber for 3D rendering.
 * Features:
 * - Scroll-triggered explosion/reassembly animation
 * - Individual part separation with labels
 * - Parallax depth layers
 * - WebGL fallback to video/image sequence
 * - Mobile-optimized simplified version
 * - Respects prefers-reduced-motion
 */

import React, { useRef, useMemo, useState, useEffect } from 'react';
import { Canvas, useFrame, useThree, extend } from '@react-three/fiber';
import { useGLTF, useScroll, Html, Environment, ContactShadows } from '@react-three/drei';
import * as THREE from 'three';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Register GSAP plugin
if (typeof window !== 'undefined') {
  gsap.registerPlugin(ScrollTrigger);
}

// Extend Drei with custom shaders if needed
extend({});

// Types
interface PartProps {
  url: string;
  position: [number, number, number];
  rotation?: [number, number, number];
  scale?: [number, number, number];
  explodeOffset: [number, number, number];
  label: string;
  partId: string;
  onPartClick?: (partId: string) => void;
}

interface ExplodedViewHeroProps {
  modelUrl?: string;
  videoFallbackUrl?: string;
  imageFallbackUrl?: string;
  explosionIntensity?: number;
  scrollDistance?: number;
  autoRotate?: boolean;
  showLabels?: boolean;
  className?: string;
}

// Part component with individual explosion behavior
const ExplodablePart: React.FC<PartProps> = ({
  url,
  position,
  rotation = [0, 0, 0],
  scale = [1, 1, 1],
  explodeOffset,
  label,
  partId,
  onPartClick,
}) => {
  const meshRef = useRef<THREE.Group>(null);
  const { nodes } = useGLTF(url);
  const scroll = useScroll();
  const [isHovered, setIsHovered] = useState(false);
  const [showLabel, setShowLabel] = useState(false);

  // Calculate current position based on scroll
  useFrame(() => {
    if (!meshRef.current) return;

    // Scroll value ranges from 0 to 1 over the hero section
    const scrollProgress = scroll.range(0, 1);
    
    // Explosion curve: starts at 0, peaks at 0.5, returns to 0 at 1
    const explosionPhase = Math.sin(scrollProgress * Math.PI);
    
    // Apply explosion offset with easing
    const currentOffset: [number, number, number] = [
      explodeOffset[0] * explosionPhase,
      explodeOffset[1] * explosionPhase,
      explodeOffset[2] * explosionPhase,
    ];

    // Smoothly interpolate position
    meshRef.current.position.lerp(
      new THREE.Vector3(
        position[0] + currentOffset[0],
        position[1] + currentOffset[1],
        position[2] + currentOffset[2]
      ),
      0.1
    );

    // Show labels during mid-scroll (0.3 to 0.7)
    setShowLabel(scrollProgress > 0.3 && scrollProgress < 0.7);

    // Subtle rotation animation
    if (isHovered) {
      meshRef.current.rotation.y += 0.02;
    }
  });

  const handleClick = () => {
    if (onPartClick) {
      onPartClick(partId);
    }
    // Spark effect could be triggered here
  };

  return (
    <>
      <group
        ref={meshRef}
        position={position}
        rotation={rotation}
        scale={scale}
        onClick={handleClick}
        onPointerOver={() => setIsHovered(true)}
        onPointerOut={() => setIsHovered(false)}
      >
        {/* Render the actual 3D model parts */}
        <primitive object={nodes.Scene || nodes.scene} />
        
        {/* Hover highlight effect */}
        {isHovered && (
          <meshBasicMaterial color="#DC2626" transparent opacity={0.3} />
        )}
      </group>

      {/* Label that appears during explosion */}
      {showLabel && (
        <Html
          position={[
            position[0] + explodeOffset[0] * 0.5,
            position[1] + explodeOffset[1] * 0.5 + 0.5,
            position[2] + explodeOffset[2] * 0.5,
          ]}
          center
          distanceFactor={10}
        >
          <div className="exploded-part-label">
            <span className="label-text">{label}</span>
            <div className="label-line" />
          </div>
        </Html>
      )}
    </>
  );
};

// Complete engine assembly with multiple explodable parts
const EngineAssembly: React.FC<{
  explosionIntensity: number;
  showLabels: boolean;
  onPartClick: (partId: string) => void;
}> = ({ explosionIntensity, showLabels, onPartClick }) => {
  // Define engine parts with their explosion offsets
  const parts = useMemo(() => [
    {
      id: 'engine-block',
      url: '/assets/3d/engine-block.glb',
      position: [0, 0, 0] as [number, number, number],
      explodeOffset: [0, 0, 0] as [number, number, number], // Stationary
      label: 'Engine Block',
    },
    {
      id: 'pistons',
      url: '/assets/3d/pistons.glb',
      position: [0, 0.2, 0] as [number, number, number],
      explodeOffset: [0, 1.5 * explosionIntensity, 0] as [number, number, number],
      label: 'Pistons',
    },
    {
      id: 'crankshaft',
      url: '/assets/3d/crankshaft.glb',
      position: [0, -0.3, 0] as [number, number, number],
      explodeOffset: [0, -1.2 * explosionIntensity, 0] as [number, number, number],
      label: 'Crankshaft',
    },
    {
      id: 'camshaft',
      url: '/assets/3d/camshaft.glb',
      position: [0, 0.5, 0.2] as [number, number, number],
      explodeOffset: [0, 1.8 * explosionIntensity, 0.5 * explosionIntensity] as [number, number, number],
      label: 'Camshaft',
    },
    {
      id: 'valves',
      url: '/assets/3d/valves.glb',
      position: [0, 0.8, 0] as [number, number, number],
      explodeOffset: [0, 2 * explosionIntensity, 0] as [number, number, number],
      label: 'Valves',
    },
    {
      id: 'cylinder-head',
      url: '/assets/3d/cylinder-head.glb',
      position: [0, 0.6, 0] as [number, number, number],
      explodeOffset: [0, 1.6 * explosionIntensity, 0] as [number, number, number],
      label: 'Cylinder Head',
    },
    {
      id: 'intake-manifold',
      url: '/assets/3d/intake-manifold.glb',
      position: [0.5, 0.4, 0.3] as [number, number, number],
      explodeOffset: [1.5 * explosionIntensity, 1 * explosionIntensity, 1.2 * explosionIntensity] as [number, number, number],
      label: 'Intake Manifold',
    },
    {
      id: 'exhaust-manifold',
      url: '/assets/3d/exhaust-manifold.glb',
      position: [-0.5, 0.4, 0.3] as [number, number, number],
      explodeOffset: [-1.5 * explosionIntensity, 1 * explosionIntensity, 1.2 * explosionIntensity] as [number, number, number],
      label: 'Exhaust Manifold',
    },
    {
      id: 'oil-pan',
      url: '/assets/3d/oil-pan.glb',
      position: [0, -0.8, 0] as [number, number, number],
      explodeOffset: [0, -1.5 * explosionIntensity, 0] as [number, number, number],
      label: 'Oil Pan',
    },
    {
      id: 'timing-belt',
      url: '/assets/3d/timing-belt.glb',
      position: [0, 0.3, 0.5] as [number, number, number],
      explodeOffset: [0, 0.5 * explosionIntensity, 1.5 * explosionIntensity] as [number, number, number],
      label: 'Timing Belt',
    },
    {
      id: 'spark-plugs',
      url: '/assets/3d/spark-plugs.glb',
      position: [0.3, 0.7, 0] as [number, number, number],
      explodeOffset: [1.2 * explosionIntensity, 1.8 * explosionIntensity, 0] as [number, number, number],
      label: 'Spark Plugs',
    },
    {
      id: 'gaskets',
      url: '/assets/3d/gaskets.glb',
      position: [0, 0.55, 0] as [number, number, number],
      explodeOffset: [0.8 * explosionIntensity, 1.4 * explosionIntensity, 0.8 * explosionIntensity] as [number, number, number],
      label: 'Gaskets',
    },
  ], [explosionIntensity]);

  return (
    <group>
      {parts.map((part) => (
        <ExplodablePart
          key={part.id}
          partId={part.id}
          url={part.url}
          position={part.position}
          explodeOffset={part.explodeOffset}
          label={part.label}
          showLabels={showLabels}
          onPartClick={onPartClick}
        />
      ))}
    </group>
  );
};

// Fallback video component for non-WebGL devices
const VideoFallback: React.FC<{ videoUrl: string; imageUrl: string }> = ({
  videoUrl,
  imageUrl,
}) => {
  const videoRef = useRef<HTMLVideoElement>(null);
  const containerRef = useRef<HTMLDivElement>(null);
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(() => {
    // Sync video playback with scroll
    const handleScroll = () => {
      if (!videoRef.current || !containerRef.current) return;

      const rect = containerRef.current.getBoundingClientRect();
      const viewportHeight = window.innerHeight;
      
      // Calculate scroll progress through the hero section
      const scrollProgress = Math.max(
        0,
        Math.min(1, (viewportHeight - rect.top) / (viewportHeight + rect.height))
      );

      // Set video currentTime based on scroll position
      const duration = videoRef.current.duration;
      if (duration) {
        videoRef.current.currentTime = scrollProgress * duration;
      }
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <div ref={containerRef} className="hero-video-fallback">
      {!isLoaded && (
        <img
          src={imageUrl}
          alt="Engine exploded view"
          className="hero-fallback-image"
        />
      )}
      <video
        ref={videoRef}
        src={videoUrl}
        muted
        playsInline
        preload="auto"
        onLoadedData={() => setIsLoaded(true)}
        className={`hero-fallback-video ${isLoaded ? 'loaded' : ''}`}
      />
      <div className="scroll-indicator">
        <span>Scroll to explore</span>
        <div className="scroll-arrow" />
      </div>
    </div>
  );
};

// Image sequence fallback for medium-performance devices
const ImageSequenceFallback: React.FC<{
  imagePrefix: string;
  totalFrames: number;
}> = ({ imagePrefix, totalFrames }) => {
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const containerRef = useRef<HTMLDivElement>(null);
  const images = useRef<HTMLImageElement[]>([]);
  const [currentFrame, setCurrentFrame] = useState(1);
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(() => {
    // Preload all images
    let loadedCount = 0;
    
    for (let i = 1; i <= totalFrames; i++) {
      const img = new Image();
      img.src = `${imagePrefix}-${String(i).padStart(4, '0')}.jpg`;
      img.onload = () => {
        loadedCount++;
        if (loadedCount === totalFrames) {
          setIsLoaded(true);
        }
      };
      images.current[i - 1] = img;
    }
  }, [imagePrefix, totalFrames]);

  useEffect(() => {
    const handleScroll = () => {
      if (!containerRef.current || !canvasRef.current) return;

      const rect = containerRef.current.getBoundingClientRect();
      const viewportHeight = window.innerHeight;
      
      const scrollProgress = Math.max(
        0,
        Math.min(1, (viewportHeight - rect.top) / (viewportHeight + rect.height))
      );

      const frameIndex = Math.floor(scrollProgress * (totalFrames - 1));
      setCurrentFrame(frameIndex);

      // Draw current frame to canvas
      const ctx = canvasRef.current.getContext('2d');
      if (ctx && images.current[frameIndex]) {
        ctx.drawImage(images.current[frameIndex], 0, 0);
      }
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, [totalFrames]);

  return (
    <div ref={containerRef} className="hero-sequence-fallback">
      <canvas
        ref={canvasRef}
        width={1920}
        height={1080}
        className="hero-sequence-canvas"
      />
      {!isLoaded && (
        <div className="loading-overlay">
          <div className="loading-spinner" />
          <span>Loading exploded view...</span>
        </div>
      )}
    </div>
  );
};

// Main Hero Component
export const ExplodedViewHero: React.FC<ExplodedViewHeroProps> = ({
  modelUrl = '/assets/3d/engine-assembly.glb',
  videoFallbackUrl = '/assets/video/engine-exploded.mp4',
  imageFallbackUrl = '/assets/images/engine-hero.jpg',
  explosionIntensity = 1,
  scrollDistance = 1000,
  autoRotate = true,
  showLabels = true,
  className = '',
}) => {
  const [useWebGL, setUseWebGL] = useState(true);
  const [useVideoFallback, setUseVideoFallback] = useState(false);
  const [reducedMotion, setReducedMotion] = useState(false);

  useEffect(() => {
    // Check for WebGL support
    const canvas = document.createElement('canvas');
    const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
    setUseWebGL(!!gl);

    // Check for reduced motion preference
    const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    setReducedMotion(mediaQuery.matches);

    const handleChange = (e: MediaQueryListEvent) => {
      setReducedMotion(e.matches);
    };

    mediaQuery.addEventListener('change', handleChange);
    return () => mediaQuery.removeEventListener('change', handleChange);
  }, []);

  useEffect(() => {
    // Detect mobile/low-performance devices for video fallback
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
      navigator.userAgent
    );
    const isLowPerformance = navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 4;
    
    if (isMobile || isLowPerformance) {
      setUseVideoFallback(true);
    }
  }, []);

  const handlePartClick = (partId: string) => {
    console.log('Part clicked:', partId);
    // Could trigger modal with part details, add to wishlist, etc.
  };

  // Render appropriate fallback or WebGL
  if (!useWebGL || reducedMotion) {
    if (useVideoFallback) {
      return (
        <VideoFallback
          videoUrl={videoFallbackUrl}
          imageUrl={imageFallbackUrl}
        />
      );
    }
    
    // Static image for reduced motion
    return (
      <section className={`hero-static ${className}`}>
        <img
          src={imageFallbackUrl}
          alt="Engine assembly"
          className="hero-static-image"
        />
        <div className="hero-content">
          <h1>Premium Auto Parts</h1>
          <p>Engineering Excellence in Every Component</p>
        </div>
      </section>
    );
  }

  return (
    <section className={`hero-exploded-view ${className}`}>
      <div className="hero-canvas-container">
        <Canvas
          camera={{ position: [0, 0, 5], fov: 50 }}
          shadows
          dpr={[1, 2]}
          gl={{ antialias: true, alpha: true }}
        >
          {/* Lighting */}
          <ambientLight intensity={0.5} />
          <directionalLight
            position={[5, 5, 5]}
            intensity={1}
            castShadow
            shadow-mapSize-width={2048}
            shadow-mapSize-height={2048}
          />
          <spotLight
            position={[-5, 5, 5]}
            intensity={0.8}
            angle={0.3}
            penumbra={1}
          />
          
          {/* Environment for realistic reflections */}
          <Environment preset="studio" />
          
          {/* Scroll-aware 3D scene */}
          <group>
            <EngineAssembly
              explosionIntensity={explosionIntensity}
              showLabels={showLabels}
              onPartClick={handlePartClick}
            />
          </group>
          
          {/* Contact shadows for depth */}
          <ContactShadows
            position={[0, -2, 0]}
            opacity={0.4}
            scale={10}
            blur={2}
            far={4}
          />
          
          {/* Auto-rotation when not interacting */}
          {autoRotate && <AutoRotateSpeed />}
        </Canvas>
      </div>
      
      {/* Overlay content */}
      <div className="hero-overlay">
        <div className="hero-text">
          <h1 className="hero-title">
            <span className="title-line">Precision Engineering</span>
            <span className="title-line accent">Exploded</span>
          </h1>
          <p className="hero-subtitle">
            Discover the complexity behind automotive excellence
          </p>
          <div className="hero-cta">
            <button className="btn btn-primary">Shop Parts</button>
            <button className="btn btn-secondary">Learn More</button>
          </div>
        </div>
        
        {/* Scroll indicator */}
        <div className="scroll-indicator-3d">
          <span>Scroll to explore</span>
          <div className="scroll-arrows">
            <div className="arrow" />
            <div className="arrow" />
            <div className="arrow" />
          </div>
        </div>
      </div>
      
      {/* Loading state */}
      <div className="hero-loader">
        <div className="loader-gear" />
        <span>Loading 3D Engine...</span>
      </div>
    </section>
  );
};

// Component to handle auto-rotation
const AutoRotateSpeed: React.FC = () => {
  const { camera, mouse } = useThree();
  const targetRotation = useRef(0);
  
  useFrame((state, delta) => {
    // Slow auto-rotation when not interacting
    targetRotation.current += delta * 0.1;
    
    // Smooth interpolation between auto-rotate and mouse control
    const mouseX = mouse.x * 0.5;
    const targetAngle = mouseX !== 0 ? mouseX : targetRotation.current;
    
    camera.position.x += (Math.sin(targetAngle) * 3 - camera.position.x) * delta * 2;
    camera.position.z += (Math.cos(targetAngle) * 5 - camera.position.z) * delta * 2;
    
    camera.lookAt(0, 0, 0);
  });
  
  return null;
};

export default ExplodedViewHero;
