<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookmarkCollection;

use App\Item;
use App\Bookmark;


class BookmarkController extends Controller
{
    public function bookmarkUnbookmark(Item $item)
    {
        $item->bookmarks()->toggle(auth()->user());

        return new BookmarkCollection($item->bookmarks);
    }
}
