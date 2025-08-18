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
import Login            from './components/auth/Login';
import Register         from './components/auth/Register';

// Layout con Sidebar + rutas internas
import Layout           from './components/app/Layout';
import Aprender         from './components/app/Aprender';
import Desafios         from './components/app/Desafios';
import Mas              from './components/app/Mas';
import Sonidos          from './components/app/Sonidos';
import Perfil           from './components/app/Perfil'; // 👈 nuevo import

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
          <Route path="sonidos"  element={<Sonidos />} />
          <Route path="desafios" element={<Desafios />} />
          <Route path="perfil"   element={<Perfil />} /> {/* 👈 nueva ruta */}
          <Route path="mas"      element={<Mas />} />
          <Route path="*"        element={<Navigate to="aprender" replace />} />
        </Route>

        {/* ────────────── Catch-all: redirige a landing ────────────── */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;
