<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\FieldType;
use App\Field;
use App\Constants\FieldTypeCategory;
use App\Constants\AttributeFieldType;
use App\Constants\ElementType;
use App\Constants\Database;
use Illuminate\Support\Str;

class FieldTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $attributeFieldTypes = AttributeFieldType::getAll();
        foreach($attributeFieldTypes as $attributeFieldType) {
            factory(FieldType::class)->create([
                'name' => $attributeFieldType,
                'category' => FieldTypeCategory::GlobalAttribute
            ]);
            
            factory(FieldType::class)->create([
                'name' => $attributeFieldType,
                'category' => FieldTypeCategory::Attribute
            ]);
        }
        
        factory(FieldType::class)->create([
            'name' => 'Tag',
            'category' => FieldTypeCategory::Relation
        ]);
        
        //$elementTypes = ElementType::getAll();
        //foreach($elementTypes as $elementType) {
        //    factory(FieldType::class)->create([
        //        'name' => Str::studly($elementType),
        //        'category' => FieldTypeCategory::SirTrevor
        //    ]);
        //}
        factory(FieldType::class)->create([
            'name' => 'Sir Trevor',
            'category' => FieldTypeCategory::SirTrevor
        ]);
    }
}
