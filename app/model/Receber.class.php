<?php
/**
 * Receber Active Record
 * @author  <your-name-here>
 */
class Receber extends TRecord
{
    const TABLENAME = 'receber';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $cliente;
    private $tipo_cobranca;
    private $plano_conta;
    private $empresa;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_lancamento');
        parent::addAttribute('data_emissao');
        parent::addAttribute('cliente_id');
        parent::addAttribute('tipo_cobranca_id');
        parent::addAttribute('plano_conta_id');
        parent::addAttribute('data_credito');
        parent::addAttribute('job_id');
        parent::addAttribute('empresa_id');
        parent::addAttribute('codigo_barras');
        parent::addAttribute('data_pagamento');
        parent::addAttribute('data_vencimento');
        parent::addAttribute('observacao');
        parent::addAttribute('situacao');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }

    
    /**
     * Method set_cliente
     * Sample of usage: $receber->cliente = $object;
     * @param $object Instance of Cliente
     */
    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }
    
    /**
     * Method get_cliente
     * Sample of usage: $receber->cliente->attribute;
     * @returns Cliente instance
     */
    public function get_cliente()
    {
        // loads the associated object
        if (empty($this->cliente))
            $this->cliente = new Cliente($this->cliente_id);
    
        // returns the associated object
        return $this->cliente;
    }
    
    
    /**
     * Method set_tipo_cobranca
     * Sample of usage: $receber->tipo_cobranca = $object;
     * @param $object Instance of TipoCobranca
     */
    public function set_tipo_cobranca(TipoCobranca $object)
    {
        $this->tipo_cobranca = $object;
        $this->tipo_cobranca_id = $object->id;
    }
    
    /**
     * Method get_tipo_cobranca
     * Sample of usage: $receber->tipo_cobranca->attribute;
     * @returns TipoCobranca instance
     */
    public function get_tipo_cobranca()
    {
        // loads the associated object
        if (empty($this->tipo_cobranca))
            $this->tipo_cobranca = new TipoCobranca($this->tipo_cobranca_id);
    
        // returns the associated object
        return $this->tipo_cobranca;
    }
    
    
    /**
     * Method set_plano_conta
     * Sample of usage: $receber->plano_conta = $object;
     * @param $object Instance of PlanoConta
     */
    public function set_plano_conta(PlanoConta $object)
    {
        $this->plano_conta = $object;
        $this->plano_conta_id = $object->id;
    }
    
    /**
     * Method get_plano_conta
     * Sample of usage: $receber->plano_conta->attribute;
     * @returns PlanoConta instance
     */
    public function get_plano_conta()
    {
        // loads the associated object
        if (empty($this->plano_conta))
            $this->plano_conta = new PlanoConta($this->plano_conta_id);
    
        // returns the associated object
        return $this->plano_conta;
    }
    
    
    
    /**
     * Method set_empresa
     * Sample of usage: $receber->empresa = $object;
     * @param $object Instance of Empresa
     */
    public function set_empresa(Empresa $object)
    {
        $this->empresa = $object;
        $this->empresa_id = $object->id;
    }
    

    /**
     * Method get_empresa
     * Sample of usage: $receber->empresa->attribute;
     * @returns Empresa instance
     */
    public function get_empresa()
    {
        // loads the associated object
        if (empty($this->empresa))
            $this->empresa = new Empresa($this->empresa_id);
    
        // returns the associated object
        return $this->empresa;
    }
    


    /**
     * Method set_job
     * Sample of usage: $receber->job = $object;
     * @param $object Instance of Empresa
     */
    public function set_job(Job $object)
    {
        $this->job = $object;
        $this->job_id = $object->id;
    }
    

    /**
     * Method get_job
     * Sample of usage: $receber->job->attribute;
     * @returns Job instance
     */
    public function get_job()
    {
        // loads the associated object
        if (empty($this->job))
            $this->job = new Job($this->job_id);
    
        // returns the associated object
        return $this->job;
    }



    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
    
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
    
        // delete the object itself
        parent::delete($id);
    }


}
