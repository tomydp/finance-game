
import Navbar from './components/Navbar';

function App() {
  return (
    <>
    <div className="bg-[#0d1321] min-h-screen text-white">
      <Navbar />
      <main className="p-6">
        {/* contenido de landing */}
        <h1 className="text-3xl font-bold">¡Bienvenido a FinanzApp!</h1>
      </main>
    </div>
  </>
   );
}

export default App;

