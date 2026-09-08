<?php
/**
 * Template Name: Vehicle Compatibility Page
 * Advanced vehicle selector and parts finder
 */

get_header();
?>

<main class="vehicle-compatibility-page">
  <!-- Hero Section -->
  <section class="compatibility-hero">
    <div class="container">
      <h1 class="page-title">Find Parts That Fit Your Vehicle</h1>
      <p class="page-subtitle">Select your vehicle to see compatible parts</p>
      
      <!-- Vehicle Selector Widget -->
      <div class="vehicle-selector-widget">
        <form id="vehicle-selector-form" class="vehicle-form">
          <div class="form-row">
            <div class="form-group">
              <label for="vehicle-year">Year</label>
              <select id="vehicle-year" name="year" required>
                <option value="">Select Year</option>
                <?php
                $current_year = date('Y');
                for ($i = $current_year; $i >= $current_year - 30; $i--) {
                  echo '<option value="' . $i . '">' . $i . '</option>';
                }
                ?>
              </select>
            </div>
            
            <div class="form-group">
              <label for="vehicle-make">Make</label>
              <select id="vehicle-make" name="make" required disabled>
                <option value="">Select Year First</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="vehicle-model">Model</label>
              <select id="vehicle-model" name="model" required disabled>
                <option value="">Select Make First</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="vehicle-engine">Engine</label>
              <select id="vehicle-engine" name="engine" required disabled>
                <option value="">Select Model First</option>
              </select>
            </div>
          </div>
          
          <button type="submit" class="search-parts-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
              <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2"/>
            </svg>
            Find Compatible Parts
          </button>
        </form>
        
        <!-- Saved Vehicles (Garage) -->
        <div class="garage-section" id="garage-section">
          <h3>Your Garage</h3>
          <div class="garage-vehicles" id="garage-vehicles">
            <!-- Populated by JavaScript -->
          </div>
          <button class="add-vehicle-btn" id="add-to-garage-btn" style="display: none;">
            + Add to Garage
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Selected Vehicle Display -->
  <section class="selected-vehicle-bar" id="selected-vehicle-bar" style="display: none;">
    <div class="container">
      <div class="vehicle-info">
        <span class="vehicle-badge">✓ Selected Vehicle:</span>
        <span class="vehicle-name" id="selected-vehicle-name"></span>
      </div>
      <button class="clear-selection-btn" id="clear-vehicle-btn">
        Clear Selection
      </button>
    </div>
  </section>

  <!-- Compatible Parts Results -->
  <section class="compatible-parts-section" id="compatible-parts-section">
    <div class="container">
      <div class="results-header">
        <h2>Compatible Parts</h2>
        <div class="results-meta">
          <span class="parts-count" id="parts-count">0 parts found</span>
          <div class="view-toggle">
            <button class="view-btn active" data-view="grid">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <rect x="3" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                <rect x="14" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                <rect x="3" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                <rect x="14" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/>
              </svg>
            </button>
            <button class="view-btn" data-view="list">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <line x1="8" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2"/>
                <line x1="8" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2"/>
                <line x1="8" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2"/>
                <circle cx="4" cy="6" r="2" stroke="currentColor" stroke-width="2"/>
                <circle cx="4" cy="12" r="2" stroke="currentColor" stroke-width="2"/>
                <circle cx="4" cy="18" r="2" stroke="currentColor" stroke-width="2"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
      
      <div class="parts-grid" id="parts-grid">
        <!-- Populated by AJAX -->
        <div class="loading-state">
          <div class="spinner"></div>
          <p>Select your vehicle to see compatible parts</p>
        </div>
      </div>
      
      <div class="load-more-container" id="load-more-container" style="display: none;">
        <button class="load-more-btn" id="load-more-btn">Load More Parts</button>
      </div>
    </div>
  </section>

  <!-- Browse by Category Alternative -->
  <section class="browse-categories-section">
    <div class="container">
      <h2>Browse by Category</h2>
      <div class="category-tiles">
        <?php
        $categories = array(
          'Engine Parts' => 'engine',
          'Brakes' => 'brakes',
          'Suspension' => 'suspension',
          'Electrical' => 'electrical',
          'Filters' => 'filters',
          'Transmission' => 'transmission',
          'Cooling' => 'cooling',
          'Exhaust' => 'exhaust',
        );
        
        foreach ($categories as $name => $slug) :
          ?>
          <a href="<?php echo esc_url(get_term_link($slug, 'product_cat')); ?>" class="category-tile">
            <div class="tile-icon">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                <path d="M12 2v4M12 18v4M2 12h4M18 12h4" stroke="currentColor" stroke-width="2"/>
              </svg>
            </div>
            <span class="tile-label"><?php echo $name; ?></span>
          </a>
          <?php
        endforeach;
        ?>
      </div>
    </div>
  </section>
</main>

