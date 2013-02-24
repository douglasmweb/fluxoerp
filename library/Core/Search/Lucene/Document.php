<?php
class Core_Search_Lucene_Document extends Zend_Search_Lucene_Document
{
    public function __construct($class, $key, $title, $contents, $summary, $createdBy, $dateCreated, $dateUpdated, $keywords = array())
    {
        $this->addField(Zend_Search_Lucene_Field::Keyword('docRef', "$class:$key"));
        $this->addField(Zend_Search_Lucene_Field::UnIndexed('class', $class));
        $this->addField(Zend_Search_Lucene_Field::UnIndexed('key', $key));
        $this->addField(Zend_Search_Lucene_Field::Text('title', $title, 'utf-8'));
        $this->addField(Zend_Search_Lucene_Field::UnStored('contents', $contents, 'utf-8'));
        $this->addField(Zend_Search_Lucene_Field::UnIndexed('summary', $summary, 'utf-8'));
        $this->addField(Zend_Search_Lucene_Field::Keyword('createdBy', $createdBy, 'utf-8'));
        $this->addField(Zend_Search_Lucene_Field::Keyword('dateCreated', $dateCreated));
        $this->addField(Zend_Search_Lucene_Field::Keyword('dateUpdated', $dateUpdated));

        if (!is_array($keywords))
        {
            $keywords = explode(' ', $keywords);
        }
        foreach ($keywords as $name => $value)
        {
            if (!empty($name) && !empty($value))
            {
                $this->addField(Zend_Search_Lucene_Field::Keyword($name, $value));
            }
        }
    }
}
