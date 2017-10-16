<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use App\Article;
use App\Element;
use App\Tag;

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
        $types = Tag::where('type', '=', 'type')->get()->toArray();
        $brands = Tag::where('type', '=', 'brand')->get()->toArray();
        $categories = Tag::where('type', '=', 'category')->get();
        
        factory(Article::class, 100)->create()->each(function ($article) use ($faker, $publications, $types, $brands, $categories) {
            $count = $faker->numberBetween(1, 5);
            for($index = 1; $index <= $count; $index++) {
                $article->elements()->save(factory(Element::class)->make(), ['ordinal_number' => $index]);
            }
            
            $article->tags()->attach($faker->randomElement($publications)['id']);
            $article->tags()->attach($faker->randomElement($types)['id']);
            $article->tags()->attach($faker->randomElement($brands)['id']);
            
            $category = $categories[$faker->numberBetween(0, count($categories) - 1)];
            $article->tags()->attach($category->id);
            if(!empty($category->children)) {
                $this->addSubcategories($article, $category->children->toArray(), $faker);
            }
            $article->save();
        });
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
