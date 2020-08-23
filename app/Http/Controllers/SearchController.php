<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\User;

class SearchController extends Controller
{
    public function getUsers(Request $request)
    {
        $myString = $request->searchTerm;

        $searchResult = User::where('name', 'like', "%$myString%")->get();

        return UserResource::collection($searchResult);

        /* //If want to get search from the whole table which also includes email
            $searchResult = User::search($request->searchTerm)->where('user_id', request()->user()->id)->get();

            return UserResource::collection($searchResult);
        */
    }
}
