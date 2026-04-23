<?php

namespace App\Http\Controllers;

use App\Product;
use App\Product_AuthLevels;
use App\Channel_Types;
use Illuminate\Http\Request;
use App\DiscountLevels;

class AuthLevelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if($isEditor){
            $channels = Channel_Types::all();
            $discLevels = DiscountLevels::all();
            $productos = Product::orderBy('id_product','ASC')->get();
            $prodLevels = Product_AuthLevels::orderBy('id_level','ASC')->with('product','channel','leveldiscount')->get();
            $prods = Product_AuthLevels::orderBy('id_level','ASC')->pluck('id_level');
        
            //dd($channels);
            foreach ($prods as $key => $prod) {
                $levels[$key] = array("level"=> $prod);
            }

        // dd($levels);
            return view('admin.products.index_auth', compact('prodLevels','channels','productos','discLevels'));
        }else{
            abort(403, 'Acción no autorizada.');
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
        $isEditor = auth()->user()->hasPermissionTo('products.create');
        if($isEditor){
        //dd(request()->all());
            $checkRegister = Product_AuthLevels::where('id_product',$request->id_product)->where('id_dist_channel',$request->id_dist_channel)->where('id_level_discount',$request->id_level_discount)->first();
        // dd($checkRegister);
            if(empty($checkRegister)){
                //dd("asdasd");
                if( Product_AuthLevels::create($request->all())){
                    return redirect()->back()->with('info','Nivel creado exitosamente');
                }else{
                    return redirect()->back()->with('info','Existio un problema al crear el nivel, intentelo de nuevo');
                }
            // dd("no encontro");
            }else{
            // dd($checkRegister[0]->id_level);
                $auth = Product_AuthLevels::find($checkRegister->id_level);
                $auth->id_product = $request->id_product;
                $auth->id_dist_channel = $request->id_dist_channel;
                $auth->discount_value = $request->discount_value;
                $auth->id_level_discount = $request->id_level_discount;
                if($auth->update()){
                    return redirect()->back()->with('info','Nivel actualizado exitosamente');
                }else{
                    return redirect()->back()->with('info','Existio un problema al modificar el nivel, intentelo de nuevo');
                }
            }
        }else{
            abort(403, 'Acción no autorizada.');
        }

        
    }

    public static function setLevel($prod_id,$id,$channel){
        $query = Product_AuthLevels::where('id_product',$prod_id)->where('id_level_discount',$id)->where('id_dist_channel',$channel)->get();
        //dd($query);
        if (empty($query)) {
            return "";
        } else {
            return $query;
        }
       // return $query;
       // dd($query);
        
        
    }
}
