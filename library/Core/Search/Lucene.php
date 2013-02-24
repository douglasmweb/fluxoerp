<?php
class Core_Search_Lucene extends Zend_Search_Lucene
{
    // Apagando um documento antes de adicioná-lo sobrescrevendo o método addDocument do Zend_Search_Lucene
    public function addDocument(Zend_Search_Lucene_Document $document)
    {
        // Obtém docRef único do documento
        $docRef = $document->docRef;

        $term = new Zend_Search_Lucene_Index_Term($docRef, 'docRef');
        $query = new Zend_Search_Lucene_Search_Query_Term($term);
        // Encontra o documento
        $results = $this->find($query);



        // conta o número de arrays
        if (count($results) > 0)
        {
            foreach($results as $result)
            {
                // Apaga documentos correntes do índice
                $this->delete($result->id);
            }
        }
        return parent::addDocument($document);

    }
    
    public function getDocumentByKey($class, $id){
        $term = new Zend_Search_Lucene_Index_Term($class.':'.$id, 'docRef');
        $query = new Zend_Search_Lucene_Search_Query_Term($term);
        $resul = $this->find($query);
        if(!empty($resul)){
            $queryhit = $resul[0];
            return $queryhit->getDocument();
        } else {
            return false;
        }
    }
    
    // Sobreescrvendo o método create e open para que tudo funcione
    /**
     * Segundo o livro o método estático Zend_Searc_Lucene::open() cria uma instância de Zend_Search_Lucene
     * e não de Core_Search_Lucene. então o addDocument, não funcionára como esperado sem antes sobreescrever os métodos
     */
    public static function create($directory)
    {
        return new Zend_Search_Lucene_Proxy(new Core_Search_Lucene($directory,true));
    }
    
    public static function open($directory)
    {
        return new Zend_Search_Lucene_Proxy(new Core_Search_Lucene($directory,false));
    }
}
