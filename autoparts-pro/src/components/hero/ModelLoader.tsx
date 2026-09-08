/**
 * ModelLoader.tsx
 * 
 * Advanced 3D model loading with progress tracking, error handling,
 * and fallback mechanisms for the exploded view hero.
 */

import React, { useState, useEffect, useCallback } from 'react';
import { useGLTF } from '@react-three/drei';
import * as THREE from 'three';

interface ModelLoaderProps {
  url: string;
  onLoad?: (model: THREE.Object3D) => void;
  onError?: (error: Error) => void;
  onProgress?: (progress: number) => void;
  children: (result: ModelLoadResult) => React.ReactNode;
}

interface ModelLoadResult {
  model: THREE.Object3D | null;
  isLoading: boolean;
  error: Error | null;
  progress: number;
}

interface LoadedModelCache {
  model: THREE.Object3D;
  timestamp: number;
}

// Cache for loaded models to prevent re-loading
const modelCache = new Map<string, LoadedModelCache>();
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

// Custom GLTF loader with progress tracking
export const ModelLoader: React.FC<ModelLoaderProps> = ({
  url,
  onLoad,
  onError,
  onProgress,
  children,
}) => {
  const [result, setResult] = useState<ModelLoadResult>({
    model: null,
    isLoading: true,
    error: null,
    progress: 0,
  });

  // Check cache first
  useEffect(() => {
    const cached = modelCache.get(url);
    if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
      setResult({
        model: cached.model,
        isLoading: false,
        error: null,
        progress: 100,
      });
      
      if (onLoad) {
        onLoad(cached.model);
      }
      return;
    }
    
    // Clear expired cache
    modelCache.forEach((value, key) => {
      if (Date.now() - value.timestamp >= CACHE_DURATION) {
        modelCache.delete(key);
      }
    });
  }, [url, onLoad]);

  // Load model using useGLTF hook
  try {
    const { scene } = useGLTF(url);
    
    useEffect(() => {
      if (scene) {
        const loadedModel = scene.clone();
        
        // Update cache
        modelCache.set(url, {
          model: loadedModel,
          timestamp: Date.now(),
        });
        
        setResult({
          model: loadedModel,
          isLoading: false,
          error: null,
          progress: 100,
        });
        
        if (onLoad) {
          onLoad(loadedModel);
        }
        
        if (onProgress) {
          onProgress(100);
        }
      }
    }, [scene, onLoad, onProgress]);
  } catch (error) {
    useEffect(() => {
      const err = error instanceof Error ? error : new Error('Failed to load model');
      
      setResult({
        model: null,
        isLoading: false,
        error: err,
        progress: 0,
      });
      
      if (onError) {
        onError(err);
      }
    }, [error, onError]);
  }

  return <>{children(result)}</>;
};

// Preload multiple models with parallel loading
export const useModelPreloader = (urls: string[]) => {
  const [loadingState, setLoadingState] = useState<{
    [key: string]: ModelLoadResult;
  }>({});
  
  const [overallProgress, setOverallProgress] = useState(0);
  const [isAllLoaded, setIsAllLoaded] = useState(false);

  useEffect(() => {
    let mounted = true;
    const results: { [key: string]: ModelLoadResult } = {};
    let completedCount = 0;

    const updateProgress = () => {
      if (!mounted) return;
      
      completedCount++;
      const progress = Math.round((completedCount / urls.length) * 100);
      setOverallProgress(progress);
      
      if (completedCount === urls.length) {
        setIsAllLoaded(true);
      }
    };

    urls.forEach((url) => {
      results[url] = {
        model: null,
        isLoading: true,
        error: null,
        progress: 0,
      };

      // Check cache
      const cached = modelCache.get(url);
      if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
        results[url] = {
          model: cached.model,
          isLoading: false,
          error: null,
          progress: 100,
        };
        updateProgress();
        return;
      }

      // Load model
      const loader = new THREE.FileLoader();
      loader.load(
        url,
        (data) => {
          // Create blob URL for GLTF loader
          const blob = new Blob([data], { type: 'application/octet-stream' });
          const blobUrl = URL.createObjectURL(blob);
          
          const gltfLoader = new THREE.GLTFLoader();
          gltfLoader.load(
            blobUrl,
            (gltf) => {
              if (!mounted) return;
              
              const clonedScene = gltf.scene.clone();
              modelCache.set(url, {
                model: clonedScene,
                timestamp: Date.now(),
              });
              
              results[url] = {
                model: clonedScene,
                isLoading: false,
                error: null,
                progress: 100,
              };
              
              setLoadingState({ ...results });
              updateProgress();
              
              // Clean up blob URL
              setTimeout(() => URL.revokeObjectURL(blobUrl), 1000);
            },
            (xhr) => {
              if (!mounted) return;
              
              const progress = Math.round((xhr.loaded / xhr.total) * 100);
              results[url].progress = progress;
              setLoadingState({ ...results });
            },
            (error) => {
              if (!mounted) return;
              
              results[url] = {
                model: null,
                isLoading: false,
                error: error instanceof Error ? error : new Error('Load failed'),
                progress: 0,
              };
              
              setLoadingState({ ...results });
              updateProgress();
            }
          );
        },
        undefined,
        (error) => {
          if (!mounted) return;
          
          results[url] = {
            model: null,
            isLoading: false,
            error: error instanceof Error ? error : new Error('Load failed'),
            progress: 0,
          };
          
          setLoadingState({ ...results });
          updateProgress();
        }
      );
    });

    return () => {
      mounted = false;
    };
  }, [urls]);

  return { loadingState, overallProgress, isAllLoaded };
};

