<?php
/**
 * TipoPessoa Active Record
 * @author  <your-name-here>
 */
class TipoPessoa extends TRecord
{
    const TABLENAME = 'tipo_pessoas';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }


}
