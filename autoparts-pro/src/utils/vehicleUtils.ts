/**
 * Vehicle Compatibility Utilities
 * Helper functions for checking part compatibility with vehicles
 */

export interface Vehicle {
  id: string;
  year: number;
  make: string;
  model: string;
  engine: string;
  trim?: string;
}

export interface PartCompatibility {
  partNumber: string;
  compatibleVehicles: Vehicle[];
  oemNumbers: string[];
  crossReferences: string[];
}

/**
 * Check if a part is compatible with a specific vehicle
 */
export const checkCompatibility = (
  part: PartCompatibility,
  vehicle: Vehicle
): boolean => {
  return part.compatibleVehicles.some(
    (v) =>
      v.year === vehicle.year &&
      v.make.toLowerCase() === vehicle.make.toLowerCase() &&
      v.model.toLowerCase() === vehicle.model.toLowerCase() &&
      (!vehicle.engine || v.engine.toLowerCase() === vehicle.engine.toLowerCase())
  );
};

/**
 * Get compatibility status message
 */
export const getCompatibilityStatus = (
  part: PartCompatibility,
  vehicle: Vehicle | null
): { status: 'compatible' | 'incompatible' | 'unknown'; message: string } => {
  if (!vehicle) {
    return {
      status: 'unknown',
      message: 'Select your vehicle to check compatibility',
    };
  }

  const isCompatible = checkCompatibility(part, vehicle);

  return {
    status: isCompatible ? 'compatible' : 'incompatible',
    message: isCompatible
      ? '✓ This part fits your vehicle'
      : '✗ This part does not fit your vehicle',
  };
};

/**
 * Filter parts by vehicle compatibility
 */
export const filterPartsByVehicle = (
  parts: PartCompatibility[],
  vehicle: Vehicle
): PartCompatibility[] => {
  return parts.filter((part) => checkCompatibility(part, vehicle));
};

/**
 * Format vehicle name for display
 */
export const formatVehicleName = (vehicle: Vehicle): string => {
  return `${vehicle.year} ${vehicle.make} ${vehicle.model}${vehicle.engine ? ` (${vehicle.engine})` : ''}`;
};

/**
 * Parse VIN number (simplified implementation)
 * In production, this would use a proper VIN decoding API
 */
export const parseVIN = (vin: string): Partial<Vehicle> | null => {
  if (!vin || vin.length !== 17) {
    return null;
  }

  // Simplified VIN parsing (real implementation would be more complex)
  const yearMap: { [key: string]: number } = {
    A: 2010, B: 2011, C: 2012, D: 2013, E: 2014,
    F: 2015, G: 2016, H: 2017, J: 2018, K: 2019,
    L: 2020, M: 2021, N: 2022, P: 2023, R: 2024, S: 2025,
  };

  const yearChar = vin[9];
  const year = yearMap[yearChar] || new Date().getFullYear();

  return {
    year,
  };
};

/**
 * Group compatible vehicles by make and model
 */
export const groupVehiclesByMakeModel = (
  vehicles: Vehicle[]
): { [make: string]: { [model: string]: Vehicle[] } } => {
  return vehicles.reduce(
    (acc, vehicle) => {
      const { make, model } = vehicle;
      const makeKey = make.toLowerCase();
      const modelKey = model.toLowerCase();

      if (!acc[makeKey]) {
        acc[makeKey] = {};
      }

      if (!acc[makeKey][modelKey]) {
        acc[makeKey][modelKey] = [];
      }

      acc[makeKey][modelKey].push(vehicle);

      return acc;
    },
    {} as { [make: string]: { [model: string]: Vehicle[] } }
  );
};

/**
 * Get years range from vehicles list
 */
export const getYearsRange = (vehicles: Vehicle[]): [number, number] => {
  if (vehicles.length === 0) {
    return [new Date().getFullYear() - 10, new Date().getFullYear()];
  }

  const years = vehicles.map((v) => v.year);
  return [Math.min(...years), Math.max(...years)];
};

/**
 * Generate compatibility badge HTML
 */
export const generateCompatibilityBadge = (
  isCompatible: boolean,
  vehicleName?: string
): string => {
  const statusClass = isCompatible ? 'compatible' : 'incompatible';
  const statusIcon = isCompatible ? '✓' : '✗';
  const statusText = isCompatible
    ? 'Fits Your Vehicle'
    : 'Does Not Fit';

  return `
    <span class="compatibility-badge ${statusClass}">
      <span class="badge-icon">${statusIcon}</span>
      <span class="badge-text">${statusText}${vehicleName ? ` - ${vehicleName}` : ''}</span>
    </span>
  `;
};

/**
 * Compare two vehicles for equality
 */
export const vehiclesAreEqual = (v1: Vehicle, v2: Vehicle): boolean => {
  return (
    v1.id === v2.id ||
    (v1.year === v2.year &&
      v1.make.toLowerCase() === v2.make.toLowerCase() &&
      v1.model.toLowerCase() === v2.model.toLowerCase() &&
      v1.engine.toLowerCase() === v2.engine.toLowerCase())
  );
};

/**
 * Validate vehicle data
 */
export const validateVehicle = (vehicle: Partial<Vehicle>): boolean => {
  return !!(
    vehicle.year &&
    vehicle.make &&
    vehicle.model &&
    vehicle.year >= 1900 &&
    vehicle.year <= new Date().getFullYear() + 2
  );
};

/**
 * Sort vehicles by year (newest first)
 */
export const sortVehiclesByYear = (vehicles: Vehicle[], ascending: boolean = false): Vehicle[] => {
  return [...vehicles].sort((a, b) => {
    return ascending ? a.year - b.year : b.year - a.year;
  });
};

export default {
  checkCompatibility,
  getCompatibilityStatus,
  filterPartsByVehicle,
  formatVehicleName,
  parseVIN,
  groupVehiclesByMakeModel,
  getYearsRange,
  generateCompatibilityBadge,
  vehiclesAreEqual,
  validateVehicle,
  sortVehiclesByYear,
};
