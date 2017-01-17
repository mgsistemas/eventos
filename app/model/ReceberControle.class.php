<?php
/**
 * ReceberControle Active Record
 * @author  <your-name-here>
 */
class ReceberControle extends TRecord
{
    const TABLENAME = 'receber_controle';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('receber_id');
        parent::addAttribute('tipo_controle_id');
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('valor_bruto');
        parent::addAttribute('iss');
        parent::addAttribute('inss');
        parent::addAttribute('irrf');
        parent::addAttribute('pis_cofins');
        parent::addAttribute('valor_liquido');
    }



    /**
     * Method set_tipo_controle
     * Sample of usage: $receber->tipo_controle = $object;
     * @param $object Instance of TipoControle
     */
    public function set_tipo_controle(TipoControle $object)
    {
        $this->tipo_controle = $object;
        $this->tipo_controle_id = $object->id;
    }
    
    /**
     * Method get_tipo_controle
     * Sample of usage: $receber->tipo_controle->attribute;
     * @returns TipoControle instance
     */
    public function get_tipo_controle()
    {
        // loads the associated object
        if (empty($this->tipo_controle))
            $this->tipo_controle = new TipoControle($this->tipo_controle_id);
    
        // returns the associated object
        return $this->tipo_controle;
    }


}
