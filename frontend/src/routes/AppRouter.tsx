import { BrowserRouter, Routes, Route } from 'react-router-dom';
import PrivateRoute from '../components/PrivateRoute';
import Login     from '../components/auth/Login';
import Register  from '../components/auth/Register';
import Layout    from '../components/app/Layout';
import Desafios from '../components/app/Desafios';

export default function AppRouter() {
  return (
    <BrowserRouter>
      <Routes>
        {/* públicas */}
        <Route path="/login"           element={<Login />} />
        <Route path="/register"        element={<Register />} />

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
