import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { useState } from "react";
import { Header } from "./components/Header";
import { Footer } from "./components/Footer";
import { ContactModal } from "./components/ContactModal";
import { Home } from "./pages/Home";
import { BooksPage } from "./pages/BooksPage";
import { ArticlesPage } from "./pages/ArticlesPage";
import { BookDetail } from "./pages/BookDetail";
import { ArticleDetail } from "./pages/ArticleDetail";

export default function App() {
  const [isContactModalOpen, setIsContactModalOpen] = useState(false);
  
  return (
    <BrowserRouter>
      <div className="flex flex-col min-h-screen">
        <Header onContactClick={() => setIsContactModalOpen(true)} />
        
        <main className="flex-1">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/books" element={<BooksPage />} />
            <Route path="/articles" element={<ArticlesPage />} />
            <Route path="/book/:id" element={<BookDetail />} />
            <Route path="/article/:id" element={<ArticleDetail />} />
            <Route path="/preview_page.html" element={<Navigate to="/" replace />} />
            <Route path="*" element={<Navigate to="/" replace />} />
          </Routes>
        </main>
        
        <Footer />
        
        <ContactModal 
          isOpen={isContactModalOpen}
          onClose={() => setIsContactModalOpen(false)}
        />
      </div>
    </BrowserRouter>
  );
}