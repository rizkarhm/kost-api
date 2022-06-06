<?php

namespace App\Http\Controllers\API;

use App\Models\Kost;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class KostController extends Controller
{
    public function all(Request $request){
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $types = $request->input('types');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if($id){
            $kost = Kost::find($id);
            return ResponseFormatter::success(
                $kost,
                'Data kost berhasil diambil'
            );
        } else{
            return ResponseFormatter::error(
                null,
                'Data kost tidak tersedia',
                484
            );
        }

        $kost = Kost::query();

        if($name){
            $kost->where('name', 'like', '%'.$name.'%');
        }

        if($types){
            $kost->where('types', 'like', '%'.$types.'%');
        }

        if($price_from){
            $kost->where('price', '>='.$price_from);
        }
        if($price_to){
            $kost->where('price', '<='.$price_to);
        }

        return ResponseFormatter::success(
            $kost->paginate($limit),
            'Data list kost berhasil diambil'
        );
    }
}
