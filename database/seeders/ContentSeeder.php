<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\Work;
use Illuminate\Database\Seeder;

/**
 * Starter content so the site is not empty on first deploy.
 * Demo works use stock photos: replace them with real ones from the admin panel.
 * Safe to run more than once (skips what already exists).
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            'weddings' => ['hy' => 'Հարսանիքներ', 'ru' => 'Свадьбы', 'en' => 'Weddings'],
            'birthdays' => ['hy' => 'Ծննդյան տոներ', 'ru' => 'Дни рождения', 'en' => 'Birthdays'],
            'kids' => ['hy' => 'Մանկական տոներ', 'ru' => 'Детские праздники', 'en' => 'Kids parties'],
            'engagements' => ['hy' => 'Նշանադրություններ', 'ru' => 'Помолвки', 'en' => 'Engagements'],
            'corporate' => ['hy' => 'Կորպորատիվ', 'ru' => 'Корпоративы', 'en' => 'Corporate'],
        ])->map(fn ($name, $slug) => Category::firstOrCreate(['slug' => $slug], [
            'name' => $name,
            'position' => array_search($slug, ['weddings', 'birthdays', 'kids', 'engagements', 'corporate']),
        ]));

        if (! Service::exists()) {
            $services = [
                ['heart', ['hy' => 'Հարսանիքներ', 'ru' => 'Свадьбы', 'en' => 'Weddings'], ['hy' => 'Ամբողջական կազմակերպում՝ գաղափարից և ձևավորումից մինչև ծրագիր և հաղորդավար։', 'ru' => 'Организация под ключ: от концепции и декора до программы и ведущего.', 'en' => 'Turnkey organization: from concept and decor to program and host.']],
                ['sparkles', ['hy' => 'Նշանադրություն և խնամախոսություն', 'ru' => 'Помолвки и сватовство', 'en' => 'Engagements'], ['hy' => 'Հուզիչ պահեր ազգային ավանդույթներով և ժամանակակից ոճով։', 'ru' => 'Трогательные моменты с национальными традициями и современным стилем.', 'en' => 'Touching moments with national traditions and modern style.']],
                ['cake', ['hy' => 'Ծննդյան տոներ', 'ru' => 'Дни рождения', 'en' => 'Birthdays'], ['hy' => 'Թեմատիկ երեկույթներ, հոբելյաններ և անակնկալներ ցանկացած տարիքի համար։', 'ru' => 'Тематические вечеринки, юбилеи и сюрпризы для любого возраста.', 'en' => 'Themed parties, anniversaries and surprises for any age.']],
                ['gift', ['hy' => 'Մանկական տոներ', 'ru' => 'Детские праздники', 'en' => 'Kids parties'], ['hy' => 'Անիմատորներ, շոու ծրագրեր, փուչիկներ և քաղցր սեղան։', 'ru' => 'Аниматоры, шоу-программы, шары и candy bar.', 'en' => 'Animators, shows, balloons and candy bar.']],
                ['briefcase', ['hy' => 'Կորպորատիվ միջոցառումներ', 'ru' => 'Корпоративы', 'en' => 'Corporate events'], ['hy' => 'Տոնական երեկոներ, թիմային միջոցառումներ և շնորհանդեսներ։', 'ru' => 'Праздничные вечера, тимбилдинги и презентации.', 'en' => 'Gala evenings, team building and presentations.']],
                ['camera', ['hy' => 'Ձևավորում, լուսանկար և տեսանկարահանում', 'ru' => 'Декор, фото и видео', 'en' => 'Decor, photo & video'], ['hy' => 'Ծաղկային դիզայն, լուսավորություն, ֆոտոզոնաներ և պրոֆեսիոնալ նկարահանում։', 'ru' => 'Флористика, свет, фотозоны и профессиональная съёмка.', 'en' => 'Floristics, lighting, photo zones and professional shooting.']],
            ];
            foreach ($services as $i => [$icon, $title, $description]) {
                Service::create(compact('icon', 'title', 'description') + ['position' => $i]);
            }
        }

        if (Work::exists()) {
            return;
        }

        $img = fn (string $id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=1600&q=75";

        $works = [
            ['weddings', ['hy' => 'Ոսկե հարսանիք այգում', 'ru' => 'Золотая свадьба в саду', 'en' => 'Golden garden wedding'], true, ['1519741497674-611481863552', '1465495976277-4387d4b0b4c6', '1511285560929-80b456fea0bc', '1519225421980-715cb0215aed']],
            ['birthdays', ['hy' => 'Ծննդյան երեկո «Գլամուր»', 'ru' => 'День рождения «Гламур»', 'en' => 'Glamour birthday night'], true, ['1530103862676-de8c9debad1d', '1464349095431-e9a21285b5f3', '1513151233558-d860c5398176']],
            ['engagements', ['hy' => 'Նշանադրություն վարդագույն երանգներով', 'ru' => 'Помолвка в розовых тонах', 'en' => 'Blush engagement'], false, ['1522673607200-164d1b6ce486', '1469371670807-013ccf25f16a', '1511795409834-ef04bbd61622']],
            ['kids', ['hy' => 'Մանկական տոն «Կախարդական աշխարհ»', 'ru' => 'Детский праздник «Волшебный мир»', 'en' => 'Magic world kids party'], false, ['1530103043960-ef38714abb15', '1558636508-e0db3814bd1d', '1527529482837-4698179dc6ce']],
            ['corporate', ['hy' => 'Ամանորյա կորպորատիվ', 'ru' => 'Новогодний корпоратив', 'en' => 'New Year corporate party'], false, ['1492684223066-81342ee5ff30', '1505236858219-8359eb29e329', '1540575467063-178a50c2df87']],
            ['weddings', ['hy' => 'Սպիտակ և կանաչ հարսանիք', 'ru' => 'Бело-зелёная свадьба', 'en' => 'White & greenery wedding'], false, ['1478146896981-b80fe463b330', '1464366400600-7168b8af9bc3', '1507504031003-b417219a0fde']],
        ];

        foreach ($works as $i => [$category, $title, $featured, $photos]) {
            $work = Work::create([
                'category_id' => $categories[$category]->id,
                'title' => $title,
                'description' => [
                    'hy' => 'Օրինակելի աշխատանք։ Փոխարինեք այն իրական լուսանկարներով ադմին վահանակից։',
                    'ru' => 'Демонстрационная работа. Замените её настоящими фото и видео через админ-панель.',
                    'en' => 'Demo work. Replace it with real photos and videos from the admin panel.',
                ],
                'location' => 'Yerevan',
                'event_date' => now()->subMonths($i * 2 + 1)->toDateString(),
                'is_featured' => $featured,
            ]);

            foreach ($photos as $position => $photo) {
                $work->media()->create(['type' => 'image', 'url' => $img($photo), 'position' => $position]);
            }
        }
    }
}
