<?php

namespace App\Http\Controllers\Backend;

use App\Models\Tag;
use App\Models\Segment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SegmentsController extends Controller
{
    public function index(Request $request) {
        return view('backend.segments');
    }

    public function add(Request $request) {
        //标签多对多，改表
        $this->validate($request, [
            'segment' => 'required|max:200',
            'author' => 'required|max:20',
            'article' => 'max:2000',
            'description' => 'max:2000',
            'thoughts' => 'max:2000',
            'setting' => 'max:500',
            'cate' => 'required',
        ]);
        //添加标签
        $tags = $request->input('tags');
        $tagsId = [];
        if (is_array($tags) && count($tags) > 0) {
            foreach ($tags as $val) {
                $tag = Tag::firstOrNew(['content' => $val]);
                if (!$tag->num) {
                    $tag->num = 1;
                } else {
                    $tag->num++;
                }
                $tag->save();
                $tagsId[] = $tag->id;
            }
        }

        //添加段落
        $data = $request->except('tags');
        $segment =  Segment::create($data);
        $segment->tags()->attach($tagsId);
        return response()->json(['code' => 0, 'data' => [], 'desc' => '']);
    }
}
