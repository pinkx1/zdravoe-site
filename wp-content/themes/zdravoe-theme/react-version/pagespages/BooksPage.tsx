import { BookCard } from "../components/BookCard";

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
  },
  {
    id: 5,
    title: "Путь ученичества",
    subtitle: "Следование за Христом",
    coverImage: "https://images.unsplash.com/photo-1516414447565-b14be0adf13e?w=400"
  },
  {
    id: 6,
    title: "Церковь и общество",
    subtitle: "Роль христианства сегодня",
    coverImage: "https://images.unsplash.com/photo-1532012197267-da84d127e765?w=400"
  },
  {
    id: 7,
    title: "Духовные дисциплины",
    subtitle: "Практика христианской жизни",
    coverImage: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400"
  },
  {
    id: 8,
    title: "История христианства",
    subtitle: "От апостолов до наших дней",
    coverImage: "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400"
  },
  {
    id: 9,
    title: "Богословие спасения",
    subtitle: "Понимание искупления",
    coverImage: "https://images.unsplash.com/photo-1509021436665-8f07dbf5bf1d?w=400"
  },
  {
    id: 10,
    title: "Христианская этика",
    subtitle: "Жизнь по заповедям",
    coverImage: "https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400"
  },
  {
    id: 11,
    title: "Поклонение и хвала",
    subtitle: "Сердце христианина",
    coverImage: "https://images.unsplash.com/photo-1457369804613-52c61a468e7d?w=400"
  },
  {
    id: 12,
    title: "Миссия и служение",
    subtitle: "Призвание каждого верующего",
    coverImage: "https://images.unsplash.com/photo-1519682337058-a94d519337bc?w=400"
  }
];

export function BooksPage() {
  return (
    <div className="min-h-screen">
      <section className="container mx-auto px-6 py-16 max-w-7xl">
        <h1 className="serif text-[3rem] font-[900] text-gray-900 mb-4">Книги</h1>
        <p className="text-[1.125rem] text-gray-600 mb-12 max-w-3xl">
          Коллекция книг для углубления вашей веры и понимания христианского учения.
        </p>
        
        <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
          {BOOKS.map((book) => (
            <BookCard key={book.id} {...book} />
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
          <button className="w-10 h-10 rounded-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
            3
          </button>
        </div>
      </section>
    </div>
  );
}
