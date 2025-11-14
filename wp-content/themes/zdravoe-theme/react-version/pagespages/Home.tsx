import { BookCard } from "../components/BookCard";
import { ArticleCard } from "../components/ArticleCard";
import { ChevronRight } from "lucide-react";
import { Link } from "react-router-dom";
import { ImageWithFallback } from "../components/figma/ImageWithFallback";

const BOOKS = [
  {
    id: 1,
    title: "Основы христианской веры",
    subtitle: "Путь к духовному росту",
    coverImage: "https://images.unsplash.com/photo-1645012656964-8632d7635191?w=400"
  },
  {
    id: 2,
    title: "Молитва и размышление",
    subtitle: "Практическое руководство",
    coverImage: "https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=400"
  },
  {
    id: 3,
    title: "Жизнь в вере",
    subtitle: "Современные вызовы",
    coverImage: "https://images.unsplash.com/photo-1589998059171-988d887df646?w=400"
  },
  {
    id: 4,
    title: "Библейская мудрость",
    subtitle: "Для повседневной жизни",
    coverImage: "https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400"
  }
];

const ARTICLES = [
  {
    id: 1,
    title: "Понимание благодати в современном мире",
    subtitle: "Как принципы христианства помогают в повседневной жизни",
    date: "10 ноября 2024",
    author: {
      name: "Александр Иванов",
      avatar: "https://images.unsplash.com/photo-1595436222774-4b1cd819aada?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1709390594155-9b1db07d2883?w=600"
  },
  {
    id: 2,
    title: "Роль молитвы в духовной жизни",
    subtitle: "Практические советы для углубления молитвенного опыта",
    date: "5 ноября 2024",
    author: {
      name: "Мария Петрова",
      avatar: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1641491616155-2864f4d3afe1?w=600"
  },
  {
    id: 3,
    title: "Христианское мировоззрение и наука",
    subtitle: "Находя гармонию между верой и разумом",
    date: "1 ноября 2024",
    author: {
      name: "Дмитрий Соколов",
      avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1759310707282-8e5e7bfc8066?w=600"
  },
  {
    id: 4,
    title: "Служение и сообщество",
    subtitle: "Построение крепких христианских общин",
    date: "28 октября 2024",
    author: {
      name: "Елена Волкова",
      avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1639916765637-43de505e45a0?w=600"
  },
  {
    id: 5,
    title: "Воспитание детей в вере",
    subtitle: "Передача духовных ценностей следующему поколению",
    date: "22 октября 2024",
    author: {
      name: "Ирина Кузнецова",
      avatar: "https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1516414447565-b14be0adf13e?w=600"
  },
  {
    id: 6,
    title: "Библейское толкование",
    subtitle: "Методы и подходы к изучению Священного Писания",
    date: "15 октября 2024",
    author: {
      name: "Павел Морозов",
      avatar: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1532012197267-da84d127e765?w=600"
  }
];

export function Home() {
  return (
    <div className="min-h-screen">
      {/* Hero Section */}
      <section className="container mx-auto px-6 py-16 max-w-7xl">
        <div className="grid md:grid-cols-2 gap-12 items-center">
          <div className="space-y-6">
            <h1 className="serif text-[3rem] font-[900] text-gray-900 leading-tight">
              Здравое мышление в мире опасностей
            </h1>
            <p className="text-[1.125rem] text-gray-600 leading-relaxed">
              По духу в материалах сайта звучит призыв к здравомыслию читателя, чтобы он смог научиться отделять зерна истины Учения Христа от плевел научно-религиозных идей, догматов, толкований.
            </p>
            <p className="text-[1.125rem] text-gray-600 leading-relaxed">
              Автором статей и книг является Анатолий Ададуров.
            </p>
            <button className="bg-[#F5C542] text-black px-8 py-3 rounded-full hover:bg-[#e6b832] transition-colors inline-flex items-center gap-2 mt-4">
              Подробнее
              <ChevronRight className="w-5 h-5" />
            </button>
          </div>
          
          <div className="relative">
            <ImageWithFallback
              src="https://images.unsplash.com/photo-1641491616155-2864f4d3afe1?w=800"
              alt="Christian cross silhouette"
              className="w-full h-[500px] object-cover rounded-2xl shadow-lg"
            />
          </div>
        </div>
      </section>
      
      {/* About Section */}
      <section className="bg-[#C9B8A8] text-gray-900 py-20 mt-16">
        <div className="container mx-auto px-6 max-w-7xl">
          <div className="grid md:grid-cols-2 gap-12 items-center">
            <div>
              <ImageWithFallback
                src="https://images.unsplash.com/photo-1709390594166-9ad7b012f698?w=700"
                alt="Bible pages"
                className="w-full h-[400px] object-cover rounded-xl"
              />
            </div>
            
            <div className="space-y-6">
              <blockquote className="serif text-[1.75rem] font-[700] italic leading-tight text-gray-900">
                "И вот благовестие, которое мы слышали от Него и возвещаем вам: Бог есть свет, и нет в Нем никакой тьмы"
              </blockquote>
              <p className="text-gray-700 italic">— 1 Иоанна 1:5</p>
              
              <p className="text-gray-800 leading-relaxed">
                Никто не приходит в мир с готовыми взглядами на жизнь. Люди рождаются свободными, как от всяких наук, так и от разных религий. Каждый имеет право идти своей дорогой по жизни.
              </p>
              
              <p className="text-gray-800 leading-relaxed">
                При этом есть одно, что неизбежно, раньше или позже объединяет всех. Называется оно – гроб, смерть. Почему человек умирает? Откуда берется несчастье? Из-за чего большинство людей даже не могут просто дожить до старости? Это – по-настоящему трудные вопросы.
              </p>
              
              <p className="text-gray-800 leading-relaxed">
                Настоящий сайт создан для людей, как склонных к вере в Бога, так и для тех, кто не задумывается о Боге. В способности здраво рассуждать нуждаются все. Тем более – в наше время.
              </p>
            </div>
          </div>
        </div>
      </section>
      
      {/* Books Section */}
      <section className="container mx-auto px-6 py-20 max-w-7xl">
        <div className="flex items-center justify-between mb-10">
          <h2 className="serif text-[2.5rem] font-[900] text-gray-900">Книги</h2>
          <Link 
            to="/books" 
            className="bg-[#F5C542] text-black px-6 py-3 rounded-full hover:bg-[#e6b832] transition-colors inline-flex items-center gap-2"
          >
            Смотреть все
            <ChevronRight className="w-5 h-5" />
          </Link>
        </div>
        
        <div className="overflow-x-auto -mx-6 px-6">
          <div className="flex gap-6 pb-4" style={{ width: 'max-content' }}>
            {BOOKS.map((book) => (
              <div key={book.id} className="w-[280px] flex-shrink-0">
                <BookCard {...book} />
              </div>
            ))}
          </div>
        </div>
      </section>
      
      {/* Articles Section */}
      <section className="container mx-auto px-6 py-20 max-w-7xl">
        <div className="flex items-center justify-between mb-10">
          <h2 className="serif text-[2.5rem] font-[900] text-gray-900">Статьи</h2>
          <Link 
            to="/articles" 
            className="bg-[#F5C542] text-black px-6 py-3 rounded-full hover:bg-[#e6b832] transition-colors inline-flex items-center gap-2"
          >
            Все статьи
            <ChevronRight className="w-5 h-5" />
          </Link>
        </div>
        
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          {ARTICLES.map((article) => (
            <ArticleCard key={article.id} {...article} />
          ))}
        </div>
      </section>
    </div>
  );
}