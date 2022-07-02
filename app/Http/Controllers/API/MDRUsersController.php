<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MDRUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DevRegistrationResource;
use App\Http\Resources\TokenizationResource;
use App\Models\Tokenization;
use GeneralHelper;
use GeneralParams;


interface IUserController {
    public function userlogin(Request $request);
    public function AuthenicationEntry($userID, $lastroute);
    public function setTokenState($istoken = null);
}
interface ItokenGenerator {
    public function tokenGen();
}
class Params {
    public static $tokenSetter;
    public static $getrow = [];
}
class QueryUtils extends Params {
    public function querybuilder($method, $snapshot = [], $target){
        switch(true) {
            case $method === 'post' : 
                if($target === 'tokenQueryBuild') {
                    $this->tokenizationQueryBuild(
                        $snapshot['userID'],
                        $snapshot['lastroute'], 
                        $snapshot['token']
                    );
                } 
                break;
            case $method === 'get' :
                
                break;
        }
    }
    public function tokenizationQueryBuild($userID, $lastroute, $genToken) {
        
        $posttokenlogs = [
            'userID' => $userID,
            'token' => $genToken,
            'lastRoute' => $lastroute,
            'isDestroyed' => '0',
            'isvalid' => '1'
        ];
        $toke = Tokenization::create($posttokenlogs);
        $responseMessage = [
            new TokenizationResource($toke)
        ];
        return response()->json([$responseMessage], 200);
    }
}
class MDRUsersController extends Controller implements IUserController, ItokenGenerator
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(MDRUsers::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json('empty_username_password', 200);
        }
        $postlogs = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'userType' => '2',
            'isLock' => '1',
            'imgURL' => 'none',
            'occupationStatus' => $request->occupationstatus,
            'occupationDetails' => $request->occupationdetails,
            'occupationPositionWork' => $request->occupationpositionwork,
            'nameofschool' => $request->nameofschool,
            'degree' => $request->degree,
            'address' => $request->address
        ];
        $dev = MDRUsers::create($postlogs);
        $responseMessage = [
            'message' => 'dev_registration_success',
            new DevRegistrationResource($dev)
        ];
        return response()->json($responseMessage, 200);
    }

    public function userlogin(Request $request) {
        if($request->role === 'developer') {
            
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required'
            ]);
            $collectrow = [];
            $tokerow = [];
            if($validator->fails()) {
                return response()->json('empty_username_password', 200);
            }
            if($request->isMethod('post')) {
                $usersloginrequest = DB::table('m_d_r_users')->select('*')
                ->where('username', '=', $request->username)->get();
                if(!$usersloginrequest->isEmpty()) {
                    foreach($usersloginrequest as $getdata){
                        $collectrow = [
                            'password' => $getdata->password,
                            'istype' => $getdata->userType,
                            'isLock' => $getdata->isLock,
                            'uid' => $getdata->id,
                            'fname' => $getdata->firstname,
                            'lname' => $getdata->lastname,
                            'uname' => $getdata->username
                        ];
                    }
                    if(Hash::check($request->password, $collectrow['password'])) {
                        if($collectrow['isLock'] === '1') {
                            return response()->json('ACCOUNT_LOCK', 200);
                        } else {
                            
                            if($collectrow['istype'] == "2") {
                               GeneralHelper::Slug(
                                'get',
                                $collectrow['uid'],
                                'api/checktoken'
                               );
                               if(count(GeneralParams::$result) !== 0){
                                foreach(GeneralParams::$result
                                    as $res
                                    ){
                                    $tokerow = [
                                        'isvalid' => $res
                                    ];
                                }
                                if($tokerow['isvalid'] === '1'){}
                                else {
                                    $this->AuthenicationEntry($collectrow['uid'], "developer_platform");
                                    $user_arr = [
                                        "fname" => $collectrow['fname'],
                                        "lname" => $collectrow['lname'],
                                        "uname" => $collectrow['uname'],
                                        "message" => 'success_developer',
                                        "role" => 'developer',
                                        "uid" => $collectrow['uid']
                                    ];
                                    return response()->json(
                                        $user_arr, 200
                                    );
                                }
                               } else {
                                    $this->AuthenicationEntry($collectrow['uid'], "developer_platform");
                                    $user_arr = [
                                        "fname" => $collectrow['fname'],
                                        "lname" => $collectrow['lname'],
                                        "uname" => $collectrow['uname'],
                                        "message" => 'success_developer',
                                        "role" => 'developer',
                                        "uid" => $collectrow['uid']
                                    ];
                                    return response()->json(
                                        $user_arr, 200
                                    );
                               }
                            }
                        }   
                    } else {
                        return response()->json('PASSWORD_INVALID', 200);
                    }
                } else {
                    return response()->json('ACCOUNT_NOT_FOUND', 200);
                }
            } else {
                return response()->json('REQUEST_NOT_POST', 200);
            }
        }
    }
   
    public function AuthenicationEntry($userID, $lastroute){
        $authentication = new QueryUtils();
        $payload = [
            'userID' => $userID,
            'lastroute' => $lastroute,
            'token' => $this->setTokenState("Basic:")
        ];
        $authentication->querybuilder('post', $payload, 'tokenQueryBuild');
    }
    public function setTokenState($istoken = null){
        Params::$tokenSetter = $istoken;
        return Params::$tokenSetter . $this->tokenGen();
    }
    public function tokenGen(){
        return bin2hex(random_bytes(16));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userID)
    {
        
    }
    public function checkusers($username) {
        $users = MDRUsers::select('*')
        ->where('username', '=', $username)
        ->get();
        if(!$users->isEmpty()) {
            return response()->json("username_taken", 200);
        }else {
            return response()->json("username_available", 200);
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
