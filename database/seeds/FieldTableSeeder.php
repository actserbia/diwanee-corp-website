<?php
use Illuminate\Database\Seeder;
use App\FieldType;
use App\NodeType;
use App\NodeTypeField;
use App\Field;
use App\Constants\FieldTypeCategory;

class FieldTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $textType = FieldType::where('name', 'like', 'Text')
            ->where('category', 'like', 'attribute')
            ->first();

        $pageType = NodeType::where('name', 'like', 'Page')->first();

        $field = Field::where('title', 'like', 'Meta Title')->first();

        if($field != null) {
            $fieldId = $field->id;
        } else {
            factory(Field::class, 1)->create([
                'title' => 'Meta Title',
                'field_type_id' => $textType->id
            ]);
            $fieldId = DB::getPdo()->lastInsertId();
        }

        $nodeTypeField = new NodeTypeField;

        $nodeTypeField->node_type_id = $pageType->id;
        $nodeTypeField->field_id = $fieldId;
        $nodeTypeField->active = 1;
        $nodeTypeField->required = 0;
        $nodeTypeField->ordinal_number = 0;

        $nodeTypeField->save();


        $field = Field::where('title', 'like', 'Meta Description')->first();

        if($field != null) {
            $fieldId = $field->id;
        } else {
            factory(Field::class, 1)->create([
                'title' => 'Meta Description',
                'field_type_id' => $textType->id
            ]);
            $fieldId = DB::getPdo()->lastInsertId();
        }

        $nodeTypeField = new NodeTypeField;

        $nodeTypeField->node_type_id = $pageType->id;
        $nodeTypeField->field_id = $fieldId;
        $nodeTypeField->active = 1;
        $nodeTypeField->required = 0;
        $nodeTypeField->ordinal_number = 1;

        $nodeTypeField->save();
        
        $this->addTagDataNodeType();
    }
    
    private function addTagDataNodeType() {
        $tagDataNodeType = NodeType::where('name', '=', 'Tag Data')->first();
        
        $field = Field::join('field_types', 'fields.field_type_id', '=', 'field_types.id')
            ->where('field_types.name', '=', 'Tag')
            ->where('field_types.category', '=', FieldTypeCategory::Relation)
            ->select('fields.*')
            ->first();
        
        $nodeTypeField = new NodeTypeField;

        $nodeTypeField->node_type_id = $tagDataNodeType->id;
        $nodeTypeField->field_id = $field->id;
        $nodeTypeField->active = 1;
        $nodeTypeField->required = 1;
        $nodeTypeField->multiple_list = [false, false];
        $nodeTypeField->ordinal_number = 0;

        $nodeTypeField->save();
    }
}
