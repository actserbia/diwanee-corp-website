<?php
  namespace App\Constants;

  final class Database {
      use Constants;

      const NodeType_Page_Id = 1;
      const NodeType_Queue_Id = 2;
      const NodeType_TagData_Id = 3;
      
      const FieldType_GlobalAttribute_Text_Id = 1;
      const FieldType_GlobalAttribute_Date_Id = 2;
      const FieldType_Relation_Tag_Id = 3;
      
      const Field_GlobalAttribute_Title_Id = 1;
      const Field_GlobalAttribute_CreatedAt_Id = 2;
      const Field_Relation_Tag_Id = 3;
  }