// Optimized model component with LOD (Level of Detail)
interface LODModelProps {
  url: string;
  position?: [number, number, number];
  rotation?: [number, number, number];
  scale?: [number, number, number];
  distanceThresholds?: [number, number, number]; // Near, medium, far
  children?: React.ReactNode;
}

export const LODModel: React.FC<LODModelProps> = ({
  url,
  position = [0, 0, 0],
  rotation = [0, 0, 0],
  scale = [1, 1, 1],
  distanceThresholds = [2, 5, 10],
  children,
}) => {
  const [lodLevel, setLodLevel] = useState(0);
  const { camera } = useThree();
  const modelRef = useRef<THREE.Group>(null);

  useFrame(() => {
    if (!modelRef.current) return;
    
    const distance = camera.position.distanceTo(modelRef.current.position);
    
    if (distance < distanceThresholds[0]) {
      setLodLevel(0); // High detail
    } else if (distance < distanceThresholds[1]) {
      setLodLevel(1); // Medium detail
    } else {
      setLodLevel(2); // Low detail
    }
  });

  return (
    <group ref={modelRef} position={position} rotation={rotation} scale={scale}>
      <ModelLoader url={url}>
        {({ model, isLoading, error }) => {
          if (isLoading) {
            return <LoadingPlaceholder />;
          }
          
          if (error || !model) {
            return <ErrorPlaceholder error={error?.message || 'Failed to load'} />;
          }
          
          return (
            <>
              <primitive object={model} />
              {children}
            </>
          );
        }}
      </ModelLoader>
    </group>
  );
};

// Loading placeholder component
const LoadingPlaceholder: React.FC = () => {
  return (
    <group>
      <mesh>
        <boxGeometry args={[0.5, 0.5, 0.5]} />
        <meshStandardMaterial color="#6B7280" wireframe />
      </mesh>
    </group>
  );
};

// Error placeholder component
const ErrorPlaceholder: React.FC<{ error: string }> = ({ error }) => {
  return (
    <group>
      <mesh>
        <boxGeometry args={[0.5, 0.5, 0.5]} />
        <meshStandardMaterial color="#DC2626" />
      </mesh>
    </group>
  );
};

// Utility to optimize GLTF models
export const optimizeModel = (model: THREE.Object3D): THREE.Object3D => {
  const optimized = model.clone();
  
  optimized.traverse((child) => {
    if ((child as THREE.Mesh).isMesh) {
      const mesh = child as THREE.Mesh;
      
      // Reduce material complexity
      if (mesh.material) {
        const material = mesh.material as THREE.Material;
        material.needsUpdate = true;
      }
      
      // Simplify geometry if possible
      const geometry = mesh.geometry as THREE.BufferGeometry;
      if (geometry.attributes.position) {
        // Could add mesh simplification here
      }
    }
  });
  
  return optimized;
};

export default ModelLoader;
