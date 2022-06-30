<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MDRUsers;
use Illuminate\Http\Request;
use App\Models\Tokenization;
use GeneralHelper;
use GeneralParams;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

interface ITokenization {
    public function tokenIdentify($userID);
}

class TokenizationController extends Controller implements ITokenization
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }
    public function gettokenization($userID) {
        $getusersaved = Tokenization::select('isvalid', 'lastRoute')
        ->where('userID', '=', $userID)
        ->get();
        $getuser = MDRUsers::select('*')
        ->where('id', '=', $userID)
        ->get();

        $collectValue = [];
        if(!$getusersaved->isEmpty()) {
            foreach($getusersaved as $usersaved) {
                $collectValue = [
                    'isvalid' => $usersaved->isvalid,
                    'lastRoute' => $usersaved->lastRoute
                ];
            }
            if($collectValue['isvalid'] === '1') {
                if($collectValue['lastRoute'] === 'developer_platform') {
                    return response()->json($getuser, 200);
                } else if($collectValue['lastRoute'] === '/developer/dashboard') {
                    return response()->json($getuser, 200);
                } else {
                    return response()->json("invalid_token", 200);
                }
            }
        }
        else {
            return response()->json('empty', 200);
        }
    }
    public function tokenIdentify($userID) {
        $account_userid = $userID;
        if($account_userid === 'unknown') {
            return response()->json("invalid_token", 200);
        } else if($account_userid === 'unknown1'){
            return response()->json("invalid_token", 200);
        } else {

            GeneralHelper::Slug(
                'get',
                $account_userid,
                'api/checktoken'
            );
            $iterate = GeneralParams::$result;
            $tokenArray = [];
            $userArray = [];
            if(!empty($iterate)){
                foreach($iterate as $slug){
                    $tokenArray = [
                        'isvalid' => $slug->isvalid,
                        'lastRoute' => $slug->lastRoute
                    ];
                }
                if($tokenArray['isvalid'] === '1'){
                    if($tokenArray['lastRoute'] === 'developer_platform'){
                        GeneralHelper::Slug(
                            'get',
                            $account_userid,
                            'api/getuser-info'
                        );
                        foreach(GeneralParams::$dynamic_result as $userSlug) {
                            $userArray = [
                                'fname' => $userSlug->firstname,
                                'lname' => $userSlug->lastname,
                                'uname' => $userSlug->username,
                                'imgurl' => $userSlug->imgURL,
                                'uid' => $userSlug->id,
                                'lastRoute' => $tokenArray['lastRoute'],
                                'key' => 'token_exist_dev_platform'
                            ];
                        }

                        return response()->json($userArray, 200);
                    } else if($tokenArray['lastRoute'] === '/developer/dashboard') {
                        GeneralHelper::Slug(
                            'get',
                            $account_userid,
                            'api/getuser-info'
                        );
                        foreach(GeneralParams::$dynamic_result as $userSlug) {
                            $userArray = [
                                'fname' => $userSlug->firstname,
                                'lname' => $userSlug->lastname,
                                'uname' => $userSlug->username,
                                'imgurl' => $userSlug->imgURL,
                                'uid' => $userSlug->id,
                                'lastRoute' => $tokenArray['lastRoute'],
                                'key' => 'token_exist_dev_platform'
                            ];
                        }

                        return response()->json($userArray, 200);
                    }
                }

            } else{
                return response()->json("empty_result", 200);
            }
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
