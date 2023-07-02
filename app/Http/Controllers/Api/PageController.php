<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public static function subMemberCounts(User $user)
    {
        $pages = $user->memberPages()->get();
        foreach ($pages as $page)
            $page->update(['follower_counts' => $page->follower_counts - 1]);
    }

    public function index(Request $request)
    {
        $my_owned_pages = $request->user()->pages()->get();

        foreach ($my_owned_pages as $page) {
            $page->getFirstMedia('cover_image');
            $page->getFirstMedia('main_image');
        }

        return response()->json([
            'Message' => 'success',
            'Pages' => $my_owned_pages,
        ]);
    }

    public function create(Request $request)
    {
        // $request->validate([
        //     'email' => 'bail|required|email',
        //     'bio' => 'bail|required|string|max:100',
        //     'cover_image' => 'bail|nullable|image|mimes:jpg,bmp,png,svg,jpeg',
        //     'main_image' => 'bail|nullable|image|mimes:jpg,bmp,png,svg,jpeg',
        //     'type' => 'bail|required|in:Company,Famous,Specialty',
        //     'name' => 'bail|required|string',
        // ]);

        $page = $request->user()->pages()->create($request->all());

        if ($request->hasFile('main_image')) {
            $page->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

        if ($request->hasFile('cover_image')) {
            $page->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        return response()->json([
            'Message' => 'success',
            'page' => $page
        ]);
    }

    public function show(Request $request)
    {
        //show the specific one with its posts and its contents
    }

    public function destroy(Request $request)
    {
        $request->user()->pages()->find($request->id)->delete();

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function edit(Request $request)
    {
        $page = $request->user()->pages()->find($request->id);

        if ($request->hasFile('main_image')) {
            $page->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

        if ($request->hasFile('cover_image')) {
            $page->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        $page->update($request->all());

        return response()->json([
            'Message' => 'success',
            'Page' => collect($page)->except('media')
        ]);
    }
}
