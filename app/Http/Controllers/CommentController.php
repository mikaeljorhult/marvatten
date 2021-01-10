<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Comment::class);

        $validated = $request->validate([
            'comment'          => ['required'],
            'user_id'          => ['sometimes', 'required', Rule::exists('users', 'id')],
            'commentable_type' => ['required', Rule::in(['App\Models\Application', 'App\Models\Workstation'])],
            'commentable_id'   => ['required', Rule::exists(app($request->get('commentable_type'))->getTable(), 'id')],
        ]);

        Comment::create($validated);

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        // return edit view
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'comment'          => ['required'],
            'user_id'          => ['sometimes', 'required', Rule::exists('users', 'id')],
            'commentable_type' => ['required', Rule::in(['App\Models\Application', 'App\Models\Workstation'])],
            'commentable_id'   => ['required', Rule::exists(app($request->get('commentable_type'))->getTable(), 'id')],
        ]);

        $comment->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back();
    }
}
