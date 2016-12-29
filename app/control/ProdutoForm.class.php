<?php
class ProdutoForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        //$this->setDatabase('dbmysql');              // defines the database
        //$this->setActiveRecord('ProdutoModel');     // defines the active record
        
        
        $painel = new TPanelGroup('Cadastro de Produtos');
        
        $this->form = new TForm('form_produto');
        $this->form->class = 'tform';
        
        //Instanciamento de Campos 
        $id = new THidden('id');  
        $codigo = new TEntry('codigo');
        $descricao = new TEntry('descricao');
        $ean = new TEntry('ean');
        //$unidade_id = new TDBCombo('unidade_id','dbmysql','UnidadeModel','uni_id','uni_abrev');
        $valor = new TEntry('valor');
        $obs = new TText('obs');
        
        $descricao->setMaxLength(60);      
        /*>>>>>>>       MÁSCARAS      <<<<<<<<*/
        $valor->setNumericMask(3,',','.');
        
        /*>>>>>>>       HINTS      <<<<<<<<*/
        $descricao->setTip('Insira a descrição do produto..');
        $notebookPrincipal = new BootstrapNotebookWrapper( new TNotebook('100%',400) );
        $tableGerais    = new TTable;
        $tableGerais->style = 'width: 100%';
        
        $notebookPrincipal->appendPage('Dados Gerais',      $tableGerais);    
        $this->form->add($notebookPrincipal);    
        
        //EXIBIÇÃO DE CAMPOS 
        $div = new TVBox;
        $div->class = 'form-group col-lg-1';
        $div->add(new TLabel('Código'));
        $codigo->class = 'tfield form-control';
        $div->add($codigo);
        $codigo->setSize('100%');
        $codigo->setEditable(FALSE);       
        $tableGerais->add($div);
        
        $div = new TVBox;
        $div->class = 'form-group col-lg-5';
        $div->add(new TLabel('Descrição'));
        $descricao->class = 'tfield form-control';
        $div->add($descricao);
        $descricao->setSize('100%');
        $tableGerais->add($div);
        
        $div = new TVBox;
        $div->class = 'form-group col-lg-2';
        $div->add(new TLabel('Preço Venda'));
        $valor->class = 'tfield form-control';
        $div->add($valor);
        $valor->setSize('100%');
        $tableGerais->add($div);
      
        
        $div = new TVBox;
        $div->class = 'form-group col-lg-3';
        $div->add(new TLabel('EAN(Cód. Barras)'));
        $ean->class = 'tfield form-control';
        $div->add($ean);
        $ean->setSize('100%');       
        $tableGerais->add($div);
        
        
/*
        $div = new TVBox;
        $div->class = 'form-group col-lg-2';
        $div->add(new TLabel('Unidade'));
        $unidade_id->class = 'tfield form-control';
        $unidade_id->setSize('100%');
        $div->add($unidade_id);
        $tableGerais->add($div);    
        
*/        $div = new TVBox;
        $div->class = 'form-group col-lg-12';
        $div->add(new TLabel('Observações'));
        $obs->class = 'tfield'; // nao usar form-control em obs
        $obs->setSize('100%',100);
        $obs->style = 'min-width: 600px;';
        $div->add($obs);
        $tableGerais->add($div);
        
        
        if (!empty($Id))
        {
            $Id->setEditable(FALSE);
        }
    
        $bt_save = new TButton('salvar');
        
        $bt_save->setAction( new TAction(array($this, 'onSave')), _t('Save'));
        $bt_save->setImage('fa:floppy-o');
       
          
        $buttons = new TTable;
        $row = $buttons->addRow();
        $row->addCell($bt_save);
        $painel->add($this->form);
        $container = new TVBox;
        $container->style = 'width: 98%';
        $container->add($painel);
        
                                             
        $this->form->setFields(array($id, $codigo, $descricao, $ean,$valor, $obs, $bt_save));
                                      
        $painel->addFooter($buttons);
        
        
        parent::add($container);
        
    }
    
    public function onSave($param)
    {
        try
        {
            $data = $this->form->getData(); // get form data as array
            var_dump($data);
            
            $this->form->validate(); // validate form data           
            
            
/*
            $object = new ProdutoModel;  // create an empty object
            TTransaction::open('dbmysql'); // open a transaction
            
            $ValorProd = str_replace('.','', $data->Valor);
            $ValorProd = str_replace(',','.', $ValorProd);
            
            $data->Valor = $ValorProd;
            
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
             // get the generated Id
            $data->Id = $object->Id;
            
*/            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
           
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

}