<!DOCTYPE html>
<html>
    <head>
        <title>学生表示画面</title>
    </head>
    <body>
    @extends('layouts.app')
    @section('content')
    <h1>学生表示画面</h1>
    <!-- 削除成功 -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    
    <form method="POST" action="{{ route('seito.gakuseihyouji_post') }}" name="shosaimain">
        @csrf
        <!-- 学年と名前で検索する -->
        <select name="grade">
            <option value="学生を選択してください">
            <option value="1" {{ request('grade') =='1' ? 'selected' : '' }}>1年生</option>
            <option value="2" {{ request('grade') =='2' ? 'selected' : '' }}>2年生</option>
            <option value="3" {{ request('grade') =='3' ? 'selected' : '' }}>3年生</option>
            <option value="4" {{ request('grade') =='4' ? 'selected' : '' }}>4年生</option>
            <option value="5" {{ request('grade') =='5' ? 'selected' : '' }}>5年生</option>
            <option value="6" {{ request('grade') =='6' ? 'selected' : '' }}>6年生</option>

            
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="名前で検索する">
        <button type="submit" class="search-button">検索</button>
    </form>
    <!-- formはデータ送信部分まで必要、表示だけであれば必要ない -->
    @if($students->count())
    <!-- ↑検索の際に表示件数をカウント -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>学年</th>
                    <th>名前</th>
                    <th>アクション</th>
                </tr>
    
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                        <td>{{ $student->grade }}</td>
                        <td>{{ $student->name }}</td>
                        <td>
                        <a class="btn btn-primary" href="{{ route('seito.Shosaikojin',$student->id) }}">詳細</a>
                        </td>
                </tr>
                @endforeach
    
            </tbody>
        </table>
        {{ $students->links() }}
        @else
        <p>検索結果がありません</p>
        @endif

    </div>  
    



    <!-- 変更無し↓ -->
    <form method="GET">  <!-- GETメソッドを使用 -->
        @csrf
        <button type="submit" formaction="{{ url('seito/mainmenu') }}">戻る</button>  <!-- メインメニューへのリンク -->
    </form>

    @endsection

    </body>
</html>