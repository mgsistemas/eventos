<?php
/**
 * Documento Active Record
 * @author  <your-name-here>
 */
class Documento extends TRecord
{
    const TABLENAME = 'documentos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('doc_id');
        parent::addAttribute('descricao');
        parent::addAttribute('tipo');
        parent::addAttribute('nomedocumento');
        parent::addAttribute('login');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }


}
