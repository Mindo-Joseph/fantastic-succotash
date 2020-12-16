<?php

namespace Webkul\Webfont\DataGrids;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

class WebfontDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('google_web_fonts')
                        ->select('id', 'font', 'activated');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('webfont::app.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'font',
            'label' => trans('webfont::app.font'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'activated',
            'label' => trans('webfont::app.webfont-status'),
            'type' => 'boolean',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function($row) {
                if ($row->activated) {
                    return trans('webfont::app.activated');
                } else {
                    return trans('webfont::app.de-activated');
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET', //use post only for redirects only
            'route' => 'admin.cms.webfont.activate',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST', //use post only for requests other than redirects
            'route' => 'admin.cms.webfont.remove',
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'    => 'update',
            'label'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('admin.cms.webfont.massupdate'),
            'method'  => 'PUT',
            'options' => [
                'Active'   => 1,
                'Inactive' => 0,
            ],
        ]);

        $this->addMassAction([
            'type'    => 'delete',
            'label'   => trans('admin::app.customers.addresses.delete'),
            'action'  => route('admin.cms.webfont.massdelete'),
            'method'  => 'DELETE'
        ]);
    }
}
