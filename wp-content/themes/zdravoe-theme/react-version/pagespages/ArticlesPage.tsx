import { ArticleCard } from "../components/ArticleCard";

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
  },
  {
    id: 7,
    title: "Пост и духовная дисциплина",
    subtitle: "Древняя практика для современных христиан",
    date: "10 октября 2024",
    author: {
      name: "Анна Смирнова",
      avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600"
  },
  {
    id: 8,
    title: "Христианство и культура",
    subtitle: "Влияние веры на искусство и общество",
    date: "5 октября 2024",
    author: {
      name: "Сергей Николаев",
      avatar: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=600"
  },
  {
    id: 9,
    title: "Надежда в трудные времена",
    subtitle: "Библейские принципы преодоления испытаний",
    date: "28 сентября 2024",
    author: {
      name: "Ольга Федорова",
      avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1509021436665-8f07dbf5bf1d?w=600"
  }
];

export function ArticlesPage() {
  return (
    <div className="min-h-screen">
      <section className="container mx-auto px-6 py-16 max-w-7xl">
        <h1 className="serif text-[3rem] font-[900] text-gray-900 mb-4">Статьи</h1>
        <p className="text-[1.125rem] text-gray-600 mb-12 max-w-3xl">
          Исследуйте актуальные темы христианской жизни через наши тщательно подготовленные статьи.
        </p>
        
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          {ARTICLES.map((article) => (
            <ArticleCard key={article.id} {...article} />
          ))}
        </div>
        
        {/* Pagination */}
        <div className="flex justify-center gap-2 mt-12">
          <button className="w-10 h-10 rounded-full bg-[#F5C542] text-black hover:bg-[#e6b832] transition-colors">
            1
          </button>
          <button className="w-10 h-10 rounded-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
            2
          </button>
        </div>
      </section>
    </div>
  );
}
