/**
 * Automotive Sound Effects Manager
 * Handles mechanical sound effects for UI interactions
 */

interface SoundEffect {
  id: string;
  src: string;
  volume: number;
}

class SoundManager {
  private sounds: Map<string, HTMLAudioElement> = new Map();
  private isEnabled: boolean = true;
  private masterVolume: number = 0.3;

  constructor() {
    this.init();
  }

  private init(): void {
    // Define sound effects
    const soundEffects: SoundEffect[] = [
      { id: 'gear-shift', src: '/assets/sounds/gear-shift.mp3', volume: 0.5 },
      { id: 'click', src: '/assets/sounds/mechanical-click.mp3', volume: 0.4 },
      { id: 'hover', src: '/assets/sounds/hover-swoosh.mp3', volume: 0.2 },
      { id: 'spark', src: '/assets/sounds/spark.mp3', volume: 0.6 },
      { id: 'engine-start', src: '/assets/sounds/engine-start.mp3', volume: 0.7 },
      { id: 'success', src: '/assets/sounds/success-chime.mp3', volume: 0.5 },
      { id: 'error', src: '/assets/sounds/error-buzz.mp3', volume: 0.4 },
    ];

    soundEffects.forEach((sound) => {
      const audio = new Audio(sound.src);
      audio.volume = sound.volume * this.masterVolume;
      audio.preload = 'auto';
      this.sounds.set(sound.id, audio);
    });
  }

  public play(soundId: string): void {
    if (!this.isEnabled) return;

    const sound = this.sounds.get(soundId);
    if (sound) {
      // Clone the audio to allow overlapping sounds
      const clone = sound.cloneNode() as HTMLAudioElement;
      clone.volume = sound.volume;
      clone.play().catch((e) => {
        console.warn(`Sound playback failed: ${soundId}`, e);
      });
    }
  }

  public stop(soundId: string): void {
    const sound = this.sounds.get(soundId);
    if (sound) {
      sound.pause();
      sound.currentTime = 0;
    }
  }

  public stopAll(): void {
    this.sounds.forEach((sound) => {
      sound.pause();
      sound.currentTime = 0;
    });
  }

  public enable(): void {
    this.isEnabled = true;
  }

  public disable(): void {
    this.isEnabled = false;
    this.stopAll();
  }

  public toggle(): void {
    this.isEnabled = !this.isEnabled;
  }

  public setMasterVolume(volume: number): void {
    this.masterVolume = Math.max(0, Math.min(1, volume));
    this.sounds.forEach((sound, id) => {
      const originalSound = this.sounds.get(id);
      if (originalSound) {
        sound.volume = originalSound.volume * this.masterVolume;
      }
    });
  }

  public getIsEnabled(): boolean {
    return this.isEnabled;
  }
}

// Singleton instance
export const soundManager = new SoundManager();

// Hook for React components
export const useSound = () => {
  return {
    play: (soundId: string) => soundManager.play(soundId),
    stop: (soundId: string) => soundManager.stop(soundId),
    stopAll: () => soundManager.stopAll(),
    enable: () => soundManager.enable(),
    disable: () => soundManager.disable(),
    toggle: () => soundManager.toggle(),
    setVolume: (volume: number) => soundManager.setMasterVolume(volume),
    isEnabled: soundManager.getIsEnabled(),
  };
};

export default soundManager;
