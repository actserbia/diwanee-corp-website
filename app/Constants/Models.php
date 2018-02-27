<?php
  namespace App\Constants;

  final class Models {
      use Constants;

      const AttributeType_Text = 'text';
      const AttributeType_Number = 'number';
      const AttributeType_Date = 'date';
      const AttributeType_Enum = 'enum';
      const AttributeType_Email = 'email';
      const AttributeType_Password = 'password';
      const AttributeType_Checkbox = 'checkbox';
      const AttributeType_CheckboxList = 'checkbox_list';

      const FormFieldType_Input = 'form_input';
      const FormFieldType_Date = 'form_date';
      const FormFieldType_Select = 'form_select';
      const FormFieldType_Relation = 'relation.form_relation';
      const FormFieldType_Relation_TagsParenting = 'relation.form_relation_tags_parenting';
      const FormFieldType_Readonly = 'form_readonly';
      const FormFieldType_CheckboxList = 'form_checkbox_list';

      const FormFieldTypesList = [
          self::AttributeType_Text => self::FormFieldType_Input,
          self::AttributeType_Number => self::FormFieldType_Input,
          self::AttributeType_Email => self::FormFieldType_Input,
          self::AttributeType_Password => self::FormFieldType_Input,
          self::AttributeType_Checkbox => self::FormFieldType_Input,
          self::AttributeType_Date => self::FormFieldType_Date,
          self::AttributeType_Enum => self::FormFieldType_Select,
          self::AttributeType_CheckboxList => self::FormFieldType_CheckboxList
      ];

      const FieldType_Attribute = 'attribute';
      const FieldType_AttributeJson = 'attribute_json';
      const FieldType_AttributeAggregate = 'attribute_aggregate';
      const FieldType_Relation = 'relation';
      const FieldType_RelationJson = 'relation_json';
  }