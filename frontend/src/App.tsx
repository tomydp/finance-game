// src/App.tsx
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { Toaster } from "react-hot-toast";

import Navbar           from './components/landing/Navbar';
import HeroSection      from './components/landing/HeroSection';
import FeaturesSection  from './components/landing/FeaturesSection';
import StepsSection     from './components/landing/StepsSection';
import CTASection       from './components/landing/CTASection';
import Footer           from './components/landing/Footer';

import Login            from './components/auth/Login';
import Register         from './components/auth/Register';

import Layout           from './components/app/Layout';
import AprenderPage     from './components/app/Aprender/pages/Aprender';

import Desafios         from './components/app/Desafios';
import Mas              from './components/app/Mas';
import Sonidos          from './components/app/Sonidos';
import Perfil           from './components/app/Perfil/pages/Perfil';

// ✅ Nuevo: Ranking
import RankingPage      from './components/app/Ranking/pages/Ranking';

import Store            from './components/app/Store';

function App() {
  return (
    <Router>
      <Toaster position="top-center" reverseOrder={false} />

      <Routes>
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

        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />

        <Route path="/app/*" element={<Layout />}>
          <Route index element={<Navigate to="aprender" replace />} />
          <Route path="aprender" element={<AprenderPage />} />
          <Route path="sonidos"  element={<Sonidos />} />
          {/* ✅ Nueva ruta */}
          <Route path="ranking"  element={<RankingPage />} />
          <Route path="desafios" element={<Desafios />} />
          <Route path="tienda"   element={<Store />} />
          <Route path="perfil"   element={<Perfil />} />
          <Route path="mas"      element={<Mas />} />
          <Route path="*"        element={<Navigate to="aprender" replace />} />
        </Route>

        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;
