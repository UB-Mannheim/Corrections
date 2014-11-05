<?php
class CorrectionsCorrection extends Omeka_Record_AbstractRecord
{
    public $added;
    public $reviewed;
    public $item_id;
    public $comment;
    public $status;
    public $owner_id;
    
    public function _initializeMixins()
    {
        $this->_mixins[] = new Mixin_Timestamp($this, 'added', null);
        $this->_mixins[] = new Mixin_ElementText($this);
        $this->_mixins[] = new Mixin_Search($this);
        $this->_mixins[] = new Mixin_Owner($this);
    }
    
    public function getItem()
    {
        return $this->getDb()->getTable('Item')->find($this->item_id);
    }
    
    protected function afterSave($args)
    {
        $mail = new Zend_Mail('UTF-8');
        $mail->addHeader('X-Mailer', 'PHP/' . phpversion());
        $mail->setFrom(get_option('administrator_email'), get_option('site_title'));
        $mail->addTo(get_option('corrections_email'));
        $subject = __("A correction has been submitted to %s", get_option('site_title'));
        $body = "<p>" . __("Please see %s to evaluate the correction.", "<a href='WEB_ROOT. '/admin/corrections'>this</a>" ) . "</p>";
        $mail->setSubject($subject);
        $mail->setBodyHtml($body);
        try {
            $mail->send();
        } catch(Exception $e) {
            _log($e);
        }
    }
    
    protected function beforeSave($args)
    {
        if ($args['post']) {
            $post = $args['post'];
            $this->beforeSaveElements($post);
        }
        
        if (!$this->status) {
            $this->status = 'submitted';
        }
    }
}