<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-500">
      自分で作るイタリア語帳
    </h2>
  </x-slot>
  <div>
    <form method="post" action="{{ route('post.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="p-2 w-full">
        <div class="relative">
          <label for="italian" class="text-gray-600">イタリア語</label>
          <textarea id="italian" name="italian" rows="5" required>{{ old('italian') }}</textarea>
          <x-input-error :messages="$errors->get('italian')" class="mt-2" />
        </div>
        <div class="relative">
          <label for="japanese" class="text-gray-600">日本語</label>
          <textarea id="japanese" name="japanese" rows="5" required>{{ old('japanese') }}</textarea>
          <x-input-error :messages="$errors->get('japanese')" class="mt-2" />
        </div>
        <div class="relative">
          <label for="voice_script" class="text-gray-600">音声ファイル</label>
          <input type="file" id="voice_script" name="voice_script" accept=".mp3,.wav,.ogg">
          <x-input-error :messages="$errors->get('voice_script')" class="mt-2" />

          @if(isset($post) && $post->voice_script)
          <p>現在の音声:</p>
          <audio controls>
            <source src="{{ Storage::disk('s3')->url($post->voice_script) }}" type="audio/mp3">
            お使いのブラウザは音声タグをサポートしていません。
          </audio>
          @endif
        </div>
        <div class="relative">
          <input type="checkbox" id="enableMemo" onclick="toggleTextarea()" />
          <label for="memo" class="text-gray-600 mt-4">メモ</label>
          <textarea id="memo" name="memo" rows="5" required disabled>{{ old('memo') }}</textarea>
          <x-input-error :messages="$errors->get('memo')" class="mt-2" />
        </div>
      </div>
      <div>
        <button type="submit">登録</button><br>
        <button type="button" onclick="location.href='{{ route('post.index') }}'">一覧画面</button>
      </div>
    </form>
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>
</x-app-layout>

<script>
  function toggleTextarea() {
    const checkbox = document.getElementById('enableMemo');
    const textarea = document.getElementById('memo');

    textarea.disabled = !checkbox.checked;
  }
</script>