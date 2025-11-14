import { useParams, Link } from "react-router-dom";
import { ArrowLeft, Share2 } from "lucide-react";
import { ImageWithFallback } from "../components/figma/ImageWithFallback";
import { ArticleCard } from "../components/ArticleCard";

const ARTICLE_DATA: Record<string, any> = {
  "1": {
    id: 1,
    title: "Понимание благодати в современном мире",
    subtitle: "Как принципы христианства помогают в повседневной жизни",
    date: "10 ноября 2024",
    author: {
      name: "Александр Иванов",
      avatar: "https://images.unsplash.com/photo-1595436222774-4b1cd819aada?w=100"
    },
    featuredImage: "https://images.unsplash.com/photo-1709390594155-9b1db07d2883?w=1200"
  }
};

const RELATED_ARTICLES = [
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
  }
];

export function ArticleDetail() {
  const { id } = useParams();
  const article = ARTICLE_DATA[id || "1"] || ARTICLE_DATA["1"];
  
  return (
    <div className="min-h-screen">
      {/* Featured Image */}
      <div className="w-full h-[500px] bg-gray-100">
        <ImageWithFallback
          src={article.featuredImage}
          alt={article.title}
          className="w-full h-full object-cover"
        />
      </div>
      
      <div className="container mx-auto px-6 max-w-4xl">
        <article className="py-16">
          {/* Back Button */}
          <Link 
            to="/articles"
            className="inline-flex items-center gap-2 text-[#6B5144] hover:text-[#5a4438] transition-colors mb-8"
          >
            <ArrowLeft className="w-5 h-5" />
            Все статьи
          </Link>
          
          {/* Article Header */}
          <header className="mb-12">
            <h1 className="serif text-[3rem] font-[900] text-gray-900 mb-4 leading-tight">
              {article.title}
            </h1>
            
            <p className="serif text-[1.5rem] text-gray-600 italic mb-8">
              {article.subtitle}
            </p>
            
            <div className="flex items-center justify-between pb-8 border-b border-gray-200">
              <div className="flex items-center gap-4">
                <ImageWithFallback
                  src={article.author.avatar}
                  alt={article.author.name}
                  className="w-12 h-12 rounded-full object-cover"
                />
                <div>
                  <p className="text-gray-900">{article.author.name}</p>
                  <p className="text-gray-500">{article.date}</p>
                </div>
              </div>
              
              <button className="flex items-center gap-2 text-gray-600 hover:text-[#6B5144] transition-colors">
                <Share2 className="w-5 h-5" />
                <span>Поделиться</span>
              </button>
            </div>
          </header>
          
          {/* Article Content */}
          <div className="prose prose-lg max-w-none">
            <p className="text-gray-700 leading-[1.6] mb-6">
              В современном мире, полном суеты и материализма, концепция благодати может показаться 
              устаревшей или неуместной. Однако именно сегодня, как никогда прежде, нам необходимо 
              понимание этого фундаментального христианского принципа.
            </p>
            
            <h2 className="serif text-[1.75rem] font-[700] text-gray-900 mt-12 mb-4">
              Что такое благодать?
            </h2>
            
            <p className="text-gray-700 leading-[1.6] mb-6">
              Благодать — это незаслуженная милость Бога, дарованная человечеству несмотря на наши 
              недостатки и грехи. Это не то, что мы можем заработать своими усилиями или добрыми делами. 
              Благодать — это божественный дар, который изменяет нас изнутри.
            </p>
            
            <p className="text-gray-700 leading-[1.6] mb-6">
              Апостол Павел писал: "Ибо благодатью вы спасены через веру, и сие не от вас, Божий дар" 
              (Ефесянам 2:8). Эти слова подчеркивают, что спасение — это полностью Божья инициатива, 
              проявление Его любви к нам.
            </p>
            
            <blockquote className="border-l-4 border-[#F5C542] pl-6 my-8 italic text-gray-700">
              "Благодать — это не просто прощение; это сила, которая преображает нас и дает способность 
              жить по воле Божьей."
            </blockquote>
            
            <h2 className="serif text-[1.75rem] font-[700] text-gray-900 mt-12 mb-4">
              Благодать в повседневной жизни
            </h2>
            
            <p className="text-gray-700 leading-[1.6] mb-6">
              Понимание благодати имеет практические последствия для нашей повседневной жизни. Когда мы 
              осознаем, что Бог любит нас не за наши достижения, а просто потому, что мы Его дети, это 
              освобождает нас от бремени перфекционизма и самооправдания.
            </p>
            
            <h3 className="serif text-[1.5rem] font-[700] text-gray-900 mt-8 mb-4">
              Практические применения
            </h3>
            
            <p className="text-gray-700 leading-[1.6] mb-6">
              Во-первых, благодать учит нас смирению. Мы признаем, что не можем спасти себя собственными 
              силами. Это смирение становится основой для подлинного духовного роста.
            </p>
            
            <p className="text-gray-700 leading-[1.6] mb-6">
              Во-вторых, получив благодать от Бога, мы призваны проявлять ее к другим. Это означает 
              прощать тех, кто обидел нас, проявлять терпение к недостаткам ближних, и служить другим 
              без ожидания вознаграждения.
            </p>
            
            <ImageWithFallback
              src="https://images.unsplash.com/photo-1641491616155-2864f4d3afe1?w=800"
              alt="Prayer and reflection"
              className="w-full h-[400px] object-cover rounded-xl my-8"
            />
            
            <h2 className="serif text-[1.75rem] font-[700] text-gray-900 mt-12 mb-4">
              Жизнь благодати
            </h2>
            
            <p className="text-gray-700 leading-[1.6] mb-6">
              Жить в благодати — значит каждый день помнить о Божьей любви и позволять ей направлять 
              наши решения и отношения. Это не означает пассивность или безответственность; напротив, 
              благодать вдохновляет нас на активную жизнь веры.
            </p>
            
            <p className="text-gray-700 leading-[1.6] mb-6">
              В заключение, понимание благодати в современном мире остается столь же актуальным, как и 
              две тысячи лет назад. Это основа нашей веры, источник нашей надежды и сила, преобразующая 
              наши жизни.
            </p>
          </div>
          
          {/* Navigation */}
          <div className="flex gap-4 mt-12 pt-8 border-t border-gray-200">
            <Link 
              to="/article/2"
              className="text-[#6B5144] hover:text-[#5a4438] transition-colors"
            >
              ← Предыдущая статья
            </Link>
            <Link 
              to="/article/3"
              className="ml-auto text-[#6B5144] hover:text-[#5a4438] transition-colors"
            >
              Следующая статья →
            </Link>
          </div>
        </article>
      </div>
      
      {/* Related Articles */}
      <section className="bg-[#FAF8F5] py-20">
        <div className="container mx-auto px-6 max-w-7xl">
          <h2 className="serif text-[2rem] font-[900] text-gray-900 mb-10">
            Похожие статьи
          </h2>
          
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            {RELATED_ARTICLES.map((article) => (
              <ArticleCard key={article.id} {...article} />
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
