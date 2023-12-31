<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-500">
      イタリア語帳の編集
    </h2>
  </x-slot>
  <div style="padding-left: 5px; padding-top:10px;">
    <form method="post" action="{{ route('post.update', $post->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div style="text-align: center">
        <div>
          <!-- <label for="italian" class="text-gray-600">イタリア語</label> -->
          <textarea id="italian" style="padding: 5px;" name="italian" cols="35" rows="5" required>{{ old('italian', $post->italian) }}</textarea>
          <x-input-error :messages="$errors->get('italian')" class="mt-2" />
        </div>
        <div>
          <!-- <label for="japanese" class="text-gray-600">日本語</label> -->
          <textarea id="japanese" style="padding: 5px;" name="japanese" cols="35" rows="5" required>{{ old('japanese', $post->japanese) }}</textarea>
          <x-input-error :messages="$errors->get('japanese')" class="mt-2" />
        </div>
        <div style="padding-left: 8%; text-align: left;">
          @if($post->voice_script)
          <p>
            <button type="button" onclick="playVoiceScript('{{ Storage::disk('s3')->url($post->voice_script) }}', this);">音声再生</button>
            @else
            <span>音声なし</span>
          </p>
          @endif
          <input type="file" id="voice_script" name="voice_script">
          <x-input-error :messages="$errors->get('voice_script')" class="mt-2" />
        </div>
        <div class="mt-4" style="padding-left: 8%; text-align: left;">
          @if(empty($post->memo))
          <!-- メモが空の場合、チェックボックスを表示 -->
          <div>
            <!-- <label for="enableMemo" class="text-gray-600">メモを有効にする</label> -->
            <input type="checkbox" id="enableMemo" onclick="toggleTextarea()" />
            <label for="memo" class="text-gray-600 mt-4">メモ</label>
          </div>
          <textarea id="memo" style="padding: 5px;" name="memo" cols="35" rows="5" required disabled>{{ old('memo', $post->memo) }}</textarea>
          @else
          <div>
            <!-- メモに何か値がある場合、通常のテキストエリアを表示 -->
            <label for="memo" class="text-gray-600 m1-2">メモ</label>
          </div>
          <textarea id="memo" style="padding: 5px;" name="memo" cols="35" rows="5" required>{{ old('memo', $post->memo) }}</textarea>
          @endif
          <x-input-error :messages="$errors->get('memo')" class="mt-2" />
        </div>
      </div>
      <button type="submit" style="padding-top: 10px; padding-left: 8%;">更新</button>
    </form>
    <form id="deleteForm" style="padding-top: 10px; padding-left: 8%;" action="{{ route('post.destroy', $post->id) }}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" onclick="return deletePost();" style="background-color: red; color: white;">削除</button>
    </form>
    <div style="text-align: center; padding-bottom: 10%;">
      <button type="button" style="padding-top: 10px;" onclick="location.href='{{ route('post.index') }}'">一覧画面</button>
    </div>
  </div>
</x-app-layout>

<script>
  function deletePost() {
    'use strict';
    return confirm('Are you sure?');
  }

  function toggleTextarea() {
    const checkbox = document.getElementById('enableMemo');
    const textarea = document.getElementById('memo');

    textarea.disabled = !checkbox.checked;
  }

  let currentAudio = null; // 現在再生中のオーディオオブジェクトを保持するための変数

function playVoiceScript(url, buttonElement) {
  if (currentAudio) {
    currentAudio.pause(); // すでに再生中の音声があれば、停止
    buttonElement.textContent = "現在の音声"; // ボタンのラベルを「現在の音声」に変更
    currentAudio = null;
    return;
  }

  const audio = new Audio(url);
  audio.play();

  buttonElement.textContent = "停止"; // ボタンのラベルを「停止」に変更

  audio.onended = function() {
    buttonElement.textContent = "現在の音声"; // 音声が終了したら、ボタンのラベルを「現在の音声」に変更
    currentAudio = null;
  };

  currentAudio = audio;
}

</script>