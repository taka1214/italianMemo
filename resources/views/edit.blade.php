<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-500">
      イタリア語帳の編集
    </h2>
  </x-slot>
  <div class="py-12">
    <form method="post" action="{{ route('post.update', $post->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="p-2 w-full">
        <div class="relative">
          <label for="italian" class="text-gray-600">イタリア語</label>
          <textarea id="italian" name="italian" rows="5" required>{{ old('italian', $post->italian) }}</textarea>
          <x-input-error :messages="$errors->get('italian')" class="mt-2" />
        </div>
        <div class="relative">
          <label for="japanese" class="text-gray-600">日本語</label>
          <textarea id="japanese" name="japanese" rows="5" required>{{ old('japanese', $post->japanese) }}</textarea>
          <x-input-error :messages="$errors->get('japanese')" class="mt-2" />
        </div>
        <div class="relative">
          <label for="voice_script" class="text-gray-600">音声ファイル</label>

          @if($post->voice_script)
          <p>現在の音声:
            <audio controls>
              <source src="{{ Storage::disk('s3')->url($post->voice_script) }}" type="audio/mp3">
              お使いのブラウザは音声タグをサポートしていません。
            </audio>
          </p>
          @endif

          <input type="file" id="voice_script" name="voice_script" accept="audio/*">
          <x-input-error :messages="$errors->get('voice_script')" class="mt-2" />
        </div>
        <div class="relative">
          @if(empty($post->memo))
          <!-- メモが空の場合、チェックボックスを表示 -->
          <!-- <label for="enableMemo" class="text-gray-600">メモを有効にする</label> -->
          <input type="checkbox" id="enableMemo" onclick="toggleTextarea()" />

          <label for="memo" class="text-gray-600 mt-4">メモ</label>
          <textarea id="memo" name="memo" rows="5" required disabled>{{ old('memo', $post->memo) }}</textarea>
          @else
          <!-- メモに何か値がある場合、通常のテキストエリアを表示 -->
          <label for="memo" class="text-gray-600">メモ</label>
          <textarea id="memo" name="memo" rows="5" required>{{ old('memo', $post->memo) }}</textarea>
          @endif
          <x-input-error :messages="$errors->get('memo')" class="mt-2" />
        </div>
      </div>
      <button type="submit">更新</button>
    </form>
    <form id="deleteForm" action="{{ route('post.destroy', $post->id) }}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" onclick="return deletePost();" style="background-color: red; color: white;">削除</button>
    </form>
    <button type="button" onclick="location.href='{{ route('post.index') }}'">一覧画面</button>
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
</script>