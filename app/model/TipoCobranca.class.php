<?php
/**
 * TipoCobranca Active Record
 * @author  <your-name-here>
 */
class TipoCobranca extends TRecord
{
    const TABLENAME = 'tipo_cobranca';
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
