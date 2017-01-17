<?php
/**
 * PagarList Listing
 * @author  <your name here>
 */
class PagarList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_search_Pagar');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Pagar');
        $this->form->setFieldsByRow(2);
        

        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo','=','P'));
        $criteria->add(new TFilter('situacao','=','A'));

        // create the form fields
        $empresa_id = new TDBCombo('empresa_id','eventos','Empresa','id','nome','nome');
        $plano_conta_id = new TDBCombo('plano_conta_id','eventos','PlanoConta','id','descricao','descricao',$criteria);
        $data_vencimento = new TDate('data_vencimento');
        $data_vencimento_final = new TDate('data_vencimento_final');
        $data_pagamento = new TDate('data_pagamento');
        $data_pagamento_final = new TDate('data_pagamento_final');
        $situacao = new TCheckGroup('situacao');
        
        $data_pagamento->setMask('dd/mm/yyyy');
        $data_pagamento_final->setMask('dd/mm/yyyy');
        $data_vencimento->setMask('dd/mm/yyyy');
        $data_vencimento_final->setMask('dd/mn/yyyy');
        
        $empresa_id->enableSearch();
        $plano_conta_id->enableSearch();


        // add the fields
        $this->form->addQuickField('Empresa Contábil', $empresa_id,  200);
        $this->form->addQuickField('Plano Conta', $plano_conta_id,  200);
        $this->form->addQuickField('Data Vencimento', $data_vencimento,  100);
        $this->form->addQuickField('Data Vencimento Final', $data_vencimento_final, 100);
        $this->form->addQuickField('Data Pagamento', $data_pagamento,  100);
        $this->form->addQuickField('Data Pagamento Final', $data_pagamento_final,  100);
        $this->form->addQuickField('Situação', $situacao,  100);
        
        $situacao->setLayout('horizontal');
        $situacao->addItems(['A' => 'Pendente', 'I' => 'Pago']);

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Pagar_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('PagarForm', 'onEdit')), 'bs:plus-sign green');
        
        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_data_vencimento = new TDataGridColumn('data_vencimento', 'Data Vencimento', 'center');
        $column_fornecedor_id = new TDataGridColumn('fornecedor->razao_social', 'Fornecedor', 'left');
        $column_empresa_id = new TDataGridColumn('empresa->nome', 'Empresa', 'left');
        $column_nota_fiscal = new TDataGridColumn('nota_fiscal', 'Nota Fiscal', 'center');
        $column_job_id = new TDataGridColumn('job_id', 'Job', 'center');
        $column_plano_conta_id = new TDataGridColumn('plano_conta->descricao', 'Plano Conta', 'left');
        $column_tipo_controle_id = new TDataGridColumn('tipo_controle->descricao', 'Controle', 'left');
        $column_valor = new TDataGridColumn('valor', 'Valor', 'right');
        $column_data_pagamento = new TDataGridColumn('data_pagamento', 'Data Pagamento', 'center');
        $column_situacao = new TDataGridColumn('situacao','Situação','center');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_data_vencimento);
        $this->datagrid->addColumn($column_fornecedor_id);
        $this->datagrid->addColumn($column_empresa_id);
        $this->datagrid->addColumn($column_nota_fiscal);
        $this->datagrid->addColumn($column_job_id);
        $this->datagrid->addColumn($column_plano_conta_id);
        $this->datagrid->addColumn($column_tipo_controle_id);
        $this->datagrid->addColumn($column_valor);
        $this->datagrid->addColumn($column_data_pagamento);
        $this->datagrid->addColumn($column_situacao);

        
        // create EDIT action
        $action_edit = new TDataGridAction(array('PagarForm', 'onEdit'));
        $action_edit->setUseButton(TRUE);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setUseButton(TRUE);
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Lista de Contas a Pagar', $this->form));
        $container->add($this->datagrid);
        $container->add($this->pageNavigation);
        
        parent::add($container);
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('eventos'); // open a transaction with database
            $object = new Pagar($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('PagarList_filter_empresa_id',   NULL);
        TSession::setValue('PagarList_filter_plano_conta_id',   NULL);
        TSession::setValue('PagarList_filter_data_vencimento',   NULL);
        TSession::setValue('PagarList_filter_data_pagamento',   NULL);

        if (isset($data->empresa_id) AND ($data->empresa_id)) {
            $filter = new TFilter('empresa_id', '=', "$data->empresa_id"); // create the filter
            TSession::setValue('PagarList_filter_empresa_id',   $filter); // stores the filter in the session
        }


        if (isset($data->plano_conta_id) AND ($data->plano_conta_id)) {
            $filter = new TFilter('plano_conta_id', '=', "$data->plano_conta_id"); // create the filter
            TSession::setValue('PagarList_filter_plano_conta_id',   $filter); // stores the filter in the session
        }


        if (isset($data->data_vencimento) AND ($data->data_vencimento)) {
            $dataV = TDate::date2us($data->data_vencimento);
            $filter = new TFilter('data_vencimento', '>=', "$dataV"); // create the filter
            TSession::setValue('PagarList_filter_data_vencimento',   $filter); // stores the filter in the session
        }

        if (isset($data->data_vencimento_final) AND ($data->data_vencimento_final)) {
            $dataVF = TDate::date2us($data->data_vencimento_final);
            $filter = new TFilter('data_vencimento_final', '<=', "$dataVF"); // create the filter
            TSession::setValue('PagarList_filter_data_vencimento_final',   $filter); // stores the filter in the session
        }

        if (isset($data->data_pagamento) AND ($data->data_pagamento)) {
            $dataP = TDate::date2us($data->data_pagamento);
            $filter = new TFilter('data_pagamento', '=', "$dataP"); // create the filter
            TSession::setValue('PagarList_filter_data_pagamento',   $filter); // stores the filter in the session
        }

        if (isset($data->data_pagamento_final) AND ($data->data_pagamento_final)) {
            $dataPF = TDate::date2us($data->data_pagamento_final);
            $filter = new TFilter('data_pagamento_final', '<=', "$dataPF"); // create the filter
            TSession::setValue('PagarList_filter_data_pagamento_final',   $filter); // stores the filter in the session
        }
        
        if (isset($data->situacao) AND ($data->situacao)) {
            $filter = new TFilter('situacao', '=', "$data->situacao"); // create the filter
            TSession::setValue('PagarList_filter_data_situacao',   $filter); // stores the filter in the session
        }
        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Pagar_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'eventos'
            TTransaction::open('eventos');
            
            // creates a repository for Pagar
            $repository = new TRepository('Pagar');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('PagarList_filter_empresa_id')) {
                $criteria->add(TSession::getValue('PagarList_filter_empresa_id')); // add the session filter
            }


            if (TSession::getValue('PagarList_filter_plano_conta_id')) {
                $criteria->add(TSession::getValue('PagarList_filter_plano_conta_id')); // add the session filter
            }


            if (TSession::getValue('PagarList_filter_data_vencimento')) {
                $criteria->add(TSession::getValue('PagarList_filter_data_vencimento')); // add the session filter
            }

            if (TSession::getValue('PagarList_filter_data_vencimento_final')) {
                $criteria->add(TSession::getValue('PagarList_filter_data_vencimento_final')); // add the session filter
            }

            if (TSession::getValue('PagarList_filter_data_pagamento')) {
                $criteria->add(TSession::getValue('PagarList_filter_data_pagamento')); // add the session filter
            }

            if (TSession::getValue('PagarList_filter_data_pagamento_final')) {
                $criteria->add(TSession::getValue('PagarList_filter_data_pagamento_final')); // add the session filter
            }
            
            if (TSession::getValue('PagarList_filter_data_situacao')) {
                $criteria->add(TSession::getValue('PagarList_filter_data_situacao')); // add the session filter
            }
            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('eventos'); // open a transaction with database
            $object = new Pagar($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            $this->onReload( $param ); // reload the listing
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted')); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    



    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
