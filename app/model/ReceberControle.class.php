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


}
