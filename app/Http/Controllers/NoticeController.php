<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notice;

class NoticeController extends Controller
{
    public function index() {
        $whole_notices = Notice::where('store_id', 0)->get();
        $my_notices = Notice::where('store_id', session('store_id'))->get();

        return view('notice.index', ['whole_notices' => $whole_notices, 'my_notices' => $my_notices]);
    }

    public function show($id) {
        $notice = Notice::find($id);

        return view('notice.show', ['notice' => $notice]);
    }

    public function create() {
        return view('notice.create');
    }

    public function store(Request $request) {
        $notice = new Notice;

        $notice->store_id = session('store_id');
        $notice->title = $request->get('title');
        $notice->content = $request->get('content');

        $notice->save();

        return redirect()->route('notice.index');
    }

    public function edit($id) {
        $notice = Notice::find($id);

        return view('notice.edit', ['notice' => $notice]);
    }

    public function update($id, Request $request) {
        $notice = Notice::find($id);

        $notice->store_id = session('store_id');
        $notice->title = $request->get('title');
        $notice->content = $request->get('content');

        $notice->save();

        return redirect()->route('notice.show', ['id' => $notice->id]);
    }

    public function destroy($id) {
        $notice = Notice::find($id);

        if($notice->store_id == session('store_id')) {
            $notice->delete();
            return redirect()->route('notice.index');
        } else {
            abort('403', "권한이 없습니다.");
        }
    }
}
