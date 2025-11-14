import { useParams, Link } from "react-router-dom";
import { ChevronRight, ArrowLeft } from "lucide-react";
import { ImageWithFallback } from "../components/figma/ImageWithFallback";

const BOOK_DATA: Record<string, any> = {
  "1": {
    id: 1,
    title: "Основы христианской веры",
    subtitle: "Путь к духовному росту",
    coverImage: "https://images.unsplash.com/photo-1645012656964-8632d7635191?w=400",
    description: "Эта книга представляет собой всестороннее введение в основные принципы христианской веры. Она предназначена как для новых верующих, так и для тех, кто желает углубить свое понимание библейских истин.",
    tableOfContents: [
      { id: 1, title: "Глава 1: Природа Бога" },
      { id: 2, title: "Глава 2: Человек и грех" },
      { id: 3, title: "Глава 3: Спасение через Христа" },
      { id: 4, title: "Глава 4: Жизнь в Духе" },
      { id: 5, title: "Глава 5: Церковь и ее миссия" },
      { id: 6, title: "Глава 6: Последние времена" }
    ]
  }
};

export function BookDetail() {
  const { id } = useParams();
  const book = BOOK_DATA[id || "1"] || BOOK_DATA["1"];
  
  return (
    <div className="min-h-screen bg-[#FAF8F5]">
      <div className="container mx-auto px-6 py-16 max-w-7xl">
        <Link 
          to="/books"
          className="inline-flex items-center gap-2 text-[#6B5144] hover:text-[#5a4438] transition-colors mb-8"
        >
          <ArrowLeft className="w-5 h-5" />
          Назад к списку книг
        </Link>
        
        <div className="grid lg:grid-cols-[350px_1fr] gap-12">
          {/* Sidebar */}
          <aside className="space-y-8">
            {/* Book Cover */}
            <div className="bg-white rounded-xl shadow-lg overflow-hidden">
              <ImageWithFallback
                src={book.coverImage}
                alt={book.title}
                className="w-full aspect-[3/4] object-cover"
              />
            </div>
            
            {/* Table of Contents */}
            <div className="bg-white rounded-xl shadow-sm p-6">
              <h3 className="serif text-[1.25rem] font-[700] text-gray-900 mb-4">
                Оглавление
              </h3>
              <nav className="space-y-3">
                {book.tableOfContents.map((chapter: any) => (
                  <button
                    key={chapter.id}
                    className="w-full text-left px-4 py-2.5 rounded-lg hover:bg-[#FAF8F5] transition-colors text-gray-700 hover:text-[#6B5144]"
                  >
                    {chapter.title}
                  </button>
                ))}
              </nav>
            </div>
          </aside>
          
          {/* Main Content */}
          <main className="bg-white rounded-xl shadow-sm p-12">
            <h1 className="serif text-[3rem] font-[900] text-gray-900 mb-4 leading-tight">
              {book.title}
            </h1>
            
            <p className="serif text-[1.5rem] text-gray-600 italic mb-8">
              {book.subtitle}
            </p>
            
            <div className="h-px bg-gradient-to-r from-[#6B5144] via-[#6B5144]/30 to-transparent mb-8" />
            
            <div className="prose prose-lg max-w-none">
              <p className="text-gray-700 leading-[1.6] mb-6">
                {book.description}
              </p>
              
              <p className="text-gray-700 leading-[1.6] mb-6">
                В первой главе мы рассмотрим фундаментальные атрибуты Бога, такие как Его всемогущество, 
                вездесущность и любовь. Понимание природы Бога является основой для всего остального в 
                христианской жизни.
              </p>
              
              <p className="text-gray-700 leading-[1.6] mb-6">
                Вторая глава посвящена пониманию человеческой природы и проблемы греха. Мы исследуем, 
                как падение человека повлияло на наши отношения с Богом и почему нам нужно искупление.
              </p>
              
              <h2 className="serif text-[1.75rem] font-[700] text-gray-900 mt-12 mb-4">
                Основные темы книги
              </h2>
              
              <p className="text-gray-700 leading-[1.6] mb-6">
                Эта книга охватывает ключевые аспекты христианского учения, включая доктрины о Троице, 
                спасении, освящении и эсхатологии. Каждая глава построена таким образом, чтобы 
                последовательно раскрывать эти важные темы.
              </p>
              
              <div className="h-px bg-gradient-to-r from-[#6B5144]/30 via-[#6B5144]/30 to-transparent my-8" />
              
              <blockquote className="border-l-4 border-[#F5C542] pl-6 my-8 italic text-gray-700">
                "Познание Бога — это не просто интеллектуальное упражнение, но преобразующее переживание, 
                которое меняет всю нашу жизнь."
              </blockquote>
              
              <p className="text-gray-700 leading-[1.6] mb-6">
                В заключительных главах мы обсудим практическое применение этих истин в повседневной жизни 
                верующего. Цель этой книги — не только информировать, но и формировать учеников Христа.
              </p>
            </div>
            
            <div className="flex gap-4 mt-12 pt-8 border-t border-gray-200">
              <button className="flex-1 bg-[#F5C542] text-black px-6 py-3 rounded-full hover:bg-[#e6b832] transition-colors inline-flex items-center justify-center gap-2">
                Следующая глава
                <ChevronRight className="w-5 h-5" />
              </button>
            </div>
          </main>
        </div>
      </div>
    </div>
  );
}
