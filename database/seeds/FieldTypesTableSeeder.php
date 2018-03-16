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
use App\Utils\Utils;
use Illuminate\Support\Str;

class FieldTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $platform = DB::getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        
        Schema::table('field_types', function (Blueprint $table) {
            $table->unsignedInteger('id')->change();
        });
        
        Schema::table('fields', function (Blueprint $table) {
            $table->unsignedInteger('id')->change();
        });
        
        // add title global field type and field
        factory(FieldType::class)->create([
            'id' => Database::FieldType_GlobalAttribute_Text_Id,
            'name' => AttributeFieldType::Text,
            'category' => FieldTypeCategory::GlobalAttribute
        ]);
        factory(Field::class)->create([
            'id' => Database::Field_GlobalAttribute_Title_Id,
            'title' => 'Created At',
            'field_type_id' => Database::FieldType_GlobalAttribute_Text_Id
        ]);
        
        // add created at global field type and field
        factory(FieldType::class)->create([
            'id' => Database::FieldType_GlobalAttribute_Date_Id,
            'name' => AttributeFieldType::Date,
            'category' => FieldTypeCategory::GlobalAttribute
        ]);
        factory(Field::class)->create([
            'id' => Database::Field_GlobalAttribute_CreatedAt_Id,
            'title' => 'Created At',
            'field_type_id' => Database::FieldType_GlobalAttribute_Date_Id
        ]);
        
        // add tag relation field type and field
        factory(FieldType::class)->create([
            'id' => Database::FieldType_Relation_Tag_Id,
            'name' => 'Tag',
            'category' => FieldTypeCategory::Relation
        ]);
        factory(Field::class)->create([
            'id' => Database::Field_Relation_Tag_Id,
            'title' => 'Tag',
            'field_type_id' => Database::FieldType_Relation_Tag_Id
        ]);
        
        
        Schema::table('field_types', function (Blueprint $table) {
            $table->increments('id')->change();
        });
        
        Schema::table('fields', function (Blueprint $table) {
            $table->increments('id')->change();
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $attributeFieldTypes = AttributeFieldType::getAll();
        foreach($attributeFieldTypes as $attributeFieldType) {
            factory(FieldType::class)->create([
                'name' => $attributeFieldType,
                'category' => FieldTypeCategory::Attribute
            ]);
        }
        
        $elementTypes = ElementType::getAll();
        foreach($elementTypes as $elementType) {
            $fieldType = factory(FieldType::class)->create([
                'name' => Str::studly($elementType),
                'category' => FieldTypeCategory::SirTrevor
            ]);
            
            factory(Field::class)->create([
                'title' => Str::studly($elementType),
                'field_type_id' => $fieldType->id
            ]);
        }
    }
}
