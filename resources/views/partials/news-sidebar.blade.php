@php
    $news = \App\Services\NewsFeed::latest();
    $duration = max(count($news), 1) * 9; // segundos para percorrer a lista: quanto maior, mais devagar
@endphp

<section class="news-panel" aria-labelledby="news-title">
    <div class="news-head">
        <h2 id="news-title">Notícias</h2>
        <a href="https://grupoviamaquinas.com.br/news" target="_blank" rel="noopener noreferrer">Ver todas</a>
    </div>
    <p class="news-source">Grupo Via Máquinas</p>

    <div class="news-viewport">
        <div class="news-track" style="--dur: {{ $duration }}s">
            {{-- A lista aparece 3 vezes para a rolagem ficar contínua; as cópias ficam escondidas de leitores de tela. --}}
            @foreach ([0, 1, 2] as $copy)
                <div class="news-list" @if ($copy > 0) aria-hidden="true" @endif>
                    @foreach ($news as $item)
                        <article class="news-item">
                            <h3>{{ $item['title'] }}</h3>
                            @if ($item['summary'] !== '')
                                <p>{{ $item['summary'] }}</p>
                            @endif
                            <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer"
                               @if ($copy > 0) tabindex="-1" @endif>Saiba mais</a>
                        </article>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
