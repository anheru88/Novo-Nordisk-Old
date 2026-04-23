<?php

namespace App\Http\Controllers;

use App\Events\OrderNotificationsEvent;
use App\Negotiation;
use App\Notifications;
use App\NegotiationComments;
use App\NegotiationConcepts;
use App\NegotiationDetails;
use App\PricesList;
use App\Product_AuthLevels;
use App\ProductxPrices;
use App\Quotation;
use App\QuotationDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Validator;
// use Auth;

class NovoAppController extends Controller
{
    public function login()
    {
        $user = User::where('email', request()->email)->first();
        // Auth::login($user);
        // return response()->json([
        //     'success'=> true,
        //     'user'   => $user,
        //     'iduser' => $user->id
        // ]);
        // Autenticacion Novo Auth0
        if (isset($user)) {
            $nick = $user->nickname;
            $client = new Client();
            $AUTH0_SCOPE = 'openid profile email';
            $AUTH0_CONNECTION = 'NovoUsers';
            $AUDIENCE_URL = 'https://' . env('AUTH0_DOMAIN') . '/api/v2/';
            $OAUTH_TOKEN_URL = 'https://' . env('AUTH0_DOMAIN') . '/oauth/token';
            // dd($OAUTH_TOKEN_URL);
            try {
                $response = $client->post($OAUTH_TOKEN_URL, [
                    'json' => [
                        'client_id'     => env('AUTH0_CLIENT_ID'),
                        'client_secret' => env('AUTH0_CLIENT_SECRET'),
                        'grant_type'    => 'password',
                        'audience'      => $AUDIENCE_URL,
                        'username'      => strtolower(request()->email),  # Usernames in Auth0 are lowercase, in the apps they should be uppercase. desarrollos@hqr.com.co
                        'password'      => request()->password, # novonordiskcolombia2019
                        'connection'    => $AUTH0_CONNECTION,
                        'scope'         => $AUTH0_SCOPE
                    ]
                ]);
                $token = json_decode($response->getBody());
                $code = $response->getStatusCode();
                $token = $token->access_token;
            } catch (ClientException $exception) {
                $responseBody = $exception;
                $code = $responseBody->getResponse()->getStatusCode();
                // dd($responseBody->getResponse()->getStatusCode());
            }
            // dd($code);
            if ($code == 200 && $token != "") {
                Auth::login($user);
                $isAuthorized = User::where('email', request()->email)->orderby('id', 'ASC')->first();
                $isAuthorized = $isAuthorized->is_authorizer;
                // dd($isAuthorized);
                if ($isAuthorized == 1) {
                    $user = Auth::user();
                    $success['token'] = $user->createToken('appToken')->accessToken;
                    //After successfull authentication, notice how I return json parameters
                    return response()->json([
                        'success'=> true,
                        'token'  => $success,
                        'user'   => $user,
                        'iduser' => $user->id
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'El usuario que intento acceder no es autorizador'
                    ], 401);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Correo electrónico o contraseña no válidos',
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'El correo ingresado no existe o esta mal escrito',
            ], 401);
        }
    }