<script>
// Vehicle compatibility page JavaScript
document.addEventListener('DOMContentLoaded', function() {
  const yearSelect = document.getElementById('vehicle-year');
  const makeSelect = document.getElementById('vehicle-make');
  const modelSelect = document.getElementById('vehicle-model');
  const engineSelect = document.getElementById('vehicle-engine');
  const form = document.getElementById('vehicle-selector-form');
  const garageSection = document.getElementById('garage-section');
  const garageVehicles = document.getElementById('garage-vehicles');
  const addToGarageBtn = document.getElementById('add-to-garage-btn');
  const selectedVehicleBar = document.getElementById('selected-vehicle-bar');
  const selectedVehicleName = document.getElementById('selected-vehicle-name');
  const clearVehicleBtn = document.getElementById('clear-vehicle-btn');
  const partsGrid = document.getElementById('parts-grid');
  const partsCount = document.getElementById('parts-count');
  
  let currentVehicle = null;
  
  // Load saved vehicles from localStorage
  function loadGarage() {
    const garage = JSON.parse(localStorage.getItem('autoparts_garage') || '[]');
    
    if (garage.length === 0) {
      garageVehicles.innerHTML = '<p class="empty-garage">No vehicles saved yet</p>';
      return;
    }
    
    garageVehicles.innerHTML = garage.map((vehicle, index) => `
      <div class="garage-vehicle-card" data-index="${index}">
        <span class="garage-vehicle-name">${vehicle.year} ${vehicle.make} ${vehicle.model}</span>
        <button class="remove-vehicle-btn" data-index="${index}">×</button>
      </div>
    `).join('');
  }
  
  // Save vehicle to garage
  function saveToGarage(vehicle) {
    const garage = JSON.parse(localStorage.getItem('autoparts_garage') || '[]');
    
    // Check if already exists
    const exists = garage.some(v => 
      v.year === vehicle.year && 
      v.make === vehicle.make && 
      v.model === vehicle.model
    );
    
    if (!exists) {
      garage.push(vehicle);
      localStorage.setItem('autoparts_garage', JSON.stringify(garage));
      loadGarage();
    }
  }
  
  // Remove vehicle from garage
  function removeFromGarage(index) {
    const garage = JSON.parse(localStorage.getItem('autoparts_garage') || '[]');
    garage.splice(index, 1);
    localStorage.setItem('autoparts_garage', JSON.stringify(garage));
    loadGarage();
  }
  
  // Cascade selects
  yearSelect.addEventListener('change', async function() {
    const year = this.value;
    
    if (!year) {
      makeSelect.disabled = true;
      makeSelect.innerHTML = '<option value="">Select Year First</option>';
      modelSelect.disabled = true;
      modelSelect.innerHTML = '<option value="">Select Make First</option>';
      engineSelect.disabled = true;
      engineSelect.innerHTML = '<option value="">Select Model First</option>';
      return;
    }
    
    // Fetch makes for selected year (AJAX call would go here)
    makeSelect.disabled = false;
    makeSelect.innerHTML = '<option value="">Loading...</option>';
    
    // Simulated data - in production this would be an AJAX call
    setTimeout(() => {
      const makes = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes', 'Nissan', 'Hyundai'];
      makeSelect.innerHTML = '<option value="">Select Make</option>' + 
        makes.map(m => `<option value="${m.toLowerCase()}">${m}</option>`).join('');
    }, 300);
  });
  
  makeSelect.addEventListener('change', function() {
    const make = this.value;
    
    if (!make) {
      modelSelect.disabled = true;
      modelSelect.innerHTML = '<option value="">Select Make First</option>';
      engineSelect.disabled = true;
      engineSelect.innerHTML = '<option value="">Select Model First</option>';
      return;
    }
    
    modelSelect.disabled = false;
    modelSelect.innerHTML = '<option value="">Loading...</option>';
    
    // Simulated data
    setTimeout(() => {
      const models = ['Camry', 'Accord', 'F-150', 'Silverado', '3 Series', 'C-Class', 'Altima', 'Sonata'];
      modelSelect.innerHTML = '<option value="">Select Model</option>' + 
        models.map(m => `<option value="${m.toLowerCase()}">${m}</option>`).join('');
    }, 300);
  });
  
  modelSelect.addEventListener('change', function() {
    const model = this.value;
    
    if (!model) {
      engineSelect.disabled = true;
      engineSelect.innerHTML = '<option value="">Select Model First</option>';
      return;
    }
    
    engineSelect.disabled = false;
    engineSelect.innerHTML = '<option value="">Loading...</option>';
    
    // Simulated data
    setTimeout(() => {
      const engines = ['2.0L 4-Cyl', '2.5L 4-Cyl', '3.5L V6', '5.0L V8', '1.5L Turbo'];
      engineSelect.innerHTML = '<option value="">Select Engine</option>' + 
        engines.map(e => `<option value="${e.toLowerCase()}">${e}</option>`).join('');
    }, 300);
  });
  
  // Form submission
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const vehicle = {
      year: yearSelect.value,
      make: makeSelect.value,
      model: modelSelect.value,
      engine: engineSelect.value,
    };
    
    if (!vehicle.year || !vehicle.make || !vehicle.model || !vehicle.engine) {
      alert('Please select all fields');
      return;
    }
    
    currentVehicle = vehicle;
    displaySelectedVehicle(vehicle);
    loadCompatibleParts(vehicle);
    addToGarageBtn.style.display = 'block';
  });
  
  // Display selected vehicle
  function displaySelectedVehicle(vehicle) {
    selectedVehicleName.textContent = `${vehicle.year} ${vehicle.make.charAt(0).toUpperCase() + vehicle.make.slice(1)} ${vehicle.model.charAt(0).toUpperCase() + vehicle.model.slice(1)} (${vehicle.engine})`;
    selectedVehicleBar.style.display = 'block';
  }
  
  // Clear selection
  clearVehicleBtn.addEventListener('click', function() {
    currentVehicle = null;
    selectedVehicleBar.style.display = 'none';
    partsGrid.innerHTML = `
      <div class="loading-state">
        <div class="spinner"></div>
        <p>Select your vehicle to see compatible parts</p>
      </div>
    `;
    addToGarageBtn.style.display = 'none';
  });
  
  // Add to garage
  addToGarageBtn.addEventListener('click', function() {
    if (currentVehicle) {
      saveToGarage(currentVehicle);
      alert('Vehicle added to garage!');
    }
  });
  
  // Remove from garage
  garageVehicles.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-vehicle-btn')) {
      const index = parseInt(e.target.dataset.index);
      removeFromGarage(index);
    }
  });
  
  // Load compatible parts (simulated)
  function loadCompatibleParts(vehicle) {
    partsGrid.innerHTML = `
      <div class="loading-state">
        <div class="spinner"></div>
        <p>Finding parts for your ${vehicle.year} ${vehicle.make} ${vehicle.model}...</p>
      </div>
    `;
    
    // Simulated AJAX call
    setTimeout(() => {
      const sampleParts = [
        { name: 'Premium Brake Pads', price: 89.99, image: '', partNumber: 'BP-001' },
        { name: 'Oil Filter', price: 12.99, image: '', partNumber: 'OF-002' },
        { name: 'Air Filter', price: 24.99, image: '', partNumber: 'AF-003' },
        { name: 'Spark Plugs (Set of 4)', price: 32.99, image: '', partNumber: 'SP-004' },
        { name: 'Timing Belt Kit', price: 149.99, image: '', partNumber: 'TB-005' },
        { name: 'Battery', price: 179.99, image: '', partNumber: 'BT-006' },
      ];
      
      partsGrid.innerHTML = sampleParts.map(part => `
        <div class="part-card">
          <div class="part-image-placeholder"></div>
          <h4>${part.name}</h4>
          <p class="part-number">Part #: ${part.partNumber}</p>
          <p class="part-price">$${part.price.toFixed(2)}</p>
          <button class="add-to-cart-sm">Add to Cart</button>
        </div>
      `).join('');
      
      partsCount.textContent = `${sampleParts.length} parts found`;
    }, 1000);
  }
  
  // Initialize
  loadGarage();
});
</script>

