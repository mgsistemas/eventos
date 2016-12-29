<?php
/**
 * ServicoList Listing
 * @author  <your name here>
 */
class ServicoList extends TPage
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
        $this->form = new TQuickForm('form_search_Servico');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Servico');
        $this->form->setFieldsByRow(3);
        

        // create the form fields
        $id = new TEntry('id');
        $descricao = new TEntry('descricao');
        $grupo_servico_id = new TDBCombo('grupo_servico_id','eventos','GrupoServico','id','descricao','descricao');
        $grupo_trabalho_id = new TDBCombo('grupo_trabalho_id','eventos','GrupoTrabalho','id','descricao','descricao');
        $segmento_id = new TDBCombo('segmento_id','eventos','Segmento','id','descricao','descricao');
        $situacao = new TCombo('situacao');
        
        // 
        $op_sit = array ('A'=>'Ativo','I'=>'Inativo');
        $situacao->addItems($op_sit);
        $segmento_id->enableSearch();
        $grupo_servico_id->enableSearch();
        $grupo_trabalho_id->enableSearch();


        // add the fields
        $this->form->addQuickField('Código', $id,  200 );
        $this->form->addQuickField('Descricao', $descricao,  200 );
        $this->form->addQuickField('Grupo Fiscal', $grupo_servico_id,  200 );
        $this->form->addQuickField('Grupo Trabalho', $grupo_trabalho_id,  200 );
        $this->form->addQuickField('Segmento', $segmento_id,  200 );
        $this->form->addQuickField('Situação', $situacao,  200 );

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Servico_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('ServicoForm', 'onEdit')), 'bs:plus-sign green');
        
        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Código', 'right');
        $column_descricao = new TDataGridColumn('descricao', 'Descricao', 'left');
        $column_grupo_servico_id = new TDataGridColumn('grupo_servico->descricao', 'Grupo Fiscal', 'left');
        $column_grupo_trabalho_id = new TDataGridColumn('grupo_trabalho->descricao', 'Grupo Trabalho', 'left');
        $column_segmento_id = new TDataGridColumn('segmento->descricao', 'Segmento', 'left');
        $column_situacao = new TDataGridColumn('situacao', 'Situação', 'center');
        $column_login = new TDataGridColumn('login', 'Login', 'left');
        $column_data_atualizacao = new TDataGridColumn('data_atualizacao', 'Atualizado em', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_grupo_servico_id);
        $this->datagrid->addColumn($column_grupo_trabalho_id);
        $this->datagrid->addColumn($column_segmento_id);
        $this->datagrid->addColumn($column_situacao);
        $this->datagrid->addColumn($column_login);
        $this->datagrid->addColumn($column_data_atualizacao);

        // transformer
        $column_situacao->setTransformer(function($value, $object, $row){
            $lbl = new TLabel('');
            if ($value == 'A') {
                $lbl->setValue('Ativo');
                $lbl->class = 'label label-success';
            } else {
                $lbl->setValue('Inativo');
                $lbl->class = 'label label-danger';
            }
            return $lbl;
        });
        

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_grupo_servico_id = new TAction(array($this, 'onReload'));
        $order_grupo_servico_id->setParameter('order', 'grupo_servico_id');
        $column_grupo_servico_id->setAction($order_grupo_servico_id);
        
        $order_grupo_trabalho_id = new TAction(array($this, 'onReload'));
        $order_grupo_trabalho_id->setParameter('order', 'grupo_trabalho_id');
        $column_grupo_trabalho_id->setAction($order_grupo_trabalho_id);
        
        $order_segmento_id = new TAction(array($this, 'onReload'));
        $order_segmento_id->setParameter('order', 'segmento_id');
        $column_segmento_id->setAction($order_segmento_id);
        
        $order_situacao = new TAction(array($this, 'onReload'));
        $order_situacao->setParameter('order', 'situacao');
        $column_situacao->setAction($order_situacao);
        

        // define the transformer method over image
        $column_data_atualizacao->setTransformer( function($value, $object, $row) {
            $date = new DateTime($value);
            return $date->format('d/m/Y');
        });


        
        // create EDIT action
        $action_edit = new TDataGridAction(array('ServicoForm', 'onEdit'));
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
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Lista de Serviços', $this->form));
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
            $object = new Servico($key); // instantiates the Active Record
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
        TSession::setValue('ServicoList_filter_id',   NULL);
        TSession::setValue('ServicoList_filter_descricao',   NULL);
        TSession::setValue('ServicoList_filter_grupo_servico_id',   NULL);
        TSession::setValue('ServicoList_filter_grupo_trabalho_id',   NULL);
        TSession::setValue('ServicoList_filter_segmento_id',   NULL);
        TSession::setValue('ServicoList_filter_situacao',   NULL);

        if (isset($data->id) AND ($data->id)) {
            $filter = new TFilter('id', '=', "$data->id"); // create the filter
            TSession::setValue('ServicoList_filter_id',   $filter); // stores the filter in the session
        }


        if (isset($data->descricao) AND ($data->descricao)) {
            $filter = new TFilter('descricao', 'like', "%{$data->descricao}%"); // create the filter
            TSession::setValue('ServicoList_filter_descricao',   $filter); // stores the filter in the session
        }


        if (isset($data->grupo_servico_id) AND ($data->grupo_servico_id)) {
            $filter = new TFilter('grupo_servico_id', '=', "$data->grupo_servico_id"); // create the filter
            TSession::setValue('ServicoList_filter_grupo_servico_id',   $filter); // stores the filter in the session
        }


        if (isset($data->grupo_trabalho_id) AND ($data->grupo_trabalho_id)) {
            $filter = new TFilter('grupo_trabalho_id', '=', "$data->grupo_trabalho_id"); // create the filter
            TSession::setValue('ServicoList_filter_grupo_trabalho_id',   $filter); // stores the filter in the session
        }


        if (isset($data->segmento_id) AND ($data->segmento_id)) {
            $filter = new TFilter('segmento_id', '=', "$data->segmento_id"); // create the filter
            TSession::setValue('ServicoList_filter_segmento_id',   $filter); // stores the filter in the session
        }


        if (isset($data->situacao) AND ($data->situacao)) {
            $filter = new TFilter('situacao', '=', "$data->situacao"); // create the filter
            TSession::setValue('ServicoList_filter_situacao',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Servico_filter_data', $data);
        
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
            
            // creates a repository for Servico
            $repository = new TRepository('Servico');
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
            

            if (TSession::getValue('ServicoList_filter_id')) {
                $criteria->add(TSession::getValue('ServicoList_filter_id')); // add the session filter
            }


            if (TSession::getValue('ServicoList_filter_descricao')) {
                $criteria->add(TSession::getValue('ServicoList_filter_descricao')); // add the session filter
            }


            if (TSession::getValue('ServicoList_filter_grupo_servico_id')) {
                $criteria->add(TSession::getValue('ServicoList_filter_grupo_servico_id')); // add the session filter
            }


            if (TSession::getValue('ServicoList_filter_grupo_trabalho_id')) {
                $criteria->add(TSession::getValue('ServicoList_filter_grupo_trabalho_id')); // add the session filter
            }


            if (TSession::getValue('ServicoList_filter_segmento_id')) {
                $criteria->add(TSession::getValue('ServicoList_filter_segmento_id')); // add the session filter
            }


            if (TSession::getValue('ServicoList_filter_situacao')) {
                $criteria->add(TSession::getValue('ServicoList_filter_situacao')); // add the session filter
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
            $object = new Servico($key, FALSE); // instantiates the Active Record
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
