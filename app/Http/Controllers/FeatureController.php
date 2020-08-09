<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

use App\User;
use Auth;


class FeatureController extends Controller
{
    //Easy to filter birthdays of all users in front-end by passing user's birthday(d,m,y format) and current date(d,m,y format) through resource but I'm doing it here just practice queries
    public function filterBirthdays()
    {
        //Today's Birthdays - Method 1
        $today = User::where(User::raw("(DATE_FORMAT(birthday, '%d'))"), now()->format('d'))->get(); //Or use ->paginate(5);

        //This week's Birthdays - Method 2 (Don't display today's birthdays)
        $users =  User::all();
        $week =[];

        foreach ($users as $user) {
            if($user->birthday->format('m') == now()->format('m') and in_array($user->birthday->format('d'), range(now()->format('d') + 1, now()->format('d') + 6))) {
                array_push($week, $user);
            }
        }

        //This month's Birthdays - Method 3 (Don't display today and this week's birthdays)
        $month = User::whereRaw('birthday LIKE "%-'. now()->format('m') .'-%"' )->where(User::raw("(DATE_FORMAT(birthday, '%d'))"), ">",  now()->format('d') + 7)->get(); //Or use ->paginate(5);

        return [
            'today' => UserResource::collection($today),
            'week' => UserResource::collection($week),
            'month' => UserResource::collection($month)
        ];
    }

    public function wishBirthday(Request $request)
    {
        $post = Auth::user()->posts()->create([
            'body' => $request->body,
            'friend_id' => $request->friend_id,
            'user_id' => Auth::user()->id
        ]);

        return (new PostResource($post))->response()->setStatusCode(201);
    }
}
