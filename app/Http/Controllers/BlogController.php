<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class BlogController extends Controller
{

    /**
     * @param $image
     * @return string
     */
    private function imageUpload($image): string
    {
        $response = Http::attach('file[0]', $image->getContent(), 'image.'.$image->getClientOriginalExtension())->post(env('DISCORD_WEBHOOK'));
        return json_decode($response->body(), true)["attachments"][0]["url"];
    }
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return response()->json($blogs);
    }

    /**
     * @return JsonResponse
     */
    public function random(): JsonResponse
    {
        $blogs = Blog::inRandomOrder()->take(5)->get();
        return response()->json($blogs);
    }

    /**
     * @param string $search
     * @return JsonResponse
     */
    public function search(string $search): JsonResponse
    {
        $blogs = Blog::where('title','like', '%' .$search. '%')->orWhere('content','like', '%' .$search. '%')->get();
        return response()->json($blogs);
    }

    /**
     * @param StoreBlogRequest $request
     * @return JsonResponse
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $image = $request->file('image');
        $imageUrl = $this->imageUpload($image);
        $blog = Blog::create([
            'author_id' => Auth::id(),
            'image' => $imageUrl,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
        ]);

        $blog = Blog::where('id', '=', $blog->id)->with('author')->get();
        return response()->json($blog);
    }

    /**
     * @param string $blog
     * @return JsonResponse
     */
    public function show(string $blog): JsonResponse
    {
        $blog = Blog::find($blog);
        if (!$blog) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($blog);
    }

    /**
     * @param string $author_id
     * @return JsonResponse
     */
    public function byAuthor(string $author_id): JsonResponse
    {
        $blogs = Blog::where('author_id', '=', strval($author_id))->orderBy('created_at', 'DESC')->get();
        return response()->json($blogs);
    }

    /**
     * @param UpdateBlogRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateBlogRequest $request, string $id): JsonResponse
    {
        $blog = Blog::find($id);
        if (!$blog){
            return response()->json(['message' => 'Data not found'], 404);
        }
        if ($blog->author_id != Auth::id()){
            return response()->json(['message' => 'Unauthorized!'], 401);
        }
        $delete = $blog->delete();
        if(!$delete){
            return response()->json(['message' => 'Failed edit, try again!'], 500);
        }

        $imageUrl = $blog->image;
        if ($request->file('image')){
            $image = $request->file('image');
            $imageUrl = $this->imageUpload($image);
        }
        $newBlog = Blog::create([
            'author_id' => Auth::id(),
            'image' => $imageUrl,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
        ]);

        $newBlog = Blog::where('id', '=', $newBlog->id)->with('author')->get();
        return response()->json($newBlog);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $delete = $blog->delete();
        if(!$delete){
            return response()->json(['message' => 'Failed delete, try again!'], 500);
        }
        return response()->json(['message' => 'Success']);
    }
}
