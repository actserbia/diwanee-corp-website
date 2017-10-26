<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use App\Article;
use App\Element;
use App\Tag;
use App\Constants\ElementType;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        $publications = Tag::where('type', '=', 'publication')->get()->toArray();
        $influencers = Tag::where('type', '=', 'influencer')->get()->toArray();
        $brands = Tag::where('type', '=', 'brand')->get()->toArray();
        $categories = Tag::where('type', '=', 'category')->get();
        
        factory(Article::class, 100)->create()->each(function ($article) use ($faker, $publications, $influencers, $brands, $categories) {
            $count = $faker->numberBetween(1, 5);
            for($index = 1; $index <= $count; $index++) {
                $element = factory(Element::class)->make();
                $article->elements()->save($element, ['ordinal_number' => $index]);
            }
            
            $this->addSlider($article, $faker, $index);

            $article->tags()->attach($faker->randomElement($publications)['id']);
            $article->tags()->attach($faker->randomElement($influencers)['id']);
            $article->tags()->attach($faker->randomElement($brands)['id']);
            
            $category = $categories[$faker->numberBetween(0, count($categories) - 1)];
            $article->tags()->attach($category->id);
            if(!empty($category->children)) {
                $this->addSubcategories($article, $category->children->toArray(), $faker);
            }
            $article->save();
        });
    }
    
    private function addSlider($article, $faker, $startIndex) {
        $count = $faker->numberBetween(3, 6);
        for($index = 0; $index < $count; $index++) {
            $element = factory(Element::class, ElementType::SliderImage)->make();
            $article->elements()->save($element, ['ordinal_number' => $startIndex + $index]);
        }
    }

    private function addSubcategories($article, $subcategories, $faker) {
        $count = $faker->numberBetween(1, count($subcategories));
        
        $used = array();
        for($i = 0; $i < $count; $i++) {
            $subcategoryId = $faker->randomElement($subcategories)['id'];
            while (in_array($subcategoryId, $used)) {
                $subcategoryId = $faker->randomElement($subcategories)['id'];
            }
            $article->tags()->attach($subcategoryId);
            array_push($used, $subcategoryId);
        }
    }
}
