import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.tsx'
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import Login from './pages/login/page.tsx'
import RegisterUser from './pages/register/page.tsx'
import AdminPanel from './pages/admin/page.tsx'

createRoot(document.getElementById('root')!).render(
  <StrictMode>
   <BrowserRouter>
      <Routes>
        <Route path="/" element={<App />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<RegisterUser />} />
        <Route path="/admin" element={<AdminPanel />}  />
      </Routes>
    </BrowserRouter>
  </StrictMode>,
)
