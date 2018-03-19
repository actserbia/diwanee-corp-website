<?php
  namespace App\Constants;

  final class Database {
      use Constants;

      const NodeType_Page_Id = 1;
      const NodeType_Queue_Id = 2;
      const NodeType_TagData_Id = 3;
      
      const FieldType_Relation_Tag_Id = 1;
      
      const Field_GlobalAttribute_CreatedAt_Id = 1;
      const Field_Attribute_MetaTitle_Id = 2;
      const Field_Attribute_MetaDescription_Id = 3;
      const Field_Relation_Tag_Id = 4;
  }