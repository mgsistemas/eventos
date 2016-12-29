<?php
/**
 * TipoBloqueio Active Record
 * @author  <your-name-here>
 */
class TipoBloqueio extends TRecord
{
    const TABLENAME = 'tipo_bloqueio';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
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
