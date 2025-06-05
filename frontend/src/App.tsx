// src/App.tsx
import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';

// Landing page
import Navbar         from './components/landing/Navbar';
import HeroSection    from './components/landing/HeroSection';
import FeaturesSection from './components/landing/FeaturesSection';
import StepsSection   from './components/landing/StepsSection';
import CTASection     from './components/landing/CTASection';
import Footer         from './components/landing/Footer';

// Login / Registro
import Login          from './components/Login';

// Layout con Sidebar + rutas internas
import Layout         from './components/Layout';
import Desafios       from './components/Desafios';
import Aprender from './components/Aprender';
import Mas from './components/ Más';
// (importa aquí cualquier otro componente que quieras bajo /app, p.ej. Aprender, Sonidos, etc.)

function App() {
  return (
    <Router>
      <Routes>
        {/* Ruta pública: Landing completo */}
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

        {/* Ruta de login/registro */}
        <Route path="/login" element={<Login />} />

        {/* Rutas protegidas bajo /app */}
        <Route path="/app/*" element={<Layout />}>
          {/* Ejemplo de rutas hijas; ajusta con los componentes reales */}
          <Route path="desafios" element={<Desafios />} />
          {/* <Route path="aprender" element={<Aprender />} /> */}
          {/* <Route path="sonidos"  element={<Sonidos />} /> */}
          {/* <Route path="ligas"    element={<Ligas />} /> */}
          {/* <Route path="desafios" element={<Desafios />} /> */}
          {/* <Route path="tienda"   element={<Tienda />} /> */}
          {/* <Route path="perfil"   element={<Perfil />} /> */}
          {/* <Route path="mas"      element={<Mas />} /> */}

          {/* Cualquier ruta inválida dentro de /app redirige a /app */}
          <Route path="*" element={<Navigate to="/app" replace />} />
        </Route>

        {/* Cualquier otra ruta (no coincide con las anteriores) redirige a "/" */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;
