<?php

namespace App\Models\Node\ClassGenerator;
use App\Constants\Models;


class NMPageClassGenerator extends NodeModelClassGenerator {

    protected function populateData() {

        $this->fillable[] = $this->allAttributesFields[] = 'meta_title';
        $this->fillable[] = $this->allAttributesFields[] = 'meta_description';
        $this->filterFields['meta_title'] = 'true';
        $this->filterFields['meta_description'] = 'true';
        $this->attributeType['meta_title'] = $this->attributeType['meta_description'] = Models::AttributeType_Text;
    }

}