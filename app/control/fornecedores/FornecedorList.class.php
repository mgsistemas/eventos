<?php
/**
 * FornecedorList Listing
 * @author  <your name here>
 */
class FornecedorList extends TPage
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
        $this->form = new TQuickForm('form_search_Fornecedor');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Fornecedor');
        $this->form->setFieldsByRow(3);

        // create the form fields
        $id = new TEntry('id');
        $razao_social = new TEntry('razao_social');
        $nome_fantasia = new TEntry('nome_fantasia');
        $cpf_cnpj = new TEntry('cpf_cnpj');
        $segmento_id = new TDBCombo('segmento_id','eventos','Segmento','id','descricao','descricao');
        $situacao = new TCombo('situacao');

        $situacao->addItems(array('A'=>'Ativo','I'=>'Inativo','B'=>'Bloqueado'));

        // add the fields
        $this->form->addQuickField('Código', $id,  200 );
        $this->form->addQuickField('Razão Social', $razao_social,  200 );
        $this->form->addQuickField('Nome Fantasia', $nome_fantasia,  200 );
        $this->form->addQuickField('CPF/CNPJ', $cpf_cnpj,  200 );
        $this->form->addQuickField('Segmento', $segmento_id,  200 );
        $this->form->addQuickField('Situação', $situacao,  200 );

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Fornecedor_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('FornecedorForm', 'onEdit')), 'bs:plus-sign green');
        
        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_razao_social = new TDataGridColumn('razao_social', 'Razao Social', 'left');
        $column_nome_fantasia = new TDataGridColumn('nome_fantasia', 'Nome Fantasia', 'left');
        $column_tipo_pessoa_id = new TDataGridColumn('tipo_pessoa->descricao', 'Tipo Pessoa Id', 'center');
        $column_cpf_cnpj = new TDataGridColumn('cpf_cnpj', 'Cpf/Cnpj', 'center');
        $column_cidade = new TDataGridColumn('cidade', 'Cidade', 'left');
        $column_segmento_id = new TDataGridColumn('segmento->descricao', 'Segmento Id', 'left');
        $column_situacao = new TDataGridColumn('situacao', 'Situacao', 'center');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_razao_social);
        $this->datagrid->addColumn($column_nome_fantasia);
        $this->datagrid->addColumn($column_tipo_pessoa_id);
        $this->datagrid->addColumn($column_cpf_cnpj);
        $this->datagrid->addColumn($column_cidade);
        $this->datagrid->addColumn($column_segmento_id);
        $this->datagrid->addColumn($column_situacao);

        // transformer
        $column_situacao->setTransformer(function($value, $object, $row){
            $lbl = new TLabel('');
            if ($value == 'A') {
                $lbl->setValue('Ativo');
                $lbl->class = 'label label-success';
            } else if ($value == 'I'){
                $lbl->setValue('Inativo');
                $lbl->class = 'label label-danger';
            } else if ($value == 'B') {
                $lbl->setValue('Bloqueado');
                $lbl->class = 'label label-danger';
            }
            return $lbl;
        });
        
        // create EDIT action
        $action_edit = new TDataGridAction(array('FornecedorForm', 'onEdit'));
        $action_edit->setUseButton(TRUE);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        //$this->datagrid->addAction($action_edit);
        
        
        // create CHANGE status action
        $action_chg_stt = new TDataGridAction(array('MotivoBloqueioFornecedorForm','onEdit'));
        $action_chg_stt->setUseButton(TRUE);
        $action_chg_stt->setButtonClass('btn btn-default');
        $action_chg_stt->setLabel('Alterar Status');
        $action_chg_stt->setImage('fa:cog fa-fw');
        $action_chg_stt->setField('id');
        $action_chg_stt->setParameter('tipo','F');
        
        // create VIEW historico movimento
        $action_hst = new TDataGridAction(array($this,'onHistorico'));
        $action_hst->setUseButton(TRUE);
        $action_hst->setButtonClass('btn btn-dafault');
        $action_hst->setLabel('Ver Histórico');
        $action_hst->setImage('fa:table fa-fw');
        $action_hst->setField('id');
        
        
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setUseButton(TRUE);
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        //$this->datagrid->addAction($action_del);
        
        $options_group = new TDataGridActionGroup('Ações','fa:table blue fa-fw');
        $options_group->addHeader('Ações Disponíveis');
        $options_group->addAction($action_edit);
        $options_group->addSeparator();
        $options_group->addHeader('Status');
        $options_group->addAction($action_chg_stt);
        $options_group->addAction($action_hst);
        $options_group->addSeparator();
        $options_group->addAction($action_del);
        
        
        $this->datagrid->addActionGroup($options_group);
        
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
        $container->add(TPanelGroup::pack('Lista de Fornecedores', $this->form));
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
            $object = new Fornecedor($key); // instantiates the Active Record
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
        TSession::setValue('FornecedorList_filter_id',   NULL);
        TSession::setValue('FornecedorList_filter_razao_social',   NULL);
        TSession::setValue('FornecedorList_filter_nome_fantasia',   NULL);
        TSession::setValue('FornecedorList_filter_cpf_cnpj',   NULL);
        TSession::setValue('FornecedorList_filter_segmento_id',   NULL);
        TSession::setValue('FornecedorList_filter_situacao',   NULL);

        if (isset($data->id) AND ($data->id)) {
            $filter = new TFilter('id', '=', "$data->id"); // create the filter
            TSession::setValue('FornecedorList_filter_id',   $filter); // stores the filter in the session
        }


        if (isset($data->razao_social) AND ($data->razao_social)) {
            $filter = new TFilter('razao_social', 'like', "%{$data->razao_social}%"); // create the filter
            TSession::setValue('FornecedorList_filter_razao_social',   $filter); // stores the filter in the session
        }


        if (isset($data->nome_fantasia) AND ($data->nome_fantasia)) {
            $filter = new TFilter('nome_fantasia', 'like', "%{$data->nome_fantasia}%"); // create the filter
            TSession::setValue('FornecedorList_filter_nome_fantasia',   $filter); // stores the filter in the session
        }


        if (isset($data->cpf_cnpj) AND ($data->cpf_cnpj)) {
            $filter = new TFilter('cpf_cnpj', 'like', "%{$data->cpf_cnpj}%"); // create the filter
            TSession::setValue('FornecedorList_filter_cpf_cnpj',   $filter); // stores the filter in the session
        }


        if (isset($data->segmento_id) AND ($data->segmento_id)) {
            $filter = new TFilter('segmento_id', '=', "$data->segmento_id"); // create the filter
            TSession::setValue('FornecedorList_filter_segmento_id',   $filter); // stores the filter in the session
        }


        if (isset($data->situacao) AND ($data->situacao)) {
            $filter = new TFilter('situacao', '=', "$data->situacao"); // create the filter
            TSession::setValue('FornecedorList_filter_situacao',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Fornecedor_filter_data', $data);
        
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
            
            // creates a repository for Fornecedor
            $repository = new TRepository('Fornecedor');
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
            

            if (TSession::getValue('FornecedorList_filter_id')) {
                $criteria->add(TSession::getValue('FornecedorList_filter_id')); // add the session filter
            }


            if (TSession::getValue('FornecedorList_filter_razao_social')) {
                $criteria->add(TSession::getValue('FornecedorList_filter_razao_social')); // add the session filter
            }


            if (TSession::getValue('FornecedorList_filter_nome_fantasia')) {
                $criteria->add(TSession::getValue('FornecedorList_filter_nome_fantasia')); // add the session filter
            }


            if (TSession::getValue('FornecedorList_filter_cpf_cnpj')) {
                $criteria->add(TSession::getValue('FornecedorList_filter_cpf_cnpj')); // add the session filter
            }


            if (TSession::getValue('FornecedorList_filter_segmento_id')) {
                $criteria->add(TSession::getValue('FornecedorList_filter_segmento_id')); // add the session filter
            }


            if (TSession::getValue('FornecedorList_filter_situacao')) {
                $criteria->add(TSession::getValue('FornecedorList_filter_situacao')); // add the session filter
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
            $object = new Fornecedor($key, FALSE); // instantiates the Active Record
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
    
    /**
     * Exibe uma janela contendo o historico das movimentacoes de status dos clientes
     */
    public function onHistorico($param)
    {
        $hst = new TQuickForm('frm_hst');
        $hst->style = 'padding: 20px;';
        
        $datagrid_hst = new BootstrapDatagridWrapper(new TDataGrid);
        $column_tipo_bloqueio_id = new TDataGridColumn('tipo_bloqueio_id','Tipo de Bloqueio',120,'center');
        $column_status = new TDataGridColumn('tipo_movimento','Status',250,'left');
        $column_motivo = new TDataGridColumn('motivo','Motivo',200,'left');
        $column_login = new TDataGridColumn('login','Login',100,'center');
        $column_atualizacao = new TDataGridColumn('data_atualizacao','Em',120,'center');
        
        $datagrid_hst->addColumn($column_tipo_bloqueio_id);
        $datagrid_hst->addColumn($column_status);
        $datagrid_hst->addColumn($column_motivo);
        $datagrid_hst->addColumn($column_login);
        $datagrid_hst->addColumn($column_atualizacao);
        
        $scroll = new TScroll();
        $scroll->setSize(550,400);
        $scroll->add($datagrid_hst);
        
        $column_tipo_bloqueio_id->setTransformer(function($value, $object, $row){
           try {
               TTransaction::open('eventos');
               $tipo = new TipoBloqueio($value);
               TTransaction::close();
               return $tipo->descricao;
           } catch (Exception $e){
               new TMessage('error',$e->getMessage());
           }                         
        });  
            
        $column_status->setTransformer(function($value, $object, $row){
            $lbl = new TLabel('');
            switch ($value) {
                case 'A' : 
                    $lbl->setValue('Ativo');
                    $lbl->class = 'label label-success';
                    break;
                case 'I' : 
                    $lbl->setValue('Inativo');
                    $lbl->class = 'label label-danger';
                    break;
                 case 'B' : 
                     $lbl->setValue('Bloqueado');
                     $lbl->class = 'label label-danger';
                     break;
             }
             return $lbl;
        });    
        
        $column_atualizacao->setTransformer(function($value, $object, $row){
            $data = new DateTime($value);
            return $data->format('d/m/Y H:i:s');
        });
        
        
        $datagrid_hst->createModel();
        
        // carregar
        TTransaction::open('eventos');
        $repository = new TRepository('MotivoBloqueio');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('origem_id','=',$param['id']));
        $criteria->add(new TFilter('tipo_bloqueio_id','=',2));
        
        $criteria->setProperty('order','data_atualizacao DESC');
        $objects = $repository->load($criteria);
        
        foreach($objects as $obj) {
            $grid = new stdClass;
            $grid->tipo_bloqueio_id = $obj->tipo_bloqueio_id;
            $grid->tipo_movimento = $obj->tipo_movimento;
            $grid->motivo = $obj->motivo;
            $grid->login = $obj->login;
            $grid->data_atualizacao = $obj->data_atualizacao;
            $datagrid_hst->addItem($grid);
        }
        
        
        $hst->add($scroll);
        
        new TInputDialog('Exibir Histórico',$hst);
    }
    
    
    
}
