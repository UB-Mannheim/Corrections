<?php
class Corrections_IndexController extends Omeka_Controller_AbstractActionController
{
    
    public function init()
    {
        $this->_helper->db->setDefaultModelName('CorrectionsCorrection');
    }
    
    public function addAction()
    {
        $this->view->addHelperPath(CORRECTIONS_DIR . '/helpers', 'Corrections_View_Helper_');
        $itemId = $this->getParam('item_id');
        $item = $this->_helper->db->getTable('Item')->find($itemId);
        $this->view->item = $item;
        
        $this->view->elements = $this->getElements();
        parent::addAction();
    }

    
    public function correctAction()
    {
        //$itemId = $this->getParam('item_id');
        $correction = $this->_helper->db->getTable('CorrectionsCorrection')->find();
        $item = $correction->getItem();
        $item->setReplaceElementTexts(false);
        $view = get_view();
        $elTexts = $view->allElementTexts($correction, array('return_type' => 'array'));
        //the array just gives the text, not the array that goes into setting element texts
        $elTexts = $this->reformatElTexts($elTexts);
        print_r($elTexts);
        $item->addElementTextsByArray($elTexts);
        $item->save();
    }
    
    protected function getElements()
    {
        $elements = array();
        $elTable = $this->_helper->db->getTable('Element');
        $correctableElements = json_decode(get_option('corrections_elements'), true);
        foreach ($correctableElements as $elSet=>$els) {
            foreach ($els as $elName) {
                $el = $elTable->findByElementSetNameAndElementName($elSet, $elName);
                $elements[$el->id] = $el;
            }
        }
        return $elements;
    }
    
    protected function reformatElTexts($elTexts)
    {
        foreach ($elTexts as $elSet => $elements) {
            foreach ($elements as $element => $texts) {
                foreach ($texts as $index => $text) {
                    $elTexts[$elSet][$element][$index] = array(
                            'text' => $text,
                            'html' => false
                            );
                }
            }
        }
        return $elTexts;
    }
    
    protected function _redirectAfterAdd($record)
    {
       // $this->_helper->redirector('browse');
       $this->_helper->redirector->gotoUrl('items/show/2906');
    }    
}