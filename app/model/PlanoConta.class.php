<?php
/**
 * PlanoConta Active Record
 * @author  <your-name-here>
 */
class PlanoConta extends TRecord
{
    const TABLENAME = 'plano_conta';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $plano_conta_macro;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('plano_conta_macro_id');
        parent::addAttribute('descricao');
        parent::addAttribute('tipo');
        parent::addAttribute('situacao');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }

    
    /**
     * Method set_plano_conta_macro
     * Sample of usage: $plano_conta->plano_conta_macro = $object;
     * @param $object Instance of PlanoContaMacro
     */
    public function set_plano_conta_macro(PlanoContaMacro $object)
    {
        $this->plano_conta_macro = $object;
        $this->plano_conta_macro_id = $object->id;
    }
    
    /**
     * Method get_plano_conta_macro
     * Sample of usage: $plano_conta->plano_conta_macro->attribute;
     * @returns PlanoContaMacro instance
     */
    public function get_plano_conta_macro()
    {
        // loads the associated object
        if (empty($this->plano_conta_macro))
            $this->plano_conta_macro = new PlanoContaMacro($this->plano_conta_macro_id);
    
        // returns the associated object
        return $this->plano_conta_macro;
    }
    


}
