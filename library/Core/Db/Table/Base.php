<?php
class Core_Db_Table_Base extends Zend_Db_Table_Abstract
{

    public function insert($data)
    {
        $retorno = parent::insert($data);
        $this->_postInsert($data,$retorno);
    }
    
    protected function _postInsert($data,$retorno){
        $index = Core_Search_Lucene::open(Model_SearchIndexer::getIndexDirectory());
        $reg = $this->find($retorno);
        //$reg->save();
        $doc = Model_SearchIndexer::getDocument($reg->getRow(0));
        $index->addDocument($doc);
        $index->commit();
        $index->optimize();
    }

    public function update($data, $where)
    {
        parent::update($data,$where);
        $this->_postUpdate($where);
    }
    
    protected function _postUpdate($where){
        $index = Core_Search_Lucene::open(Model_SearchIndexer::getIndexDirectory());
        $reg = $this->fetchRow($where);
        //$reg->save();
        $doc = Model_SearchIndexer::getDocument($reg);
        $index->addDocument($doc);
        $index->commit();
    }
    

    
    public function delete($where)
    {
        $this->_preDelete($where);
        $delete = parent::delete($where);
        
    }
    
    protected function _preDelete($where)
    {
        $index = Core_Search_Lucene::open(Model_SearchIndexer::getIndexDirectory());
        $reg = $this->fetchRow($where);
        $class = get_class($reg->getTable());
        $lucene = new Zend_Search_Lucene(Model_SearchIndexer::getIndexDirectory());
        $term = new Zend_Search_Lucene_Index_Term($class.':'.$reg->id, 'docRef');
        //$term = new Zend_Search_Lucene_Index_Term('Model_Blog:9', 'docRef');
        $query = new Zend_Search_Lucene_Search_Query_Term($term);
        $resul = $lucene->find($query);
        $queryhit = $resul[0];
        
        $res = $index->delete($queryhit->id);
        $index->commit();
    }
    
    
}
