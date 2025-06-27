// src/App.tsx
import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';

// Landing page
import Navbar           from './components/landing/Navbar';
import HeroSection      from './components/landing/HeroSection';
import FeaturesSection  from './components/landing/FeaturesSection';
import StepsSection     from './components/landing/StepsSection';
import CTASection       from './components/landing/CTASection';
import Footer           from './components/landing/Footer';

// Login / Registro
import Login            from './components/landing/Login';
import Register         from './components/landing/Register';

// Layout con Sidebar + rutas internas
import Layout           from './components/Layout';
import Aprender         from './components/Aprender';
import Desafios         from './components/Desafios';
import Mas from './components/landing/Mas';

function App() {
  return (
    <Router>
      <Routes>
        {/* ────────────── Ruta pública: Landing completo ────────────── */}
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

        {/* ────────────── Rutas públicas: login y registro ────────────── */}
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />

        {/* ────────────── Rutas protegidas bajo /app ────────────── */}
        <Route path="/app/*" element={<Layout />}>
          <Route index element={<Navigate to="aprender" replace />} />
          <Route path="aprender" element={<Aprender />} />
          <Route path="desafios" element={<Desafios />} />
          <Route path="mas" element={<Mas />} />
          <Route path="*" element={<Navigate to="aprender" replace />} />
        </Route>

        {/* ────────────── Catch-all: redirige a landing ────────────── */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;
