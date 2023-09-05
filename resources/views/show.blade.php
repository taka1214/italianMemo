<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-500 leading-tight">
      イタリア語の詳細
    </h2>
  </x-slot>

  <div>
    <div class="container mx-auto">
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="font-semibold text-gray-500 flex items-center justify-center">イタリア語の音声</h3>
        <div>
          <p>音声再生</p>
          <audio controls>
            <source src="{{ Storage::disk('s3')->url($post->voice_script) }}" type="audio/mp3">
            お使いのブラウザは音声タグをサポートしていません。
          </audio>
        </div>
        <div style="padding-top: 10px;">
          <div class="font-semibold" onclick="toggleVisibility('italianContent', 'italianToggleIcon')">audio script<span id="italianToggleIcon">▼</span></div>
          <div id="italianContent" style="display:none;">
            &nbsp;&nbsp;{{ $post->italian }}
            @if(!empty($post->memo))
            <span id="italianMemoNote" style="display:block;">&nbsp;&nbsp;※メモあり</span>
            @endif
          </div>
        </div>
        <div style="padding-top: 10px;">
          <div class="font-semibold" onclick="toggleVisibility('japaneseContent', 'japaneseToggleIcon')">日本語<span id="japaneseToggleIcon">▼</span></div>
          <p id="japaneseContent" style="display:none;">&nbsp;&nbsp;{{ $post->japanese }}</p>
          @if(!empty($post->memo))
          <p id="japaneseMemoContent" style="display:none;">&nbsp;&nbsp;※メモ{{ $post->memo }}</p>
          @endif
        </div>
        <button type="button" style="padding-top: 10px;" onclick="location.href='{{ route('post.shuffle') }}'">次へ</button>
      </div>
    </div>
    <button type="button" onclick="location.href='{{ route('post.index') }}'">一覧画面</button>
  </div>
</x-app-layout>

<script>
  function toggleVisibility(contentElementId, toggleIconId) {
    let contentElement = document.getElementById(contentElementId);
    let toggleIcon = document.getElementById(toggleIconId);

    // Check for a possible memo note
    let memoNoteElementId = contentElementId + "MemoNote";
    let memoNoteElement = document.getElementById(memoNoteElementId);

    // Check for a possible memo content
    let memoContentElementId = contentElementId.replace("Content", "MemoContent");
    let memoContentElement = document.getElementById(memoContentElementId);

    if (contentElement.style.display === "none") {
      contentElement.style.display = "block";
      toggleIcon.innerHTML = "▲";

      if (memoNoteElement) {
        memoNoteElement.style.display = "block";
      }

      if (memoContentElement) {
        memoContentElement.style.display = "block";
      }
    } else {
      contentElement.style.display = "none";
      toggleIcon.innerHTML = "▼";

      if (memoNoteElement) {
        memoNoteElement.style.display = "none";
      }

      if (memoContentElement) {
        memoContentElement.style.display = "none";
      }
    }
  }
</script>