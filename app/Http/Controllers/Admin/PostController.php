<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Mail\NewPostNotificationToAdmin;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(9);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->getValidationRules());

        $data = $request->all();

        if (isset($data['image'])) {
            $image_path = Storage::put('post_covers', $data['image']);
            $data['cover'] = $image_path;
        }

        // Creazione del post
        $post = new Post();
        $post->fill($data);
        $post->slug = $this->generatePostSlugFromTitle($post->title);
        $post->save();

        // Collegamento con i vari tag
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
        
        // Invio l'email di notifica all'amministratore
        Mail::to('superadmin@boolpress.it')->send(new NewPostNotificationToAdmin($post));

        return redirect()->route('admin.posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $category = $post->category;

        return view('admin.posts.show', compact('post', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate($this->getValidationRules());

        $data = $request->all();

        $post = Post::findOrFail($id);

        // Se presente un immagine v?? prima rimossa la precedente e poi v?? salvata la nuova, per poi salvare nel db
        // Controllo se presente ???
        if (isset($data['image'])) {
            // Cancello l'attuale immagine se presente ???
            if ($post->cover) {
                Storage::delete($post->cover);
            }
            // Salvo la nuova ???
            $image_path = Storage::put('post_covers', $data['image']);
            // Salvo il path della nuova ???
            $data['cover'] = $image_path;
        }

        $post->fill($data);
        $post->slug = $this->generatePostSlugFromTitle($post->title);
        $post->save();

        if(isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        } else {
            $post->tags()->sync([]);
        }

        return redirect()->route('admin.posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post ->tags()->sync([]);
        // Se presente un'immagine cover, deve essere cancellata
        if ($post->cover) {
            Storage::delete($post->cover);
        }
        $post->delete();
        return redirect()->route('admin.posts.store');
    }

    private function generatePostSlugFromTitle($title) {
        // generiamo slug base
        // finch?? slug esiste nel db
        // aggiungiamo numero progressivo, 
        // se non esiste, salvo slug nel model
        $base_slug = Str::slug($title, '-'); // mio-post
        $slug = $base_slug; // mio-post
        $count = 1;
        $post_found = Post::where('slug', '=', $slug)->first();
        while ($post_found) {
            $slug = $base_slug . '-' . $count; // mio-post-1
            $post_found = Post::where('slug', '=', $slug)->first();
            $count++; // 2
        }

        return $slug;
    }

    private function getValidationRules() {
        return [
            'title' => 'required|max:255',
            'content' => 'required|max:30000',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'exists:tags,id',
            'image' => 'image|max:512'
        ];
    }
}
