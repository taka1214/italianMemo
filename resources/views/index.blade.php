<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-500 leading-tight">
      自分で作るイタリア語帳
    </h2>
  </x-slot>
  <div style="padding:10px; text-align: center;" class="text-gray-600">
  <div style="text-align: right; padding-right: 10px;">
    <button type="button" onclick="location.href='{{ route('post.create') }}'">新規登録</button>
  </div>
    @if(session('message'))
    <div id="postMessage">
      {{ session('message') }}
    </div>
    @endif
    <section class="text-gray-600 body-font">
      <div class="container ">
        <div class="w-full overflow-auto">
          <button style="border:1px solid;" type="button" onclick="location.href='{{ route('post.shuffle') }}'">伊→日</button>          
          <button style="border:1px solid;" type="button" onclick="location.href='{{ route('post.shuffleReverse') }}'">日→伊</button> 
          <div class="w-full mx-auto">
            @foreach($posts as $post)
            <div class="flex" onclick="location.href='{{ route('post.edit', ['post' => $post->id]) }}'">
              <!-- イタリア語 & 日本語の部分 -->
              <div class="" style="width: 80%; padding-top: 15px; text-align: left;">
                <div class="">
                  {{ Str::limit($post->italian, 40, '…' ) }}
                </div>
                <div class="">
                  {{ Str::limit($post->japanese, 37, '…' ) }}
                </div>
              </div>

              <!-- 音声ボタンの部分 -->
              <div class="flex items-center" style="width: 20%; text-align: right;" >
                @if($post->voice_script)
                <button onclick="playVoiceScript('{{ Storage::disk('s3')->url($post->voice_script) }}', this); event.stopPropagation();">音声</button>
                @else
                <span>音声なし</span>
                @endif
              </div>
            </div>
            @endforeach
          </div>
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

  let currentAudio = null; // 現在再生中のオーディオオブジェクトを保持するための変数

  function playVoiceScript(url, buttonElement) {
    if (currentAudio) {
      currentAudio.pause(); // すでに再生中の音声があれば、停止
      currentAudio = null;
      return;
    }

    const audio = new Audio(url);
    audio.play();

    audio.onended = function() {
      currentAudio = null;
    };

    currentAudio = audio;
  }
</script>