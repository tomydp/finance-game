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

// Layout con Sidebar + rutas internas
import Layout           from './components/Layout';
import Aprender         from './components/Aprender';
import Desafios         from './components/Desafios';
import Mas              from './components/Mas';
import Sonidos          from './components/Sonidos';
import Ligas            from './components/Ligas';
// (importa aquí cualquier otro componente que quieras bajo /app, p.ej. Sonidos, Ligas, etc.)

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

        {/* ────────────── Ruta de login/registro ────────────── */}
        <Route path="/login" element={<Login />} />

        {/* ────────────── Rutas protegidas bajo /app ────────────── */}
        <Route path="/app/*" element={<Layout />}>
          {/* Cuando la URL es exactamente "/app", redirige a "/app/aprender" */}
          <Route index element={<Navigate to="aprender" replace />} />

          {/* Rutas hijas dentro de /app */}
          <Route path="aprender" element={<Aprender />} />
          <Route path="sonidos"  element={<Sonidos />} />
          <Route path="ligas"    element={<Ligas />} />
          <Route path="desafios" element={<Desafios />} />
          {/* <Route path="tienda"   element={<Tienda />} /> */}
          {/* <Route path="perfil"   element={<Perfil />} /> */}
          <Route path="mas" element={<Mas />} />

          {/* Cualquier otra ruta inválida dentro de /app redirige a "/app/aprender" */}
          <Route path="*" element={<Navigate to="aprender" replace />} />
        </Route>

        {/* ────────────── Cualquier otra ruta redirige a "/" ────────────── */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;
