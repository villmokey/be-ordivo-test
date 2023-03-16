<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search         = $request->query('search', null);
        $page           = $request->query('page', null);
        $perPage        = $request->query('per_page', 15);
        // $paginate       = $request->query('paginate', true);
        
        try {
            $products = Product::query()
            ->with('images')    
            ->orderBy('created_at', 'DESC');
            
            if ($search) {
                $products->where('name', 'like', '%' . $search . '%');
            }

            $paginated = $products->paginate($perPage, ['*'], 'page', $page);

            return $this->sendSuccess($paginated, 'Success to get data');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'price' => 'required',
                'description' => 'required',
                'stock' => 'required',
                'images' => 'required'
            ]);

        if($validateUser->fails()){
            return $this->sendError($validateUser->errors());
        }

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'stock' => $request->stock,
            ]);

            foreach ($request->images as $key => $value) {
                $image = Image::findOrFail($value);
                $image->parent_id = $product->id;
                $image->parent_type = get_class($product);
                $image->save();
            }

            DB::commit();

            return $this->sendSuccess($product, 'Success to add data');

        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->sendError('failed');

        }
        
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::where('id', $id)->with('images')->first();
            return $this->sendSuccess($product, 'Success to add data');

        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validate = Validator::make($request->all(), 
            [
                'name' => 'required',
                'price' => 'required',
                'description' => 'required',
                'stock' => 'required'
            ]);

        if($validate->fails()){
            return $this->sendError($validate->errors());

        }

        try {
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->stock = $request->stock;
            $product->save();

            return $this->sendSuccess($product, 'Success to add data');

        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return $this->sendSuccess($product, 'Success to add data');

        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());

        }
    }
}
