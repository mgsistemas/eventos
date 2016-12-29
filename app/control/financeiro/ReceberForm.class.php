<?php
/**
 * ReceberForm
 * @author Marcelo Gomes
 * @package app/control/financeiro
 */
class ReceberForm extends TPage
{
    
    private $form;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Contas a Receber');
        $this->form->appendPage('Dados da Conta a Receber');
        
        $this->form->addAction('Salvar',new TAction(array($this, 'onSave')),'fa:save blue');
        $this->form->addAction('Cancelar',new TAction(array('ReceberList', 'onReload')),'fa:table red');
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        parent::add($vbox);
        
    }
    
    
    public function onEdit ($param)
    {
    }
    
    public function onSave ($param)
    {
    }

}
