<?php
/**
 * MotivoBloqueio Active Record
 * @author  <your-name-here>
 */
class MotivoBloqueio extends TRecord
{
    const TABLENAME = 'motivo_bloqueio';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $tipo_bloqueio;
    
    use SystemChangeLogTrait;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_bloqueio_id');
        parent::addAttribute('origem_id');
        parent::addAttribute('motivo');
        parent::addAttribute('tipo_movimento');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }

    
    /**
     * Method set_tipo_bloqueio
     * Sample of usage: $motivo_bloqueio->tipo_bloqueio = $object;
     * @param $object Instance of TipoBloqueio
     */
    public function set_tipo_bloqueio(TipoBloqueio $object)
    {
        $this->tipo_bloqueio = $object;
        $this->tipo_bloqueio_id = $object->id;
    }
    
    /**
     * Method get_tipo_bloqueio
     * Sample of usage: $motivo_bloqueio->tipo_bloqueio->attribute;
     * @returns TipoBloqueio instance
     */
    public function get_tipo_bloqueio()
    {
        // loads the associated object
        if (empty($this->tipo_bloqueio))
            $this->tipo_bloqueio = new TipoBloqueio($this->tipo_bloqueio_id);
    
        // returns the associated object
        return $this->tipo_bloqueio;
    }
    


}
