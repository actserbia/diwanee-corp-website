<?php
use Illuminate\Database\Seeder;
use App\FieldType;
use App\Field;
use App\Constants\FieldTypeCategory;
use App\Constants\FieldType as FieldTypeList;
use App\Constants\ElementType;

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
                'category' => FieldTypeCategory::Field
            ]);
        }
        
        
        $elementTypes = ElementType::getAll();
        foreach($elementTypes as $elementType) {
            $fieldType = factory(FieldType::class)->create([
                'name' => __('constants.ElementType.' . $elementType),
                'category' => FieldTypeCategory::SirTrevor
            ]);
            
            factory(Field::class)->create([
                'title' => __('constants.ElementType.' . $elementType),
                'field_type_id' => $fieldType->id
            ]);
        }
    }
}
