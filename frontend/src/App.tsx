import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';

// ----------------------------------------------------------------
// Páginas públicas / landing
// ----------------------------------------------------------------
import Navbar           from './components/landing/Navbar';
import HeroSection      from './components/landing/HeroSection';
import FeaturesSection  from './components/landing/FeaturesSection';
import StepsSection     from './components/landing/StepsSection';
import CTASection       from './components/landing/CTASection';
import Footer           from './components/landing/Footer';

// ----------------------------------------------------------------
// Auth (login, registro, etc.)
// ----------------------------------------------------------------
import Login            from './components/auth/Login';
import Register         from './components/auth/Register';
import ConfirmPassword  from './components/auth/ConfirmPassword';
import ForgotPassword   from './components/auth/ForgotPassword';
import ResetPassword    from './components/auth/ResetPassword';
import VerifyEmail      from './components/auth/VerifyEmail';

// ----------------------------------------------------------------
// SPA interna protegida (con Layout + Sidebar + subrutas)
// ----------------------------------------------------------------
import PrivateRoute     from './routes/PrivateRoute';  // <-- tu componente guard
import Layout           from './components/app/Layout';
import Aprender         from './components/app/Aprender';
import Desafios         from './components/app/Desafios';
import Mas              from './components/app/Mas';
import Sonidos          from './components/app/Sonidos';

function App() {
  return (
    <Router>
      <Routes>

        {/* ─────────────────────────────────────────
            Landing público completo
        ───────────────────────────────────────── */}
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

        {/* ─────────────────────────────────────────
            Rutas públicas de autenticación
        ───────────────────────────────────────── */}
        <Route path="/login"           element={<Login />} />
        <Route path="/register"        element={<Register />} />
        <Route path="/confirm-password" element={<ConfirmPassword />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/reset-password/:token" element={<ResetPassword />} />
        <Route path="/verify-email"    element={<VerifyEmail />} />

        {/* ─────────────────────────────────────────
            Rutas protegidas bajo /app/*
        ───────────────────────────────────────── */}
        <Route element={<PrivateRoute />}>
          <Route path="/app/*" element={<Layout />}>
            {/* default dentro de /app */}
            <Route index element={<Navigate to="aprender" replace />} />
            <Route path="aprender" element={<Aprender />} />
            <Route path="sonidos" element={<Sonidos />} />
            <Route path="desafios" element={<Desafios />} />
            <Route path="mas" element={<Mas />} />
            {/* catch dentro de /app */}
            <Route path="*" element={<Navigate to="aprender" replace />} />
          </Route>
        </Route>

        {/* ─────────────────────────────────────────
            Cualquier otra ruta redirige al landing
        ───────────────────────────────────────── */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;
