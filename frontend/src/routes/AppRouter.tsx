import { BrowserRouter, Routes, Route } from 'react-router-dom';
import PrivateRoute from './PrivateRoute';
import Login     from '../components/auth/Login';
import Register  from '../components/auth/Register';
import ForgotPassword from '../components/auth/ForgotPassword';
import ResetPassword  from '../components/auth/ResetPassword';
import VerifyEmail    from '../components/auth/VerifyEmail';
import Layout    from '../components/app/Layout';
import Desafios from '../components/app/Desafios';

export default function AppRouter() {
  return (
    <BrowserRouter>
      <Routes>
        {/* públicas */}
        <Route path="/login"           element={<Login />} />
        <Route path="/register"        element={<Register />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/reset-password/:token" element={<ResetPassword />} />
        <Route path="/verify-email"    element={<VerifyEmail />} />

        {/* privadas */}
        <Route element={<PrivateRoute />}>
          <Route element={<Layout />}>
            <Route path="/"          element={<div>Dashboard</div>} />
            <Route path="/desafios"  element={<Desafios />} />
            {/* …más rutas… */}
          </Route>
        </Route>
      </Routes>
    </BrowserRouter>
  );
}
