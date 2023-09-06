<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-500">
      自分で作るイタリア語帳
    </h2>
  </x-slot>
  <div style="padding-left: 5px; padding-top:10px;">
    <form method="post" action="{{ route('post.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="p-2 w-full">
        <div>
          <!-- <label for="italian" class="text-gray-600">イタリア語</label> -->
          <textarea id="italian" style="padding: 5px;" name="italian" rows="5" required placeholder="Italiano">{{ old('italian') }}</textarea>
          <x-input-error :messages="$errors->get('italian')" class="mt-2" />
        </div>
        <div>
          <!-- <label for="japanese" class="text-gray-600">日本語</label> -->
          <textarea id="japanese" style="padding: 5px;" name="japanese" rows="5" required placeholder="日本語">{{ old('japanese') }}</textarea>
          <x-input-error :messages="$errors->get('japanese')" class="mt-2" />
        </div>
        <div>
          <label for="voice_script" class="text-gray-600">音声ファイル</label>
          <input type="file" id="voice_script" name="voice_script" accept=".mp3,.wav,.ogg,.m4a">
          <x-input-error :messages="$errors->get('voice_script')" class="mt-2" />
          @if(isset($post) && $post->voice_script)
          <p>現在の音声:</p>
          <audio controls>
            <source src="{{ Storage::disk('s3')->url($post->voice_script) }}" type="audio/mp3">
            お使いのブラウザは音声タグをサポートしていません。
          </audio>
          @endif
        </div>
        <div class="mt-4">
          <div>
            <label for="memo" class="text-gray-600 m1-2">メモ</label>
            <input type="checkbox" id="enableMemo" onclick="toggleTextarea()" />
          </div>
          <textarea id="memo" style="padding: 5px;" name="memo" rows="5" required disabled>{{ old('memo') }}</textarea>
          <x-input-error :messages="$errors->get('memo')" class="mt-2" />
        </div>
      </div>
      <div style="padding-top: 10px;">
        <button type="submit">登録</button><br>
        <button type="button" style="padding-top: 5px;" onclick="location.href='{{ route('post.index') }}'">一覧画面</button>
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