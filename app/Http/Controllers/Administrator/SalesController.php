<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;



class SalesController extends Controller
{
    //

    public function index(){
        return view('administrator.sales');
    }
    
    public function getData(Request $req){

        $sort = explode('.', $req->sort_by);

       // $serial = '';

        $data = Sales::with(['sales_details.serials', 'user'])
            ->whereBetween('sales_date', [$req->start . ' 00:00:00', $req->end . ' 23:59:59'])
            ->whereHas('sales_details', function($q) use ($req){
                $q->where('item_name', 'like', '%' . $req->itemname . '%');
            })
            ->whereHas('sales_details.serials', function($q) use ($req){
                $q->where('serial', 'like', '%' . $req->serial . '%');
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }
}
