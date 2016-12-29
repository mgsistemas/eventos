<?php
/**
 * PlanoContaMacro Active Record
 * @author  <your-name-here>
 */
class PlanoContaMacro extends TRecord
{
    const TABLENAME = 'plano_conta_macro';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }


}
