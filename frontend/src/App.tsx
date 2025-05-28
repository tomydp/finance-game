import Navbar from './components/Navbar';
import HeroSection from './components/HeroSection';
import FeaturesSection from './components/FeaturesSection';
import StepsSection from './components/StepsSection';
import CTASection from './components/CTASection';
import Footer from './components/Footer';


function App() {
  return (
    <>
    <div className="bg-[#0d1321] min-h-screen text-white">
      <Navbar />
      <HeroSection />
      <FeaturesSection />
      <StepsSection />
      <CTASection />
      <Footer />
    </div>
  </>
   );
}

export default App;

