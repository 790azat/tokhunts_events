<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Work;
use Illuminate\Database\Seeder;

/**
 * Real Tokhunts Events works from their Instagram, with media shipped in public/media/works.
 * Skips works that already exist (by slug), so it is safe to run more than once.
 */
class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        foreach ($this->works() as $slug => $work) {
            if (Work::where('slug', $slug)->exists()) {
                continue;
            }

            $model = Work::create([
                'slug' => $slug,
                'category_id' => $categories[$work['category']] ?? null,
                'title' => $work['title'],
                'description' => $work['description'],
                'event_date' => $work['date'],
                'is_featured' => $work['featured'] ?? false,
            ]);

            $position = 0;
            foreach (range(1, $work['photos']) as $n) {
                $model->media()->create(['type' => 'image', 'url' => "/media/works/{$slug}/{$n}.webp", 'position' => $position++]);
            }
            foreach ($work['videos'] ?? [] as $video) {
                $model->media()->create(['type' => 'video', 'url' => "/media/works/{$slug}/{$video}.mp4", 'position' => $position++]);
            }
        }
    }

    private function works(): array
    {
        return [
            'pearl-birthday' => [
                'category' => 'birthdays', 'featured' => true, 'date' => '2026-09-18', 'photos' => 5,
                'title' => ['hy' => 'Ծննդյան տոն մարգարիտներով', 'ru' => 'День рождения в жемчуге', 'en' => 'Pearl birthday'],
                'description' => [
                    'hy' => 'Սպիտակ վարագույրներ, մարգարիտե շարաններ, բյուրեղյա ջահեր և «Happy Birthday» նեոնային գրություն։',
                    'ru' => 'Белые драпировки, нити жемчуга, хрустальные люстры и неоновая надпись Happy Birthday.',
                    'en' => 'White drapery, strings of pearls, crystal chandeliers and a Happy Birthday neon sign.',
                ],
            ],
            'costume-characters' => [
                'category' => 'kids', 'featured' => true, 'date' => '2026-06-30', 'photos' => 5, 'videos' => ['video'],
                'title' => ['hy' => 'Կոստյումավորված հերոսներ և անիմատորներ', 'ru' => 'Ростовые куклы и аниматоры', 'en' => 'Costumed characters and animators'],
                'description' => [
                    'hy' => 'Ջեսին և Վուդին, արծաթե նապաստակը, LOL տիկնիկները և հրեշտակները՝ հերոսներ, որոնք տոնը հեքիաթ են դարձնում։',
                    'ru' => 'Джесси и Вуди, серебряный кролик, куклы LOL и ангелы: герои, которые превращают праздник в сказку.',
                    'en' => 'Jessie and Woody, a silver bunny, LOL dolls and angels: characters that turn a party into a fairy tale.',
                ],
            ],
            'wedding-photo-story' => [
                'category' => 'weddings', 'date' => '2026-06-22', 'photos' => 6,
                'title' => ['hy' => 'Հարսանեկան պատմություն', 'ru' => 'Свадебная история', 'en' => 'A wedding story'],
                'description' => [
                    'hy' => 'Հարսի պատրաստությունները, պսակադրությունը և նորապսակների զբոսանքը անտառում։',
                    'ru' => 'Сборы невесты, венчание и прогулка молодожёнов в лесу.',
                    'en' => 'The bride getting ready, the church ceremony and a walk in the woods.',
                ],
            ],
            'white-wedding-decor' => [
                'category' => 'weddings', 'featured' => true, 'date' => '2026-02-24', 'photos' => 6, 'videos' => ['video'],
                'title' => ['hy' => 'Ձյունաճերմակ հարսանիք մոմերով', 'ru' => 'Белоснежная свадьба при свечах', 'en' => 'Snow-white candlelit wedding'],
                'description' => [
                    'hy' => 'Սպիտակ վարդեր, կալաներ, հարյուրավոր մոմեր և հսկա ժապավեններ։',
                    'ru' => 'Белые розы, каллы, сотни свечей и огромные банты.',
                    'en' => 'White roses, calla lilies, hundreds of candles and giant bows.',
                ],
            ],
            'blue-arch-kids-birthday' => [
                'category' => 'kids', 'featured' => true, 'date' => '2026-02-13', 'photos' => 3,
                'title' => ['hy' => 'Մանկական ծնունդ կապույտ կամարով', 'ru' => 'Детский день рождения с голубой аркой', 'en' => 'Kids birthday with a blue balloon arch'],
                'description' => [
                    'hy' => 'Կապույտ և սպիտակ փուչիկներից կամար, նեոնային գրություն և մուլտհերոսներ։',
                    'ru' => 'Арка из голубых и белых шаров, неоновая надпись и весёлые герои мультфильма.',
                    'en' => 'An arch of blue and white balloons, a neon sign and cheerful cartoon heroes.',
                ],
            ],
            'pink-balloons' => [
                'category' => 'birthdays', 'date' => '2026-02-07', 'photos' => 3,
                'title' => ['hy' => 'Վարդագույն փուչիկների սենյակ', 'ru' => 'Комната розовых шаров', 'en' => 'A room of pink balloons'],
                'description' => [
                    'hy' => 'Սրտիկ փուչիկներ, կոնֆետիով փուչիկներ և վարդագույն ոսկի՝ անակնկալ հենց շեմից։',
                    'ru' => 'Шары-сердца, шары с конфетти и розовое золото: сюрприз прямо с порога.',
                    'en' => 'Heart balloons, confetti balloons and rose gold: a surprise from the doorstep.',
                ],
            ],
            'white-candle-photo-zone' => [
                'category' => 'birthdays', 'date' => '2026-01-30', 'photos' => 4,
                'title' => ['hy' => 'Սպիտակ ֆոտոզոնա մոմերով', 'ru' => 'Белая фотозона со свечами', 'en' => 'White candlelit photo zone'],
                'description' => [
                    'hy' => 'Փափուկ մետաքս, մոմեր բարձր ապակե անոթներում, մարգարիտներ և բյուրեղյա ջահ։',
                    'ru' => 'Мягкий шёлк, свечи в высоких колбах, жемчуг и хрустальная люстра.',
                    'en' => 'Soft silk, candles in tall glass, pearls and a crystal chandelier.',
                ],
            ],
            'gender-reveal' => [
                'category' => 'kids', 'date' => '2025-11-02', 'photos' => 2, 'videos' => ['video-1', 'video-2'],
                'title' => ['hy' => 'Gender reveal՝ տղա՞, թե՞ աղջիկ', 'ru' => 'Gender party: мальчик или девочка?', 'en' => 'Gender reveal: boy or girl?'],
                'description' => [
                    'hy' => 'Փուչիկներից կամար, անակնկալ արկղ և գունավոր ծուխ, որը պատասխանում է գլխավոր հարցին։',
                    'ru' => 'Арка из шаров, коробка-сюрприз и цветной дым, который отвечает на главный вопрос.',
                    'en' => 'A balloon arch, a surprise box and coloured smoke that answers the big question.',
                ],
            ],
            'candlelight-banquet' => [
                'category' => 'weddings', 'date' => '2025-10-17', 'photos' => 2,
                'title' => ['hy' => 'Սեղանի ձևավորում մոմերով և վարդերով', 'ru' => 'Сервировка со свечами и белыми розами', 'en' => 'Candlelit tables with white roses'],
                'description' => [
                    'hy' => 'Ոսկեգույն ափսեներ, բյուրեղապակի, սպիտակ վարդեր և մոմեր յուրաքանչյուր սեղանին։',
                    'ru' => 'Золотые тарелки, хрусталь, белые розы и свечи на каждом столе.',
                    'en' => 'Gold charger plates, crystal, white roses and candles on every table.',
                ],
            ],
            'baby-welcome-home' => [
                'category' => 'kids', 'date' => '2025-10-14', 'photos' => 3,
                'title' => ['hy' => 'Փոքրիկի դիմավորում տանը', 'ru' => 'Встреча малыша дома', 'en' => 'Welcome home, baby'],
                'description' => [
                    'hy' => 'Փուչիկներով մանկական սենյակ, փափուկ արջուկներ և կամար՝ փոքրիկի առաջին դիմավորման համար։',
                    'ru' => 'Детская комната в шарах, плюшевые мишки и арка для первой встречи с малышом.',
                    'en' => 'A nursery full of balloons, teddy bears and an arch for the baby\'s homecoming.',
                ],
            ],
            'flowers-surprise' => [
                'category' => 'engagements', 'date' => '2025-09-29', 'photos' => 2, 'videos' => ['video-1', 'video-2'],
                'title' => ['hy' => 'Առաջարկություն և անակնկալներ', 'ru' => 'Предложение и сюрпризы', 'en' => 'Proposals and surprises'],
                'description' => [
                    'hy' => 'Ծաղիկներից սիրտ լեռների վրա, սառը հրավառություն և վարդերով լի մեքենա։',
                    'ru' => 'Сердце из цветов над горами, холодные фонтаны и машина, полная роз.',
                    'en' => 'A heart of flowers above the mountains, cold sparklers and a car full of roses.',
                ],
            ],
        ];
    }
}
