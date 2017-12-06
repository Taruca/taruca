<?php

namespace App\Http\Controllers\Backend;

use App\Models\Tag;
use App\Models\Segment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SegmentsController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $segments = Segment::select('id', 'segment', 'author', 'cate', 'created_at')
            ->when($search, function ($query) use ($search) {
                return $query->where('segment', 'like', '%' .$search .'%')->orWhere('author', 'like', '%' .$search .'%');
            })->paginate(2);
        return view('backend.segments', compact('segments', 'search'));
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
        $tagsId = $this->saveTags($tags);

        //添加段落
        $data = $request->except('tags');
        $segment =  Segment::create($data);
        $segment->tags()->attach($tagsId);
        return response()->json(['code' => 0, 'data' => [], 'desc' => '']);
    }

    public function show(Request $request) {
        $this->validate($request, ['id' => 'required']);
        $id = $request->input('id');
        $segment = Segment::find($id);
        $segment->tags = $segment->tags()->select('content')->get();
        return response()->json($segment);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'segment' => 'required|max:200',
            'author' => 'required|max:20',
            'article' => 'max:2000',
            'description' => 'max:2000',
            'thoughts' => 'max:2000',
            'setting' => 'max:500',
            'cate' => 'required',
        ]);
        $id = $request->input('id');
        $tags = $request->input('tags');
        $data = $request->except(['id', 'tags']);

        $tagsId = $this->saveTags($tags);
        $segment = Segment::find($id);
        $segment->update($data);
        if (count($tagsId) > 0) {
            $segment->tags()->attach($tagsId);
        } else {
            $segment->tags()->detach();
        }
        return response()->json(['code' => 0, 'data' => [], 'desc' => '']);
    }

    public function destroy(Request $request) {
        $this->validate($request, ['id' => 'required']);
        $id = $request->input('id');
        $segment =  Segment::find($id);
        $segment->tags()->detach();
        $segment->delete();
        return response()->json(['code' => 0, 'desc' => '', 'result' => []]);
    }

    private function saveTags($tags) {
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
        return $tagsId;
    }
}
