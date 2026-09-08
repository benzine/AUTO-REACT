export interface Product {
  id: string;
  name: string;
  slug: string;
  description: string;
  shortDescription: string;
  price: number;
  salePrice?: number;
  partNumber: string;
  oemNumber?: string;
  brand: string;
  category: string;
  subcategory?: string;
  images: string[];
  inStock: boolean;
  stockQuantity: number;
  rating: number;
  reviewCount: number;
  specifications: Record<string, string>;
  compatibility: VehicleCompatibility[];
  features: string[];
  isOEM: boolean;
  weight?: string;
  dimensions?: string;
  warranty?: string;
}

export interface VehicleCompatibility {
  year: number;
  make: string;
  model: string;
  engine?: string;
  trim?: string;
}

export interface Category {
  id: string;
  name: string;
  slug: string;
  description?: string;
  image?: string;
  parentId?: string;
  subcategories?: Category[];
}

export interface Brand {
  id: string;
  name: string;
  slug: string;
  logo: string;
  description?: string;
  featuredProducts?: string[];
}

export const mockProducts: Product[] = [
  {
    id: '1',
    name: 'High-Performance Brake Pads - Ceramic',
    slug: 'ceramic-brake-pads',
    description: 'Premium ceramic brake pads designed for maximum stopping power and minimal dust. Engineered for performance vehicles and daily drivers alike.',
    shortDescription: 'Premium ceramic brake pads for superior performance',
    price: 89.99,
    partNumber: 'BP-CER-001',
    oemNumber: 'OEM-12345',
    brand: 'BrakeMaster',
    category: 'brakes',
    subcategory: 'brake-pads',
    images: ['/images/products/brake-pads-1.jpg'],
    inStock: true,
    stockQuantity: 150,
    rating: 4.8,
    reviewCount: 127,
    specifications: {
      material: 'Ceramic',
      position: 'Front Axle',
      quantity: '4 Pads',
    },
    compatibility: [
      { year: 2020, make: 'BMW', model: 'M3', engine: '3.0L' },
      { year: 2021, make: 'BMW', model: 'M3', engine: '3.0L' },
      { year: 2022, make: 'BMW', model: 'M4', engine: '3.0L' },
    ],
    features: [
      'Low dust formula',
      'Quiet operation',
      'Extended pad life',
      'Excellent heat dissipation',
    ],
    isOEM: false,
    weight: '2.5 lbs',
    warranty: '2 years',
  },
  {
    id: '2',
    name: 'Sport Air Filter - High Flow',
    slug: 'sport-air-filter',
    description: 'High-flow air filter that increases horsepower and acceleration while providing superior engine protection.',
    shortDescription: 'High-flow air filter for increased performance',
    price: 59.99,
    salePrice: 49.99,
    partNumber: 'AF-SPT-002',
    brand: 'FilterPro',
    category: 'filters',
    subcategory: 'air-filters',
    images: ['/images/products/air-filter-1.jpg'],
    inStock: true,
    stockQuantity: 85,
    rating: 4.6,
    reviewCount: 89,
    specifications: {
      type: 'Oiled Cotton Gauze',
      washable: 'Yes',
      flowIncrease: '15%',
    },
    compatibility: [
      { year: 2019, make: 'Toyota', model: 'Supra', engine: '3.0L' },
      { year: 2020, make: 'Toyota', model: 'Supra', engine: '3.0L' },
    ],
    features: [
      'Reusable and washable',
      'Increases horsepower',
      'Better throttle response',
      'Eco-friendly',
    ],
    isOEM: false,
    weight: '1.2 lbs',
    warranty: '10 years or 100,000 miles',
  },
  {
    id: '3',
    name: 'Performance Spark Plugs - Iridium',
    slug: 'iridium-spark-plugs',
    description: 'Iridium spark plugs deliver maximum ignition performance with superior durability and fuel efficiency.',
    shortDescription: 'Iridium spark plugs for optimal ignition',
    price: 12.99,
    partNumber: 'SP-IRI-003',
    oemNumber: 'OEM-67890',
    brand: 'IgniteMax',
    category: 'electrical',
    subcategory: 'spark-plugs',
    images: ['/images/products/spark-plug-1.jpg'],
    inStock: true,
    stockQuantity: 500,
    rating: 4.9,
    reviewCount: 234,
    specifications: {
      electrodeType: 'Iridium',
      gap: '0.044"',
      threadSize: '14mm',
    },
    compatibility: [
      { year: 2018, make: 'Ford', model: 'Mustang GT', engine: '5.0L' },
      { year: 2019, make: 'Ford', model: 'Mustang GT', engine: '5.0L' },
      { year: 2020, make: 'Ford', model: 'Mustang GT', engine: '5.0L' },
    ],
    features: [
      'Longer electrode life',
      'Better fuel economy',
      'Smoother idle',
      'Improved cold starting',
    ],
    isOEM: false,
    weight: '0.3 lbs',
    warranty: '5 years',
  },
];

