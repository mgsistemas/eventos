<?php
/**
 * Servico Active Record
 * @author  <your-name-here>
 */
class Servico extends TRecord
{
    const TABLENAME = 'servico';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $segmento;
    private $grupo_servico;
    private $grupo_trabalho;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('grupo_servico_id');
        parent::addAttribute('grupo_trabalho_id');
        parent::addAttribute('segmento_id');
        parent::addAttribute('imagem');
        parent::addAttribute('situacao');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }

    
    /**
     * Method set_segmento
     * Sample of usage: $servico->segmento = $object;
     * @param $object Instance of Segmento
     */
    public function set_segmento(Segmento $object)
    {
        $this->segmento = $object;
        $this->segmento_id = $object->id;
    }
    
    /**
     * Method get_segmento
     * Sample of usage: $servico->segmento->attribute;
     * @returns Segmento instance
     */
    public function get_segmento()
    {
        // loads the associated object
        if (empty($this->segmento))
            $this->segmento = new Segmento($this->segmento_id);
    
        // returns the associated object
        return $this->segmento;
    }
    
    
    /**
     * Method set_grupo_servico
     * Sample of usage: $servico->grupo_servico = $object;
     * @param $object Instance of GrupoServico
     */
    public function set_grupo_servico(GrupoServico $object)
    {
        $this->grupo_servico = $object;
        $this->grupo_servico_id = $object->id;
    }
    
    /**
     * Method get_grupo_servico
     * Sample of usage: $servico->grupo_servico->attribute;
     * @returns GrupoServico instance
     */
    public function get_grupo_servico()
    {
        // loads the associated object
        if (empty($this->grupo_servico))
            $this->grupo_servico = new GrupoServico($this->grupo_servico_id);
    
        // returns the associated object
        return $this->grupo_servico;
    }
    
    
    /**
     * Method set_grupo_trabalho
     * Sample of usage: $servico->grupo_trabalho = $object;
     * @param $object Instance of GrupoTrabalho
     */
    public function set_grupo_trabalho(GrupoTrabalho $object)
    {
        $this->grupo_trabalho = $object;
        $this->grupo_trabalho_id = $object->id;
    }
    
    /**
     * Method get_grupo_trabalho
     * Sample of usage: $servico->grupo_trabalho->attribute;
     * @returns GrupoTrabalho instance
     */
    public function get_grupo_trabalho()
    {
        // loads the associated object
        if (empty($this->grupo_trabalho))
            $this->grupo_trabalho = new GrupoTrabalho($this->grupo_trabalho_id);
    
        // returns the associated object
        return $this->grupo_trabalho;
    }
    


}
