<?php
use Illuminate\Database\Seeder;
use App\FieldType;
use App\NodeType;
use App\NodeTypeField;
use App\Field;
use App\Constants\Database;

class NodeTypeFieldTableSeeder extends Seeder
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

        $pageType = NodeType::find(Database::NodeType_Page_Id);

        $nodeTypeField = new NodeTypeField;

        $nodeTypeField->node_type_id = $pageType->id;
        $nodeTypeField->field_id = Database::Field_Attribute_MetaTitle_Id;
        $nodeTypeField->active = 1;
        $nodeTypeField->required = 0;
        $nodeTypeField->ordinal_number = 0;

        $nodeTypeField->save();

        
        $nodeTypeField = new NodeTypeField;

        $nodeTypeField->node_type_id = $pageType->id;
        $nodeTypeField->field_id = Database::Field_Attribute_MetaDescription_Id;
        $nodeTypeField->active = 1;
        $nodeTypeField->required = 0;
        $nodeTypeField->ordinal_number = 1;

        $nodeTypeField->save();
        
        $this->addTagDataNodeType();
    }
    
    private function addTagDataNodeType() {
        $tagDataNodeType = NodeType::find(Database::NodeType_TagData_Id);
        
        $field = Field::find(Database::Field_Relation_Tag_Id);
        
        $nodeTypeField = new NodeTypeField;

        $additionalSettings = [
            'additional_settings' => [
                'multiple' => [
                    'hierarchy' => '0',
                    'value' => '0'
                ],
                'render_type' => 'input'
            ]
        ];

        $nodeTypeField->node_type_id = $tagDataNodeType->id;
        $nodeTypeField->field_id = $field->id;
        $nodeTypeField->active = 1;
        $nodeTypeField->required = 1;
        $nodeTypeField->additional_settings = $additionalSettings;
        $nodeTypeField->ordinal_number = 0;

        $nodeTypeField->save();
    }
}
