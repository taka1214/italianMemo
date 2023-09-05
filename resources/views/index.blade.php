<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-500 leading-tight">
      自分で作るイタリア語帳
    </h2>
  </x-slot>
  <div style="padding:10px">
    <button type="button" onclick="location.href='{{ route('post.create') }}'">新規登録</button>
    @if(session('message'))
    <div id="postMessage">
      {{ session('message') }}
    </div>
    @endif
    <section class="text-gray-600 body-font">
      <div class="container px-5 py-24 mx-auto">
        <div class="lg:w-2/3 w-full mx-auto overflow-auto" style="padding:10px">
          <button type="button" onclick="location.href='{{ route('post.shuffle') }}'">伊→日</button>
          <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
              <tr>
                <th>イタリア語</th>
                <th>日本語</th>
              </tr>
            </thead>
            <tbody>
              @foreach($posts as $post)
              <tr onclick="location.href='{{ route('post.edit', ['post' => $post->id]) }}'">
                <td style="padding-top: 10px;">{{ Str::limit($post->italian, 40, '…' ) }}</td>
                <td style="padding-top: 10px;">{{ Str::limit($post->japanese, 40, '…' ) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</x-app-layout>

<script>
  // メッセージが存在する場合
  if (document.getElementById('postMessage')) {
    setTimeout(function() {
      document.getElementById('postMessage').style.display = 'none';
    }, 3000); // 3000ミリ秒後（3秒後）に実行
  }
</script>