<style>
/* Additional styles for compatibility page */
.vehicle-compatibility-page .compatibility-hero {
  padding: 60px 0;
  background: linear-gradient(135deg, var(--color-dark) 0%, var(--color-mid) 100%);
}

.vehicle-selector-widget {
  background: var(--color-light);
  padding: 30px;
  border-radius: 8px;
  margin-top: 30px;
}

.vehicle-form .form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.search-parts-btn {
  width: 100%;
  padding: 15px 30px;
  background: var(--color-primary);
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.garage-section {
  margin-top: 30px;
  padding-top: 20px;
  border-top: 2px solid var(--color-border);
}

.garage-vehicles {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 15px;
}

.garage-vehicle-card {
  background: var(--color-mid);
  color: white;
  padding: 10px 15px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.remove-vehicle-btn {
  background: var(--color-primary);
  color: white;
  border: none;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  cursor: pointer;
}

.selected-vehicle-bar {
  background: var(--color-primary);
  color: white;
  padding: 15px 0;
}

.selected-vehicle-bar .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.clear-selection-btn {
  background: rgba(255,255,255,0.2);
  color: white;
  border: 1px solid white;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
}

.parts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-top: 30px;
}

.part-card {
  background: var(--color-light);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 20px;
  transition: transform 0.3s ease;
}

.part-card:hover {
  transform: translateY(-5px);
}

.category-tiles {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 20px;
  margin-top: 30px;
}

.category-tile {
  background: var(--color-light);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 30px 20px;
  text-align: center;
  text-decoration: none;
  color: var(--color-text);
  transition: all 0.3s ease;
}

.category-tile:hover {
  border-color: var(--color-primary);
  transform: translateY(-5px);
}
</style>

<?php get_footer(); ?>