    /**
     * Register api.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
            'password' => 'required', 'string', 'min:8', 'confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('appToken')->accessToken;
        return response()->json([
            'success' => true,
            'token' => $success,
            'user' => $user
        ]);
    }

    public function logout(Request $res)
    {
        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();
            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userForRole()
    {
        $users = User::orderBy('name', 'ASC')
            ->with('roles')->get();
        return $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quotation()
    {
        // $cot = QuotationDetails::where('id_quotation', request()->idquota)
        //     ->join('nvn_quotations', 'nvn_quotations_details.id_quotation', '=', 'nvn_quotations.id_quotation')
        //     ->where('nvn_quotations.id_authorizer_user', request()->iduser)
        //     ->where('nvn_quotations.id_auth_level', '>' , '1')
        //     ->where('nvn_quotations.pre_aproved', 1)
        //     ->orderBy('nvn_quotations.id_quotation', 'ASC')
        //     ->select('nvn_quotations_details.*')
        //     ->with('cliente', 'channel', 'users', 'status', 'creator')
        //     ->get();
        //return (request()->iduser);
        $cot = Quotation::where('id_authorizer_user', request()->iduser)->where('id_auth_level', '>', 1)->where('pre_aproved', 1)->orderBy('id_quotation', 'ASC')
            ->with('cliente', 'channel', 'users', 'status', 'creator')
            ->get();
        if (!empty($cot)) {
            return response()->json([
                'success' => true,
                'cot'     => $cot
            ]);
        }else{
            return response()->json([
                'success' => false,
                'cot'     => $cot,
                'message' => 'Usted no cuenta con cotizaciones pendientes'
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showquotation($id)
    {
        // return $id;
        $showCot = Quotation::where('id_quotation', $id)->orderBy('id_quotation', 'ASC')
        ->with('cliente', 'channel', 'users', 'status', 'creator', 'city')
        ->get();
        // return $showCot;
        if (isset($showCot)) {
            return response()->json([
                'success' => true,
                'showCot' => $showCot
            ]);
        }else{
            return response()->json([
                'success' => false,
                'showCot' => $showCot,
                'message' => 'Usted no cuenta con cotizaciones pendientes'
            ]);
        }
    }

    public function producForDetail()
    {
        $prod = QuotationDetails::where('id_quotation', request()->id)
        ->join('nvn_products', 'nvn_quotations_details.id_product', '=', 'nvn_products.id_product')
        ->orderBy('nvn_products.prod_name', 'ASC')
        ->select('nvn_quotations_details.*')
        ->with('product', 'payterm')
        ->get();
        if (isset($prod)) {
            return response()->json([
                'success' => true,
                'prod' => $prod
            ]);
        }else{
            return response()->json([
                'success' => false,
                'prod' => $prod,
                'message' => 'Usted no cuenta con cotizaciones pendientes'
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function negotiation()
    {
        $neg = Negotiation::where('id_authorizer_user', request()->iduser)->where('id_auth_level', '>', 1)->where('pre_approved', 1)->orderBy('id_negotiation', 'ASC')
            ->with('cliente', 'channel', 'users', 'status', 'creator')
            ->get();
        if (isset($neg)) {
            return response()->json([
                'success' => true,
                'neg'   => $neg
            ]);
        }else{
            return response()->json([
                'success' => false,
                'neg'   => $neg,
                'message' => 'Usted no cuenta con negociaciones pendientes'
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNegotiation($id)
    {
        $showNeg = Negotiation::where('id_negotiation', $id)->orderBy('id_negotiation', 'ASC')
        ->with('cliente', 'channel', 'users', 'status', 'creator', 'city')
        ->get();
        if (isset($showNeg)) {
            return response()->json([
                'success' => true,
                'showNeg' => $showNeg
            ]);
        }else{
            return response()->json([
                'success' => false,
                'showNeg' => $showNeg,
                'message' => 'Usted no cuenta con negociaciones pendientes'
            ]);
        }
    }

    public function pricelist() {
        $pricelists = PricesList::orderBy('id_pricelists','DESC')
        ->with('products')
        ->get();
        if (isset($pricelists)) {
            return response()->json([
                'success' => true,
                'pricelists'   => $pricelists
            ]);
        }else{
            return response()->json([
                'success' => false,
                'pricelists'   => $pricelists,
                'message' => 'Usted no cuenta con listas de precios asignadas'
            ]);
        }
    }

    public function listsprices()
    {
        $id = request()->id;
        $pricelist      = PricesList::where('id_pricelists',$id)->first();
        $productos      = ProductxPrices::where('id_pricelists',$id)->orderBy('id_productxprices','ASC')->get();
        $autorizador    = User::where('is_authorizer',1)->where('id', $pricelist->id_authorizer_user)->first();
        $pricel = [];
        $pricelistcomercial     = [];
        $pricelistinstitucional = [];
        foreach ($productos as $key => $producto) {
            $authlevels = Product_AuthLevels::where('id_pricelists',$id)
            ->where('id_product',$producto->id_product)
            ->where('id_dist_channel',5)
            ->orderBy('id_level_discount','ASC')
            ->with('product')
            ->get();
            array_push($pricelistcomercial,$authlevels);
        }
        $pricel2 = [];
        foreach ($productos as $key => $producto) {
            $authlevels = Product_AuthLevels::where('id_pricelists',$id)
            ->where('id_product',$producto->id_product)
            ->where('id_dist_channel',6)
            ->orderBy('id_level_discount','ASC')
            ->with('product')
            ->get();
            array_push($pricelistinstitucional,$authlevels);
        }
        if (isset($pricelistcomercial) && isset($pricelistinstitucional)) {
            return response()->json([
                'success' => true,
                'commercial'   => $pricelistcomercial,
                'institutional'   => $pricelistinstitucional,
                'message' => 'Lista de precios cargada con éxito'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Usted no cuenta con listas de precios asignadas'
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function scaleName($prodid)
    {
        $scalename = Scales::where('id_product', $prodid)->first(['scale_number']);
        if (isset($scalename)) {
            $toJson = json_decode($scalename, TRUE);
            $str = $toJson['scale_number'];
            $scalename = json_encode($str);
        }
        // dd($scalename);
        return $scalename;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function conceptName($conceptid)
    {
        if ($conceptid > 0) {
            $conceptname = NegotiationConcepts::where('id_negotiation_concepts', $conceptid)->first();
            $conceptname = $conceptname->name_concept;
        } else {
            $conceptname = 'Sin concepto';
        }
        return $conceptname;
    }

    // autorizar Neg y Cot

    public function authorizeQuote(){
        // dd(request()->id);
        $id = request()->id;
        $answer = request()->answer;
        $comment = request()->comment;
        $sendBy = request()->sender_id;
        $quota = Quotation::where('id_quotation', $id)->with('cliente')->first();
        $quota->is_authorized       = $answer;
        $quota->comments_auth       = $comment;
        if ($quota->update()) {
            $dateIni =  $quota->quota_date_ini;
            if($answer == 4){
                $products = QuotationDetails::where('id_quotation', $id)->get();
                foreach ($products as $key => $product) {
                    $inactivate = QuotationDetails::where('id_product', $product->id_product)
                    ->where('id_client',$product->id_client)
                    ->where(function($query){
                        $query->where('is_valid', 1)
                        ->orWhere('is_valid', 6);
                    })
                    ->whereHas('quotation', function ($query) use($dateIni) {
                        return $query->where('quota_date_end', '<=', $dateIni);
                    })
                    ->update(['is_valid' => 0]);
                }
                $quotaDetails = QuotationDetails::where('id_quotation', $id)->update(['is_valid' => 1]);
                $cam = $quota->cliente->id_diab_contact;
                $msg = 'La cotización '.$quota->quota_consecutive.' fue aprobada';
                $this->sendNotification($msg, $cam, $sendBy);
                return response()->json([
                    'success' => true,
                    'idquota' => $id,
                    'message' => $msg
                ]);
            }else{
                $quotaDetails = QuotationDetails::where('id_quotation', $id)->update(['is_valid' => 0]);
                $cam = $quota->cliente->id_diab_contact;
                $msg = 'La cotización '.$quota->quota_consecutive.' fue rechazada';
                $this->sendNotification($msg, $cam, $sendBy);
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
        }
    }

    public function authorizeNegotiation(Request $request){

        $sendBy = request()->sender_id;
        $idUser = Auth::user()->id;
        $roles = Auth::user()->roles;
        $rol = $roles[0]->id;
        $nego = Negotiation::where('id_negotiation', $request['id'])->with('cliente')->first();
        $nego->is_authorized       = $request['respuesta'];

        if ($nego->update()) {
            if($request['comment']){
                $comment = $request['comment'];
                $comments = new NegotiationComments();
                $comments->id_negotiation = $request['id'];
                $comments->created_by     = $idUser;
                $comments->type_comment   = $rol;
                $comments->text_comment   = $comment;
                $comments->save();
            }
            if($request['respuesta'] == 4){
                $products = NegotiationDetails::where('id_negotiation',$request['id'])->get();
                $negoDetails = NegotiationDetails::where('id_negotiation',$request['id'])->update(['is_valid' => 1]);
                $cam = $nego->client->id_diab_contact;
                $msg = 'La negociación '.$nego->negotiation_consecutive.' fue aprobada';
                $this->sendNotification($msg, $cam, $sendBy);
                return $msg;
                // return redirect()->route('autorizaciones.index')->with('success','Cotización aprobada exitosamente');
            }else{
                $negoDetails = NegotiationDetails::where('id_negotiation',$request['id'])->update(['is_valid' => 0]);
                $cam = $nego->client->id_diab_contact;
                $msg = 'La negociación '.$nego->negotiation_consecutive.' fue rechazada';
                $this->sendNotification($msg, $cam, $sendBy);
                return $msg;
                // return redirect()->route('autorizaciones.index')->with('info','Cotización rechazada exitosamente');
            }
        }
    }

    public function rejectQuote(){
        // return response()->json([
        //     'success' => true,
        //     'showNeg' => request()->showcot,
        //     'message' => 'Usted no cuenta con negociaciones pendientes'
        // ]);
        // dd();
        $id = request()->id;
        $answer = request()->answer;
        $sendBy = request()->sender_id;
        $quota = Quotation::where('id_quotation', $id)->with('cliente')->first();
        $quota->is_authorized       = '4';
        // $quota->comments_auth       = $request['comment'];
        if ($quota->update()) {
            $dateIni =  $quota->quota_date_ini;
            if($answer == 4){
                $products = QuotationDetails::where('id_quotation', $id)->get();
                foreach ($products as $key => $product) {
                    $inactivate = QuotationDetails::where('id_product', $product->id_product)
                    ->where('id_client',$product->id_client)
                    ->where(function($query){
                        $query->where('is_valid', 1)
                        ->orWhere('is_valid', 6);
                    })
                    ->whereHas('quotation', function ($query) use($dateIni) {
                        return $query->where('quota_date_end', '<=', $dateIni);
                    })
                    ->update(['is_valid' => 0]);
                }
                $quotaDetails = QuotationDetails::where('id_quotation', $id)->update(['is_valid' => 1]);
                $cam = $quota->cliente->id_diab_contact;
                $msg = 'La cotización '.$quota->quota_consecutive.' fue aprobada';
                $this->sendNotification($msg, $cam, $sendBy);
                return $msg;
            }else{
                $quotaDetails = QuotationDetails::where('id_quotation', $id)->update(['is_valid' => 0]);
                $cam = $quota->cliente->id_diab_contact;
                $msg = 'La cotización '.$quota->quota_consecutive.' fue rechazada';
                $this->sendNotification($msg, $cam, $sendBy);
                return $msg;
            }
        }
    }

    public function sendNotification(string $msg, int $cam, int $sendBy){
        // dd($cam);
        $notiUsers = [];
        array_push($notiUsers, $cam);

        $users_auth = User::where('is_authorizer',1)->where('authlevel',2)->get();
        foreach($users_auth as $user){
            $notification = Notifications::create([
                'destiny_id'    => $user->id,
                'sender_id'     => $sendBy,
                'type'          => 'Autorizacion de cotizacion',
                'data'          => $msg,
                'url'           => "cotizaciones/",
                'readed'        => 0,
            ]);
            array_push($notiUsers, $user->id);
        }

        $users_notified = User::whereHas(
            'roles', function($q){
                $q->where('slug', 'admin_venta');
            }
        )->get();

        foreach($users_notified as $user){
            $notification = Notifications::create([
                'destiny_id'    => $user->id,
                'sender_id'     => $sendBy,
                // Auth::user()->id
                'type'          => 'Autorizacion de cotizacion',
                'data'          => $msg,
                'url'           => "cotizaciones/",
                'readed'        => 0,
            ]);
            array_push($notiUsers, $user->id);
        }

        $not['description']     = $msg;
        $not['url']             = "cotizaciones/";
        $not['userId']          = $notiUsers;
        event(new OrderNotificationsEvent($not));
    }
}
