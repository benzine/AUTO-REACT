/**
 * Format utilities for prices, dates, numbers, etc.
 */

/**
 * Format price with currency symbol and proper decimal places
 */
export const formatPrice = (
  price: number,
  currency: string = 'USD',
  locale: string = 'en-US'
): string => {
  return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency,
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(price);
};

/**
 * Format discount percentage
 */
export const formatDiscount = (originalPrice: number, salePrice: number): string => {
  if (originalPrice <= 0) return '0%';
  
  const discount = ((originalPrice - salePrice) / originalPrice) * 100;
  return `${Math.round(discount)}% OFF`;
};

/**
 * Format bulk pricing tiers
 */
export const formatBulkPricing = (tiers: Array<{ quantity: number; discount: number }>): string[] => {
  return tiers.map((tier) => {
    const minQty = tier.quantity;
    const maxQty = tiers[tiers.indexOf(tier) + 1]?.quantity - 1 || '+';
    const discountPercent = Math.round(tier.discount);
    
    return `Buy ${minQty}${maxQty !== '+' ? `-${maxQty}` : '+'} and save ${discountPercent}%`;
  });
};

/**
 * Format part number with proper spacing/dashes
 */
export const formatPartNumber = (partNumber: string): string => {
  // Add spaces every 4 characters for better readability
  return partNumber.replace(/(.{4})/g, '$1 ').trim();
};

/**
 * Format vehicle year range
 */
export const formatYearRange = (startYear: number, endYear: number): string => {
  if (startYear === endYear) {
    return startYear.toString();
  }
  return `${startYear}-${endYear}`;
};

/**
 * Format stock status
 */
export const formatStockStatus = (
  quantity: number,
  lowStockThreshold: number = 5
): { status: 'in-stock' | 'low-stock' | 'out-of-stock'; label: string; color: string } => {
  if (quantity <= 0) {
    return {
      status: 'out-of-stock',
      label: 'Out of Stock',
      color: '#DC2626',
    };
  }
  
  if (quantity <= lowStockThreshold) {
    return {
      status: 'low-stock',
      label: `Only ${quantity} left`,
      color: '#EA580C',
    };
  }
  
  return {
    status: 'in-stock',
    label: 'In Stock',
    color: '#16A34A',
  };
};

/**
 * Format date for display
 */
export const formatDate = (date: Date | string, options?: Intl.DateTimeFormatOptions): string => {
  const dateObj = typeof date === 'string' ? new Date(date) : date;
  
  const defaultOptions: Intl.DateTimeFormatOptions = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  };
  
  return dateObj.toLocaleDateString('en-US', options || defaultOptions);
};

/**
 * Format order tracking timeline date
 */
export const formatOrderDate = (date: Date | string): string => {
  const dateObj = typeof date === 'string' ? new Date(date) : date;
  
  return dateObj.toLocaleDateString('en-US', {
    weekday: 'short',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

/**
 * Format phone number
 */
export const formatPhoneNumber = (phone: string): string => {
  const cleaned = phone.replace(/\D/g, '');
  const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
  
  if (match) {
    return `(${match[1]}) ${match[2]}-${match[3]}`;
  }
  
  return phone;
};

/**
 * Format dimensions (L x W x H)
 */
export const formatDimensions = (
  length: number,
  width: number,
  height: number,
  unit: string = 'inches'
): string => {
  return `${length} x ${width} x ${height} ${unit}`;
};

/**
 * Format weight with unit
 */
export const formatWeight = (weight: number, unit: string = 'lbs'): string => {
  return `${weight.toFixed(2)} ${unit}`;
};

/**
 * Truncate text with ellipsis
 */
export const truncateText = (text: string, maxLength: number): string => {
  if (text.length <= maxLength) return text;
  return text.substring(0, maxLength) + '...';
};

/**
 * Format rating stars
 */
export const formatRating = (rating: number): { full: number; half: boolean; empty: number } => {
  const full = Math.floor(rating);
  const hasHalf = rating % 1 >= 0.5;
  const empty = 5 - full - (hasHalf ? 1 : 0);
  
  return { full, half: hasHalf, empty };
};

/**
 * Format review count
 */
export const formatReviewCount = (count: number): string => {
  if (count === 0) return 'No reviews';
  if (count === 1) return '1 review';
  if (count < 1000) return `${count} reviews`;
  if (count < 1000000) return `${(count / 1000).toFixed(1)}K reviews`;
  return `${(count / 1000000).toFixed(1)}M reviews`;
};

/**
 * Format countdown timer
 */
export const formatCountdown = (seconds: number): { days: number; hours: number; minutes: number; seconds: number } => {
  const days = Math.floor(seconds / (3600 * 24));
  const hours = Math.floor((seconds % (3600 * 24)) / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const secs = Math.floor(seconds % 60);
  
  return { days, hours, minutes, seconds: secs };
};

/**
 * Format countdown for display
 */
export const formatCountdownDisplay = (seconds: number): string => {
  const { days, hours, minutes, seconds: secs } = formatCountdown(seconds);
  
  const parts: string[] = [];
  if (days > 0) parts.push(`${days}d`);
  if (hours > 0 || days > 0) parts.push(`${hours}h`);
  parts.push(`${minutes}m`);
  parts.push(`${secs}s`);
  
  return parts.join(' ');
};

/**
 * Parse and format SKU
 */
export const formatSKU = (sku: string): string => {
  return sku.toUpperCase().replace(/[^A-Z0-9-]/g, '');
};

/**
 * Format email with obfuscation option
 */
export const formatEmail = (email: string, obfuscate: boolean = false): string => {
  if (!obfuscate) return email;
  
  const [local, domain] = email.split('@');
  const obfuscatedLocal = local.charAt(0) + '***' + local.charAt(local.length - 1);
  
  return `${obfuscatedLocal}@${domain}`;
};

/**
 * Calculate shipping cost based on weight and distance
 */
export const calculateShipping = (
  weight: number,
  distance: number,
  baseRate: number = 5.99,
  perLbRate: number = 0.5,
  perMileRate: number = 0.01
): number => {
  const weightCost = weight * perLbRate;
  const distanceCost = distance * perMileRate;
  
  return Math.max(baseRate, baseRate + weightCost + distanceCost);
};

/**
 * Format shipping estimate
 */
export const formatShippingEstimate = (businessDays: number): string => {
  if (businessDays === 0) return 'Same Day Delivery';
  if (businessDays === 1) return 'Next Day Delivery';
  if (businessDays <= 3) return `${businessDays}-5 Business Days`;
  if (businessDays <= 7) return `${businessDays}-10 Business Days`;
  return `${businessDays}+ Business Days`;
};

export default {
  formatPrice,
  formatDiscount,
  formatBulkPricing,
  formatPartNumber,
  formatYearRange,
  formatStockStatus,
  formatDate,
  formatOrderDate,
  formatPhoneNumber,
  formatDimensions,
  formatWeight,
  truncateText,
  formatRating,
  formatReviewCount,
  formatCountdown,
  formatCountdownDisplay,
  formatSKU,
  formatEmail,
  calculateShipping,
  formatShippingEstimate,
};
