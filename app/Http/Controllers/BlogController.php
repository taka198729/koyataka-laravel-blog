<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    /**
     * ブログ一覧を表示する
     *
     * @return view
     */
    public function showList()
    {
        $blogs = Blog::all();

        return view('blog.list', ['blogs' => $blogs]);
    }
    /**
     * ブログ詳細を表示する
     * @param int
     * @return view
     */
    public function showDetail($id)
    {
        $blog = Blog::find($id);

        if (is_null($blog)) {
            session()->flash('error_msg', 'データがありません。');
            return redirect(route('blogs'));
        }

        return view('blog.detail', ['blog' => $blog]);
    }

    /**
     * ブログ登録画面を表示する
     *
     * @return view
     */
    public function showCreate()
    {
        return view('blog.form');
    }

    /**
     * ブログを登録する
     *
     * @return view
     */
    public function exeStore(BlogRequest $request)
    {
        // ブログのデータを受け取る
        $inputs = $request->all();
        DB::beginTransaction();
        try {
            // ブログを登録
            Blog::create($inputs);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            abort(500);
        }
        session()->flash('error_msg', 'ブログを登録しました。');
        return redirect(route('blogs'));
    }
    /**
     * ブログ編集フォームを表示する
     * @param int
     * @return view
     */
    public function showEdit($id)
    {
        $blog = Blog::find($id);

        if (is_null($blog)) {
            session()->flash('error_msg', 'データがありません。');
            return redirect(route('blogs'));
        }

        return view('blog.Edit', ['blog' => $blog]);
    }
    /**
     * ブログを更新する
     *
     * @return view
     */
    public function exeUpdate(BlogRequest $request)
    {
        // ブログのデータを受け取る
        $inputs = $request->all();
        DB::beginTransaction();
        try {
            // ブログを更新
            $blog = Blog::find($inputs['id']);
            $blog->fill([
                'title' => $inputs['title'],
                'content' => $inputs['content']
            ]);
            $blog->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            abort(500);
        }
        session()->flash('error_msg', 'ブログを更新しました。');
        return redirect(route('blogs'));
    }
    /**
     * ブログ削除
     * @param int
     * @return view
     */
    public function exeDelete($id)
    {
        if (empty($id)) {
            session()->flash('error_msg', 'データがありません。');
            return redirect(route('blogs'));
        }
        try {
            // ブログ削除
            Blog::destroy($id);
        } catch (\Throwable $e) {
            abort(500);
        }
        session()->flash('error_msg', '削除しました。');
        return redirect(route('blogs'));
    }
}
