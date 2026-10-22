@csrf
<div class="field">
    <label for="title">Título</label>
    <input type="text" id="title" name="title" value="{{ old('title', $post->title ?? '') }}" required>
    @error('title')<div class="error">{{ $message }}</div>@enderror
</div>

<div class="field">
    <label for="body">Texto</label>
    <textarea id="body" name="body" rows="12" required>{{ old('body', $post->body ?? '') }}</textarea>
    @error('body')<div class="error">{{ $message }}</div>@enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn">{{ $submit ?? 'Salvar' }}</button>
    <a class="btn btn-ghost" href="{{ isset($post) ? route('posts.show', $post) : route('posts.index') }}">Cancelar</a>
</div>
