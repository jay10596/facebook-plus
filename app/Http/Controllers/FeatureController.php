<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\Notifications\BirthdayNotification;
use App\Notifications\TagNotification;
use App\Notifications\WishNotification;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

use Auth;
use App\User;
use App\Comment;
use App\Avatar;


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

        //Won't implement because this function is called in the create() of vue which is why it will give a new notification everytime the page is refreshed. I will have to create a new function and notify the user there.
        //Do it like notifyTaggedUser
        /*
            //Send notification
            auth()->user()->notify(new BirthdayNotification($today));
        */

        return [
            'today' => UserResource::collection($today),
            'week' => UserResource::collection($week),
            'month' => UserResource::collection($month)
        ];
    }

    //Also considered as writing on other user's wall
    public function wishBirthday(Request $request)
    {
        $post = Auth::user()->posts()->create([
            'body' => $request->body,
            'friend_id' => $request->friend_id,
            'user_id' => Auth::user()->id
        ]);

        //Send notification
        $friend = User::find($request->friend_id);
        $friend->notify(new WishNotification($post));

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    //Send notification
    public function notifyTaggedUser(Request $request) {
        $tagged_user_id = $request->tagged_user_id;
        $tagged_comment_id = $request->tagged_comment_id;

        if($tagged_user_id != null && $tagged_user_id != Auth::user()->id) {
            $tagged_user = User::find($tagged_user_id);
            $tagged_comment = Comment::find($tagged_comment_id);
            $tagged_user->notify(new TagNotification($tagged_comment));
        }
    }

    public function getAllAvatars(Request $request)
    {
        $user_id = $request->user_id;

        $avatars = Avatar::where('user_id', $user_id)->get();

        return $avatars;
    }
}
