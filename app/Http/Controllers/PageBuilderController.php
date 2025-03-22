<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\PageContent;

class PageBuilderController extends Controller
{
    public function builder($id)
    {
        $event = Event::where('EventID', $id)->firstOrFail();
        $pageContent = PageContent::where('EventID', $id)->first();

        return view('content.pages.page-builder', compact('event', 'pageContent'));
    }

    public function save(Request $request, $id)
    {
        $request->validate([
            'html' => 'required',
            'css' => 'required',
        ]);

        PageContent::updateOrCreate(
            ['EventID' => $id],
            ['html' => $request->html, 'css' => $request->css]
        );

        return response()->json(['message' => 'Page saved successfully']);
    }

    public function view($id)
    {
        $pageContent = PageContent::where('EventID', $id)->firstOrFail();
        return view('content.pages.view-page', compact('pageContent'));
    }
}