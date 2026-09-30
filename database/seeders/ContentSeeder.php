<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Starter content so the site is not empty on first deploy.
 * Portfolio works come from PortfolioSeeder (real photos and videos in public/media/works).
 * Safe to run more than once (skips what already exists).
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            'weddings' => ['hy' => 'Հարսանիքներ', 'ru' => 'Свадьбы', 'en' => 'Weddings'],
            'birthdays' => ['hy' => 'Ծննդյան տոներ', 'ru' => 'Дни рождения', 'en' => 'Birthdays'],
            'kids' => ['hy' => 'Մանկական տոներ', 'ru' => 'Детские праздники', 'en' => 'Kids parties'],
            'engagements' => ['hy' => 'Նշանադրություններ', 'ru' => 'Помолвки', 'en' => 'Engagements'],
            'corporate' => ['hy' => 'Կորպորատիվ', 'ru' => 'Корпоративы', 'en' => 'Corporate'],
        ])->each(fn ($name, $slug) => Category::firstOrCreate(['slug' => $slug], [
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

        $this->call(PortfolioSeeder::class);
    }
}
