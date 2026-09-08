import { create } from 'zustand';

export interface Vehicle {
  year: number;
  make: string;
  model: string;
  engine?: string;
}

interface VehicleState {
  garage: Vehicle[];
  selectedVehicle: Vehicle | null;
  addVehicle: (vehicle: Vehicle) => void;
  removeVehicle: (index: number) => void;
  setSelectedVehicle: (vehicle: Vehicle | null) => void;
  clearGarage: () => void;
}

export const useVehicleStore = create<VehicleState>((set) => ({
  garage: [],
  selectedVehicle: null,
  
  addVehicle: (vehicle) => set((state) => ({
    garage: [...state.garage, vehicle],
    selectedVehicle: vehicle,
  })),
  
  removeVehicle: (index) => set((state) => ({
    garage: state.garage.filter((_, i) => i !== index),
  })),
  
  setSelectedVehicle: (vehicle) => set({ selectedVehicle: vehicle }),
  
  clearGarage: () => set({ garage: [], selectedVehicle: null }),
}));
