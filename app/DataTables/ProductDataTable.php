<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Product> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('category', function ($row) {


                return $row->categoryname ? $row->categoryname->name : "NA";
            })
            ->addColumn('subcategoryname', function ($row) {
                return $row->subcategoryname ? $row->subcategoryname->name : 'NA';
            })

            ->addColumn('brandname', function ($row) {

                return $row->brandname ? $row->brandname->name : 'NA';
            })

            ->addColumn('image', function ($row) {
                return '<img style="height:100px;width:100px"  src="' . asset('storage/' . $row->main_image) . '" class="img-fluid rounded" alt="Product Image">';
            })
           

            ->addColumn('action', function ($row) {

                return '
                 <a class="btn btn-success" href="' . route('admin.product.edit', $row->id) . '">Edit</a>

                 <a class="btn btn-danger" href="' . route('admin.product.destroy', $row->id) . '">Delete</a>
                
                ';
            })->rawColumns(['action','image'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Product>
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->with('categoryname', 'brandname', 'subcategoryname'); // eager load category
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('name')->title('Name'),
            Column::computed('category')->title('Category'),
            Column::computed('subcategory')->title('subcategory'),
            Column::computed('brand')->title('brand'),
            Column::computed("image")->title(value: "Image"),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
