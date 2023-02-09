<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\blogsResource;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;
use Validator;


class BlogController extends BaseController
{
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        $success['blogs'] = $blogs;
        return $this->sendResponse(blogsResource::collection($blogs), 'blogs retrieved successfully');
    }

    public function random()
    {
        $blogs = Blog::inRandomOrder()->take(5)->get();
        $success['blogs'] = $blogs;
        return $this->sendResponse(blogsResource::collection($blogs), 'blogs retrieved successfully');
    }

    public function searchBlog($search){
        $blogs = Blog::where('title','like', '%' .$search. '%')->orWhere('content','like', '%' .$search. '%')->get();
        if(is_null($blogs)){
            return $this->sendError(`can't find the blog`);
        }
        return $this->sendResponse(blogsResource::collection($blogs), 'data retrive success');
    }

    public function store(Request $request)
    {
        $input = request()->all();
        $validator = Validator::make($input, [
            'author' => 'required',
            'author_id' => 'required',
            'image' => 'required | image | mimes:jpeg,png,jpg,gif,svg | max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('create blog error', 'please fill up all the form');
        }

        $image = $request->file('image');
        if ($image == null) {
            return $this->sendError('Image is required');
        }

        else {
            $imageName = date('YmdHis') . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);
            $input['image'] = $imageName;
        }

        $blog = Blog::create([
            'author' => $input['author'],
            'author_id' => $input['author_id'],
            'image' => $imageName,
            'title' => $input['title'],
            'content' => $input['content']]);

        return $this->sendResponse(new blogsResource($blog), 'blog created successfully');

        
        
        
    }

    public function show($id)
    {
        $blog = Blog::find($id);
        if (is_null($blog)) {
            return $this->sendError('blog not found');
        }
        $success['blog'] = $blog;
        return $this->sendResponse(new blogsResource($blog), 'blog retrieved successfully');
    }

    public function getByAuthor($author_id)
    {
        $blog = Blog::where('author_id', $author_id)->orderBy('created_at', 'DESC')->get();
        if (is_null($blog)) {
            return $this->sendError('blog not found');
        }
        $success['blog'] = $blog;
        return $this->sendResponse(blogsResource::collection($blog), 'blog retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $input = request()->all();
        $validator = Validator::make($input, [
            'author' => 'required',
            'author_id' => 'required',
            'image' => 'nullable | max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);
        $blog = Blog::find($id);

        if ($validator->fails()) {
            return $this->sendError('blog update error', "please fill up all the form");
        }

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = date('YmdHis') . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);
            Storage::delete('public/images'. $blog->image);
            $blog->image = $imageName;

            $blog->author = $input['author'];
            $blog->author_id = $input['author_id'];
            $blog->image = $imageName;
            $blog->title = $input['title'];
            $blog->content = $input['content'];
            $blog->save();
        }
        else{
            $blog->author = $input['author'];
            $blog->author_id = $input['author_id'];
            $blog->image = $blog->image;
            $blog->title = $input['title'];
            $blog->content = $input['content'];
            $blog->save();
        }
        
        return $this->sendResponse(new blogsResource($blog), 'blog updated successfully');
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        if (is_null($blog)) {
            return $this->sendError('blog not found');
        }
        $blog->delete();
        return $this->sendResponse(new blogsResource($blog), 'blog deleted successfully');
    }
}