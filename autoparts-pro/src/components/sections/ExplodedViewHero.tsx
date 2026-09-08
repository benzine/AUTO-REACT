import { useEffect, useRef } from 'react';
import * as THREE from 'three';
import { Canvas, useThree, useFrame } from '@react-three/fiber';
import { OrbitControls, PerspectiveCamera, Environment, ContactShadows } from '@react-three/drei';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

interface ExplodedPart {
  name: string;
  position: [number, number, number];
  explodedPosition: [number, number, number];
  label?: string;
}

const mockExplodedParts: ExplodedPart[] = [
  { name: 'piston1', position: [0.5, 0, 0.5], explodedPosition: [1.5, 0.5, 1.5], label: 'Piston' },
  { name: 'piston2', position: [-0.5, 0, 0.5], explodedPosition: [-1.5, 0.5, 1.5], label: 'Piston' },
  { name: 'crankshaft', position: [0, -0.5, 0], explodedPosition: [0, -1.5, 0], label: 'Crankshaft' },
  { name: 'camshaft', position: [0, 1, 0], explodedPosition: [0, 2, 0], label: 'Camshaft' },
  { name: 'valve1', position: [0.3, 0.8, 0.3], explodedPosition: [0.8, 1.8, 0.8], label: 'Valve' },
  { name: 'valve2', position: [-0.3, 0.8, 0.3], explodedPosition: [-0.8, 1.8, 0.8], label: 'Valve' },
  { name: 'block', position: [0, 0, 0], explodedPosition: [0, 0, 0], label: 'Engine Block' },
];

function EnginePart({ part, progress }: { part: ExplodedPart; progress: number }) {
  const meshRef = useRef<THREE.Mesh>(null);
  
  // Interpolate between original and exploded position based on scroll progress
  const currentPosition = part.position.map((pos, i) => 
    pos + (part.explodedPosition[i] - pos) * progress
  ) as [number, number, number];
  
  useFrame((state) => {
    if (meshRef.current) {
      meshRef.current.position.lerp(
        new THREE.Vector3(...currentPosition),
        0.1
      );
      meshRef.current.rotation.y += 0.005;
    }
  });
  
  return (
    <mesh ref={meshRef} position={part.position}>
      <boxGeometry args={[0.4, 0.4, 0.4]} />
      <meshStandardMaterial
        color="#DC2626"
        metalness={0.8}
        roughness={0.2}
        emissive="#DC2626"
        emissiveIntensity={0.2}
      />
    </mesh>
  );
}

function EngineBlock() {
  const meshRef = useRef<THREE.Mesh>(null);
  
  useFrame((state) => {
    if (meshRef.current) {
      meshRef.current.rotation.y = Math.sin(state.clock.elapsedTime * 0.5) * 0.1;
    }
  });
  
  return (
    <mesh ref={meshRef} position={[0, 0, 0]}>
      <boxGeometry args={[1.5, 1, 1.5]} />
      <meshStandardMaterial
        color="#2D2D2D"
        metalness={0.9}
        roughness={0.3}
      />
    </mesh>
  );
}

function ExplodedViewScene({ scrollProgress }: { scrollProgress: number }) {
  const groupRef = useRef<THREE.Group>(null);
  
  useFrame((state) => {
    if (groupRef.current) {
      groupRef.current.rotation.y = Math.sin(state.clock.elapsedTime * 0.3) * 0.2;
    }
  });
  
  return (
    <group ref={groupRef}>
      <EngineBlock />
      {mockExplodedParts.map((part) => (
        <EnginePart key={part.name} part={part} progress={scrollProgress} />
      ))}
      
      {/* Lighting */}
      <ambientLight intensity={0.5} />
      <spotLight position={[10, 10, 10]} angle={0.15} penumbra={1} intensity={1} />
      <pointLight position={[-10, -10, -10]} intensity={0.5} />
      
      {/* Environment */}
      <Environment preset="city" />
      <ContactShadows position={[0, -2, 0]} opacity={0.4} scale={10} blur={2} />
    </group>
  );
}

export function ExplodedViewHero() {
  const containerRef = useRef<HTMLDivElement>(null);
  const [scrollProgress, setScrollProgress] = useState(0);
  
  useEffect(() => {
    const ctx = ScrollTrigger.create({
      trigger: containerRef.current,
      start: 'top top',
      end: '+=2000',
      scrub: true,
      onUpdate: (self) => {
        setScrollProgress(self.progress);
      },
    });
    
    return () => {
      ctx.kill();
    };
  }, []);
  
  return (
    <section
      ref={containerRef}
      className="relative h-[300vh] bg-dark"
    >
      <div className="sticky top-0 h-screen overflow-hidden">
        {/* Background Grid */}
        <div className="absolute inset-0 mechanical-grid opacity-20" />
        
        {/* 3D Canvas */}
        <Canvas className="w-full h-full">
          <PerspectiveCamera makeDefault position={[5, 3, 5]} fov={50} />
          <OrbitControls enableZoom={false} enablePan={false} maxPolarAngle={Math.PI / 2} />
          <ExplodedViewScene scrollProgress={scrollProgress} />
        </Canvas>
        
        {/* Overlay Content */}
        <div className="absolute inset-0 pointer-events-none">
          <div className="container mx-auto px-4 h-full flex flex-col justify-center">
            <div className="max-w-2xl">
              <h1 className="text-5xl md:text-7xl font-heading font-bold text-white mb-6">
                Precision <span className="text-gradient">Engineering</span>
              </h1>
              <p className="text-xl text-mid-steel mb-8">
                Explore the intricate details of high-performance automotive components. 
                Scroll to explode the view and discover every part.
              </p>
              <div className="flex gap-4">
                <button className="btn-primary">
                  Shop Now
                </button>
                <button className="btn-secondary">
                  Learn More
                </button>
              </div>
            </div>
          </div>
        </div>
        
        {/* Scroll Progress Indicator */}
        <div className="absolute right-8 top-1/2 -translate-y-1/2 hidden md:block">
          <div className="w-1 h-32 bg-bg-tertiary rounded-full overflow-hidden">
            <div
              className="w-full bg-primary transition-all duration-100"
              style={{ height: `${scrollProgress * 100}%` }}
            />
          </div>
          <p className="text-xs text-mid-steel mt-2 text-center rotate-90 origin-top-left translate-x-4">
            SCROLL TO EXPLORE
          </p>
        </div>
      </div>
    </section>
  );
}
