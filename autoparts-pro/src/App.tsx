import { Header } from './components/layout/Header';
import { Footer } from './components/layout/Footer';
import { ExplodedViewHero } from './components/sections/ExplodedViewHero';
import { ProductCard } from './components/products/ProductCard';
import { ThemeToggle } from './components/ui/ThemeToggle';
import { CustomCursor } from './components/ui/CustomCursor';
import { BackToTop } from './components/ui/BackToTop';
import { ChatBot } from './components/ui/ChatBot';
import { CartDrawer } from './components/layout/CartDrawer';
import { mockProducts, mockCategories } from './data/mockData';

function App() {
  return (
    <div className="min-h-screen bg-bg-primary">
      {/* Global UI Components */}
      <CustomCursor />
      <CartDrawer />
      
      {/* Header */}
      <Header />
      
      <main>
        {/* Hero Section with 3D Exploded View */}
        <ExplodedViewHero />
        
        {/* Featured Products Section */}
        <section className="section-padding bg-bg-secondary">
          <div className="container mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-4xl font-heading font-bold mb-4">
                Featured <span className="text-gradient">Products</span>
              </h2>
              <p className="text-mid-steel max-w-2xl mx-auto">
                Premium automotive parts engineered for performance and reliability
              </p>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {mockProducts.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          </div>
        </section>
        
        {/* Categories Section */}
        <section className="section-padding mechanical-grid">
          <div className="container mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-4xl font-heading font-bold mb-4">
                Shop by <span className="text-gradient">Category</span>
              </h2>
              <p className="text-mid-steel max-w-2xl mx-auto">
                Find exactly what you need from our comprehensive catalog
              </p>
            </div>
            
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
              {mockCategories.map((category) => (
                <a
                  key={category.id}
                  href={`/category/${category.slug}`}
                  className="group p-6 rounded-xl bg-bg-secondary hover:bg-primary transition-all duration-300 text-center"
                >
                  <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-bg-tertiary group-hover:bg-white/20 flex items-center justify-center transition-colors">
                    <svg className="w-8 h-8 text-primary group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                  </div>
                  <h3 className="font-heading font-bold group-hover:text-white transition-colors">
                    {category.name}
                  </h3>
                </a>
              ))}
            </div>
          </div>
        </section>
        
        {/* Brands Section */}
        <section className="section-padding bg-dark carbon-fiber">
          <div className="container mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-4xl font-heading font-bold text-white mb-4">
                Trusted <span className="text-gradient">Brands</span>
              </h2>
              <p className="text-mid-steel max-w-2xl mx-auto">
                Industry-leading manufacturers of premium automotive components
              </p>
            </div>
            
            <div className="flex flex-wrap justify-center items-center gap-12">
              {['Brembo', 'Bosch', 'K&N', 'NGK', 'Denso'].map((brand) => (
                <div
                  key={brand}
                  className="px-8 py-4 rounded-lg bg-bg-secondary/50 backdrop-blur hover:bg-primary/20 transition-colors cursor-pointer"
                >
                  <span className="text-xl font-heading font-bold text-mid-steel hover:text-white transition-colors">
                    {brand}
                  </span>
                </div>
              ))}
            </div>
          </div>
        </section>
        
        {/* Features Section */}
        <section className="section-padding bg-bg-secondary">
          <div className="container mx-auto px-4">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {[
                {
                  icon: (
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                  ),
                  title: 'Quality Guaranteed',
                  description: 'All parts meet or exceed OEM specifications',
                },
                {
                  icon: (
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                  ),
                  title: 'Fast Shipping',
                  description: 'Free delivery on orders over $99',
                },
                {
                  icon: (
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                  ),
                  title: 'Expert Support',
                  description: '24/7 assistance from automotive specialists',
                },
              ].map((feature, index) => (
                <div
                  key={index}
                  className="p-8 rounded-xl bg-bg-tertiary hover:shadow-2xl transition-shadow"
                >
                  <div className="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center mb-6">
                    <svg className="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      {feature.icon}
                    </svg>
                  </div>
                  <h3 className="text-xl font-heading font-bold mb-3">{feature.title}</h3>
                  <p className="text-mid-steel">{feature.description}</p>
                </div>
              ))}
            </div>
          </div>
        </section>
      </main>
      
      {/* Footer */}
      <Footer />
      
      {/* Floating UI Elements */}
      <BackToTop />
      <ChatBot />
    </div>
  );
}

export default App;
