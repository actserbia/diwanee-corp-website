<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\FieldType;
use App\Field;
use App\Constants\FieldTypeCategory;
use App\Constants\ElementType;
use App\Constants\Database;
use App\Utils\Utils;

class FieldsTableSeeder extends Seeder
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
        
        Schema::table('fields', function (Blueprint $table) {
            $table->unsignedInteger('id')->change();
        });
        
        $dateFieldType = FieldType::where('name', '=', 'Date')
            ->where('category', '=', FieldTypeCategory::GlobalAttribute)
            ->first();
        factory(Field::class)->create([
            'id' => Database::Field_GlobalAttribute_CreatedAt_Id,
            'title' => 'Created At',
            'field_type_id' => $dateFieldType->id
        ]);    
        
        $textFieldType = FieldType::where('name', '=', 'Text')
            ->where('category', '=', FieldTypeCategory::Attribute)
            ->first();        
        factory(Field::class)->create([
            'id' => Database::Field_Attribute_MetaTitle_Id,
            'title' => 'Meta Title',
            'field_type_id' => $textFieldType->id
        ]);
        factory(Field::class)->create([
            'id' => Database::Field_Attribute_MetaDescription_Id,
            'title' => 'Meta Description',
            'field_type_id' => $textFieldType->id
        ]);
        
        $relationTagFieldType = FieldType::where('name', '=', 'Tag')
            ->where('category', '=', FieldTypeCategory::Relation)
            ->first();
        factory(Field::class)->create([
            'id' => Database::Field_Relation_Tag_Id,
            'title' => 'Tag',
            'field_type_id' => $relationTagFieldType->id
        ]);
        
        Schema::table('fields', function (Blueprint $table) {
            $table->increments('id')->change();
        });
        
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $fieldType = FieldType::where('name', '=', 'Text')
            ->where('category', '=', FieldTypeCategory::GlobalAttribute)
            ->first();
        factory(Field::class)->create([
            'title' => 'Title',
            'field_type_id' => $fieldType->id
        ]);
        
        $elementTypes = ElementType::getAll();
        foreach($elementTypes as $elementType) {
            //$fieldType = FieldType::where('name', '=', Str::studly($elementType))
            //    ->where('category', '=', FieldTypeCategory::SirTrevor)
            //    ->first();
            
            $fieldType = FieldType::where('name', '=', 'Sir Trevor')
                ->where('category', '=', FieldTypeCategory::SirTrevor)
                ->first();
            
            factory(Field::class)->create([
                'title' => Utils::getFormattedName(Utils::getFormattedDBName($elementType), '_', ' '),
                'field_type_id' => $fieldType->id
            ]);
        }
    }
}
