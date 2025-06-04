import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';

// Landing page components
import Navbar from './components/landing/Navbar';
import HeroSection from './components/landing/HeroSection';
import FeaturesSection from './components/landing/FeaturesSection';
import StepsSection from './components/landing/StepsSection';
import CTASection from './components/landing/CTASection';
import Footer from './components/landing/Footer';

// App (post-login) components
import Layout from './components/Layout';
import Dashboard from './components/Dashbord';
import Login from './components/Login';
import Aprender from './components/Aprender';
import Mas from './components/ Más';

function App() {
  return (
    <Router>
      <Routes>
        {/* Ruta pública: Landing */}
        <Route
          path="/"
          element={
            <>
              <Navbar />
              <HeroSection />
              <FeaturesSection />
              <StepsSection />
              <CTASection />
              <Footer />
            </>
          }
        />

        {/* Ruta privada: Layout englobando al Dashboard */}
        <Route
          path="/app"
          element={
            <Layout>
              <Dashboard />
            </Layout>
          }
        />

        {/* Ruta privada: Aprender */}
        <Route
          path="/aprendizaje"
          element={
            <Layout>
              <Aprender />
            </Layout>
          }
        />

        {/* Ruta privada: Más */}
        <Route
          path="/mas"
          element={
            <Layout>
              <Mas />
            </Layout>
          }
        />

        {/* Ruta de login (si la necesitas) */}
        <Route path="/login" element={<Login />} />
      </Routes>
    </Router>
  );
}

export default App;