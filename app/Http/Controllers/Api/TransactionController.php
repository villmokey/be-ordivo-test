<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
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
            $transactions = Transaction::query()
            ->with('detail.product')    
            ->orderBy('created_at', 'DESC');
            
            if ($search) {
                $transactions->where('name', 'like', '%' . $search . '%');
            }

            $paginated = $transactions->paginate($perPage, ['*'], 'page', $page);

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
            'customer_name' => 'required',
            'customer_address' => 'required',
            'customer_email' => 'required',
            'total' => 'required'
        ]);

        if($validateUser->fails()){
            return $this->sendError($validateUser->errors());
        }


        DB::beginTransaction();


        try {
            $transaction = Transaction::create([
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_email' => $request->customer_email,
                'total' => $request->total
            ]);

            $transaction->detail()->createMany($request->products);

            DB::commit();

            $transaction['detail'] = TransactionDetail::where('transaction_id', $transaction->id)->with('product')->get();

            return $this->sendSuccess($transaction, 'Success to create transaction');


        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());

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
            $transaction = Transaction::where('id', $id)->with('detail.product')->get();

            return $this->sendSuccess($transaction, 'Success to get transaction');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());

        }
   

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