export const mockCategories: Category[] = [
  {
    id: 'engine',
    name: 'Engine Parts',
    slug: 'engine-parts',
    description: 'Complete range of engine components',
    subcategories: [
      { id: 'pistons', name: 'Pistons', slug: 'pistons', parentId: 'engine' },
      { id: 'gaskets', name: 'Gaskets', slug: 'gaskets', parentId: 'engine' },
      { id: 'timing-belts', name: 'Timing Belts', slug: 'timing-belts', parentId: 'engine' },
      { id: 'camshafts', name: 'Camshafts', slug: 'camshafts', parentId: 'engine' },
    ],
  },
  {
    id: 'brakes',
    name: 'Brakes',
    slug: 'brakes',
    description: 'Brake systems and components',
    subcategories: [
      { id: 'brake-pads', name: 'Brake Pads', slug: 'brake-pads', parentId: 'brakes' },
      { id: 'rotors', name: 'Rotors', slug: 'rotors', parentId: 'brakes' },
      { id: 'calipers', name: 'Calipers', slug: 'calipers', parentId: 'brakes' },
    ],
  },
  {
    id: 'electrical',
    name: 'Electrical',
    slug: 'electrical',
    description: 'Electrical system components',
    subcategories: [
      { id: 'spark-plugs', name: 'Spark Plugs', slug: 'spark-plugs', parentId: 'electrical' },
      { id: 'batteries', name: 'Batteries', slug: 'batteries', parentId: 'electrical' },
      { id: 'alternators', name: 'Alternators', slug: 'alternators', parentId: 'electrical' },
    ],
  },
  {
    id: 'suspension',
    name: 'Suspension',
    slug: 'suspension',
    description: 'Suspension and steering parts',
  },
  {
    id: 'filters',
    name: 'Filters',
    slug: 'filters',
    description: 'Air, oil, and fuel filters',
  },
  {
    id: 'exhaust',
    name: 'Exhaust',
    slug: 'exhaust',
    description: 'Exhaust systems and components',
  },
  {
    id: 'cooling',
    name: 'Cooling',
    slug: 'cooling',
    description: 'Cooling system parts',
  },
  {
    id: 'transmission',
    name: 'Transmission',
    slug: 'transmission',
    description: 'Transmission components',
  },
  {
    id: 'body',
    name: 'Body Parts',
    slug: 'body-parts',
    description: 'Exterior body components',
  },
];

export const mockBrands: Brand[] = [
  {
    id: 'brembo',
    name: 'Brembo',
    slug: 'brembo',
    logo: '/images/brands/brembo.svg',
    description: 'World-renowned manufacturer of high-performance braking systems.',
  },
  {
    id: 'bosch',
    name: 'Bosch',
    slug: 'bosch',
    logo: '/images/brands/bosch.svg',
    description: 'Leading global supplier of technology and services.',
  },
  {
    id: 'k&n',
    name: 'K&N',
    slug: 'kn',
    logo: '/images/brands/kn.svg',
    description: 'Manufacturer of high-flow air filters and intake systems.',
  },
  {
    id: 'ngk',
    name: 'NGK',
    slug: 'ngk',
    logo: '/images/brands/ngk.svg',
    description: 'Global leader in spark plug technology.',
  },
];
