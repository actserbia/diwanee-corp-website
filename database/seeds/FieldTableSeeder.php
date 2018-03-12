<?php
use Illuminate\Database\Seeder;
use App\FieldType;
use App\NodeType;
use App\NodeTypeField;
use App\Field;


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
        $nodeTypeField->multiple = 0;
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
        $nodeTypeField->multiple = 0;
        $nodeTypeField->ordinal_number = 1;

        $nodeTypeField->save();

    }
}
