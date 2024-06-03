<?php

class Corrections_View_Helper_ElementInput extends Omeka_View_Helper_ElementInput
{
    protected function _getControlsComponent($inputNameStemId) {
        return '';
    }
    
    protected function _getHtmlCheckboxComponent($inputNameStem, $inputNameStemId, $isHtml) {
        return '';
    }
}