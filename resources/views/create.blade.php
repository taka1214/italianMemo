<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-500">
      自分で作るイタリア語帳
    </h2>
  </x-slot>
  <div style="padding-top:10px; ">
    <form method="post" action="{{ route('post.store') }}" enctype="multipart/form-data">
      @csrf
      <div style="text-align: center;">
        <div>
          <!-- <label for="italian" class="text-gray-600">イタリア語</label> -->
          <textarea id="italian" style="padding: 5px;" name="italian" rows="5" cols="35" required placeholder="Italiano">{{ old('italian') }}</textarea>
          <x-input-error :messages="$errors->get('italian')" class="mt-2" />
        </div>
        <div>
          <!-- <label for="japanese" class="text-gray-600">日本語</label> -->
          <textarea id="japanese" style="padding: 5px;" name="japanese" rows="5" cols="35" required placeholder="日本語">{{ old('japanese') }}</textarea>
          <x-input-error :messages="$errors->get('japanese')" class="mt-2" />
        </div>
        <div style="padding-left: 8%; text-align: left;">
          <!-- <label for="voice_script" class="text-gray-600">音声ファイル</label> -->
          <input type="file" id="voice_script" name="voice_script">
          <x-input-error :messages="$errors->get('voice_script')" class="mt-2" />
        </div>
        <div class="mt-4">
          <span class="text-gray-600">カテゴリ:</span>
          <div>
            <label class="inline-flex items-center">
              <input type="radio" name="category" value="spreadsheet" {{ old('category') == 'spreadsheet' ? 'checked' : '' }}>
              <span class="ml-1 mr-2">Spreadsheet</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="category" value="kentei" {{ old('category') == 'kentei' ? 'checked' : '' }}>
              <span class="ml-1">検定</span>
            </label>
          </div>
          <x-input-error :messages="$errors->get('category')" class="mt-2" />
        </div>
        <div class="mt-4" style="padding-left: 8%; text-align: left;">
          <div>
            <label for="memo" class="text-gray-600 m1-2">メモ</label>
            <input type="checkbox" id="enableMemo" onclick="toggleTextarea()" />
          </div>
          <textarea id="memo" name="memo" rows="10" cols="35" required disabled>{{ old('memo') }}</textarea>
          <x-input-error :messages="$errors->get('memo')" class="mt-2" />
        </div>
      </div>
      <div style="padding-top: 10px;">
        <button type="submit" style="padding-left: 8%;">登録</button><br>
        <div style="text-align: center; padding-bottom: 10%;">
          <button type="button" style="padding-top: 5px;" onclick="location.href='{{ route('post.index') }}'">一覧画面</button>
        </div>
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