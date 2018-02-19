<?php
use Illuminate\Database\Seeder;
use App\FieldType;
use App\Field;
use App\Constants\FieldTypeCategory;
use App\Constants\FieldType as FieldTypeList;
use App\Constants\ElementType;
use App\Utils\Utils;

class FieldTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fieldTypes = FieldTypeList::getAll();
        foreach($fieldTypes as $fieldType) {
            factory(FieldType::class)->create([
                'name' => $fieldType,
                'category' => FieldTypeCategory::Attribute
            ]);
        }
        
        
        $elementTypes = ElementType::getAll();
        foreach($elementTypes as $elementType) {
            $fieldType = factory(FieldType::class)->create([
                'name' => Utils::getFormattedName($elementType, '_', ' '),
                'category' => FieldTypeCategory::SirTrevor
            ]);
            
            factory(Field::class)->create([
                'title' => Utils::getFormattedName($elementType, '_', ' '),
                'field_type_id' => $fieldType->id
            ]);
        }
    }
}
