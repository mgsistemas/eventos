<?php
/**
 * MotivoBloqueioFornecedorForm Form
 * @author  <your name here>
 */
class MotivoBloqueioFornecedorForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_MotivoBloqueio');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('MotivoBloqueio');
        


        // create the form fields
        $id = new TEntry('id');
        $tipo_bloqueio_id = new TEntry('tipo_bloqueio_id');
        $origem_id = new TEntry('origem_id');
        $motivo = new TEntry('motivo');
        $tipo_movimento = new TEntry('tipo_movimento');
        $login = new TEntry('login');
        $data_atualizacao = new TDate('data_atualizacao');
        $razao_social = new TEntry('razao_social');
        $situacao = new TCombo('situacao');
        
        $op_situacao = array ('A'=>'Ativo', 'I'=>'Inativo', 'B'=>'Bloueado');
        $situacao->addItems($op_situacao);


        $origem_id->setValue($param['id']);
        $tipo_bloqueio_id->setValue(1); // 1 tipo = cliente

        // add the fields
        $this->form->addQuickField('Id', $id,  100 );
        //$this->form->addQuickField('Tipo Bloqueio Id', $tipo_bloqueio_id,  100 );
        //$this->form->addQuickField('Origem Id', $origem_id,  100 );
        $this->form->addQuickField('Cliente',$razao_social,400);
        $this->form->addQuickField('Situação',$situacao, 250);
        $this->form->addQuickField('Motivo', $motivo,  400, new TRequiredValidator );
        //$this->form->addQuickField('Tipo Motivmento', $tipo_motivmento,  200 );
        //$this->form->addQuickField('Login', $login,  200 );
        //$this->form->addQuickField('Data Atualizacao', $data_atualizacao,  100 );


        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction('Confirmar', new TAction(array('FornecedorList', 'onReload')), 'fa:floppy-o');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Mudança de Status', $this->form));
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('eventos'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            //var_dump($param);
            $this->form->validate(); // validate form data
            
            $object = new MotivoBloqueio;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            //$object->fromArray( (array) $data); // load the object with data

            $object->tipo_bloqueio_id = 2;
            $object->origem_id = $data->id;
            $object->motivo = $data->motivo;
            $object->login = TSession::getValue('login');
            $object->tipo_movimento = $data->situacao;
            $object->data_atualizacao = date('Y-m-d H:i:s');
            $object->store();
            
            $fornecedor = new Fornecedor($data->id);
            $fornecedor->situacao = $data->situacao;
            $fornecedor->store();
                        
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            //var_dump($param);
            if (isset($param['key']))
            {
                // var_dump($param);
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('eventos'); // open a transaction
                //$object = new MotivoBloqueio($key); // instantiates the Active Record
                $object = new Fornecedor($key);
                $object->motivo = "";
                $object->tipo_bloqueio_id = 1;
                $object->tipo_movimento = 'I';
                $object->origem_id = $key;
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}

