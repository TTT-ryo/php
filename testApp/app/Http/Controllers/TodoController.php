<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo; 
//Modelを使用可能に
//use=使う App～（パス＝ネームスペース） このファイルを使うと宣言

/**
 * TodoController class
 */
class TodoController extends Controller
{
    /**
     * @var Todo
     */
    private Todo $todo;

    /**
     * constructor function
     * @param Todo $todo
     */
    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }
    
    /**
     * Display a listing of the resource.
     * index function
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $todos = $this->todo->all();
        // DBのtodos table から全件取得=その為のSQL文の発行
        $todos = $this->todo->orderby('updated_at', 'desc')->paginate(5);
        // ↑ページネーション追加
        return view('todo.index', ['todos' => $todos]);
        // 取得したobjectをviewに渡す。第二引数はviewに渡したい変数
    }

    /**
     * Show the form for creating a new resource.
     * create function
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('todo.create');
        //viewの表示が行えるように
    }

    /**
     * Store a newly created resource in storage.
     * store function
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // storeメソッド=DBにデータを格納するためのメソッド
    public function store(Request $request)//Request $request=formタグで送信したPOST等の情報を取得
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);
        //第一引数に記載したルールでvalidationを行っている

        $this->todo->fill($validated)->save();
        //fillを使ってfillableホワイトリストにまとめて値の設定→save()メソッドを使ってDBへデータの保存

        return redirect()->route('todo.index');
        //保存完了後一覧画面に遷移させる
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * edit function
     * @param  int  $id
     * @return Response
     */
    public function edit(int $id)
    {
        $todo = $this->todo->findOrFail($id);
        // DBへ問い合わせを行っている。該当するデータがないときにExceptionがthrowされる
        //「Exceptionがthrow」=異常状態放置して実行するのではなく、異常が発生したら強制的に動作を終了させる→バグが発生しにくい◎
        return view('todo.edit', ['todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     * update function
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);
        $this->todo->findOrFail($id)->update($validated);
        return redirect()->route('todo.index');
    }

    /**
     * Remove the specified resource from storage.
     * destroy function
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $this->todo->findOrFail($id)->delete();
        //delete()メソッドで削除
        return redirect()->route('todo.index');
    }
}
