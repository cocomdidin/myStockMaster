<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $product;

    public $productWarehouse = [];

    public $productWarehouses;

    public $editModal = false;

    public $image;

    public $category_id;

    public $gallery = [];

    public $width = 1000;

    public $height = 1000;

    public $description;

    public $listeners = [
        'editModal',
    ];

    public $listsForFields = [];
    public function mount()
    {
        $this->initListsForFields();
    }

    /** @var array */
    protected $rules = [
        'product.name'              => 'required|string|min:3|max:255',
        'product.code'              => 'required|string|max:255',
        'product.barcode_symbology' => 'required|string|max:255',
        'product.unit'              => 'required|string|max:255',
        'product.quantity'          => 'required|integer|min:1',
        'product.cost'              => 'required|numeric',
        'product.price'             => 'required|numeric',
        'product.stock_alert'       => 'required|integer|min:0',
        'product.order_tax'         => 'nullable|integer|min:0|max:100',
        'product.tax_type'          => 'nullable|integer|min:0|max:100',
        'product.note'              => 'nullable|string|max:1000',
        'product.category_id'       => 'required|integer|min:0|max:100',
        'product.brand_id'          => 'nullable|integer|min:0|max:100',
        'product.warehouse_id'      => 'nullable|integer|min:0|max:100',
        'product.featured'          => 'nullable',
    ];


    public function editModal($id)
    {
        abort_if(Gate::denies('product_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->product = Product::findOrFail($id);

        $this->productWarehouses = $this->product->warehouses()->withPivot('price', 'qty', 'cost')->get();

        $this->productWarehouse = $this->productWarehouses->mapWithKeys(function ($warehouse) {
            return [$warehouse->id => [
                'price' => $warehouse->pivot->price,
                'qty' => $warehouse->pivot->qty,
                'cost' => $warehouse->pivot->cost,
            ]];
        })->toArray();

        // dd($this->all());
        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('product_update'), 403);

        $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->product->name) . '-' . Str::random(5) . '.' . $this->image->extension();
            $this->image->storeAs('products', $imageName);
            $this->product->image = $imageName;
        }
        $this->product->price = 0;
        $this->product->cost = 0;
        $this->product->quantity = 0;

        $this->product->save();

        foreach ($this->productWarehouse as $warehouseId => $warehouse) {
            $this->product->warehouses()->updateExistingPivot($warehouseId, [
                'price' => $warehouse['price'],
                'qty' => $warehouse['qty'],
                'cost' => $warehouse['cost'],
            ]);
        }

        $this->emit('refreshIndex');

        $this->alert('success', __('Product updated successfully.'));

        $this->editModal = false;
    }

    public function getCategoriesProperty()
    {
        return Category::select(['name', 'id'])->get();
    }

    public function getBrandsProperty()
    {
        return Brand::select(['name', 'id'])->get();
    }

    public function render()
    {
        return view('livewire.products.edit');
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['brands'] = Brand::pluck('name', 'id')->toArray();
    }
}
