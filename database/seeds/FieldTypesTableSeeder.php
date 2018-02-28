<?php
use Illuminate\Database\Seeder;
use App\FieldType;
use App\Field;
use App\Constants\FieldTypeCategory;
use App\Constants\AttributeFieldType;
use App\Constants\ElementType;
use App\Utils\Utils;

class FieldTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $attributeFieldTypes = AttributeFieldType::getAll();
        foreach($attributeFieldTypes as $attributeFieldTypeName) {
            $attributeFieldType = factory(FieldType::class)->create([
                'name' => $attributeFieldTypeName,
                'category' => FieldTypeCategory::GlobalAttribute
            ]);
            
            if($attributeFieldTypeName === AttributeFieldType::Text) {
                factory(Field::class)->create([
                    'title' => 'Title',
                    'field_type_id' => $attributeFieldType->id
                ]);
            }
            
            if($attributeFieldTypeName === AttributeFieldType::Date) {
                factory(Field::class)->create([
                    'title' => 'Created At',
                    'field_type_id' => $attributeFieldType->id
                ]);
            }
        }
        foreach($attributeFieldTypes as $attributeFieldType) {
            factory(FieldType::class)->create([
                'name' => $attributeFieldType,
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
