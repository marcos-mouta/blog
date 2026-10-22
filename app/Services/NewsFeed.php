<?php

namespace App\Services;

use DOMDocument;
use DOMText;
use DOMXPath;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Busca as notícias da página https://grupoviamaquinas.com.br/news para a barra lateral.
 *
 * - O resultado fica em cache por 30 minutos (o site não é consultado a cada visita).
 * - Se o site estiver fora do ar ou mudar de formato, usa uma lista salva (fallback),
 *   e só tenta de novo depois de 10 minutos.
 */
class NewsFeed
{
    public const URL = 'https://grupoviamaquinas.com.br/news';

    private const HOSTS = ['grupoviamaquinas.com.br', 'www.grupoviamaquinas.com.br'];

    private const LIMIT = 8;

    /** @return array<int, array{title: string, summary: string, url: string}> */
    public static function latest(): array
    {
        // Nos testes automáticos nunca acessamos a internet.
        if (app()->environment('testing')) {
            return self::fallback();
        }

        try {
            if ($items = Cache::get('via-news')) {
                return $items;
            }

            if (Cache::has('via-news-indisponivel')) {
                return self::fallback();
            }

            $items = self::fetchRemote();

            if ($items) {
                Cache::put('via-news', $items, now()->addMinutes(30));

                return $items;
            }

            Cache::put('via-news-indisponivel', true, now()->addMinutes(10));
        } catch (\Throwable) {
            // Se o cache falhar por qualquer motivo, a página continua funcionando com a lista salva.
        }

        return self::fallback();
    }

    public static function fetchRemote(): array
    {
        try {
            $response = Http::timeout(4)
                ->withUserAgent('Mozilla/5.0 (compatible; BlogNoticias/1.0)')
                ->get(self::URL);

            return $response->successful() ? self::parse($response->body()) : [];
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Lê o HTML da página e devolve as notícias.
     *
     * Funciona assim: percorre o texto da página em ordem. Cada link "open_news?id=N"
     * fecha uma notícia, e os textos que vieram antes dele (depois do cabeçalho
     * "Notícias") são o título (primeiro texto) e o resumo (o restante).
     */
    public static function parse(string $html): array
    {
        if (trim($html) === '') {
            return [];
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query(
            '//a[contains(@href, "open_news")]'
            . ' | //text()[normalize-space()'
            . ' and not(ancestor::a[contains(@href, "open_news")])'
            . ' and not(ancestor::script) and not(ancestor::style) and not(ancestor::noscript)]'
        );

        $items = [];
        $seen = [];
        $buffer = [];

        foreach ($nodes as $node) {
            if ($node instanceof DOMText) {
                $text = trim(preg_replace('/\s+/u', ' ', $node->textContent));
                if ($text !== '') {
                    $buffer[] = $text;
                }

                continue;
            }

            $url = self::absoluteUrl($node->getAttribute('href'));
            if ($url === null) {
                continue;
            }

            $texts = self::afterHeading($buffer);
            $buffer = [];

            if ($texts === [] || isset($seen[$url])) {
                continue;
            }

            $seen[$url] = true;
            $items[] = [
                'title' => Str::limit($texts[0], 160),
                'summary' => Str::limit(implode(' ', array_slice($texts, 1)), 220),
                'url' => $url,
            ];

            if (count($items) >= self::LIMIT) {
                break;
            }
        }

        return $items;
    }

    /** Lista salva, usada quando o site não responde. */
    public static function fallback(): array
    {
        $base = 'https://grupoviamaquinas.com.br/news/open_news?id=';

        return [
            [
                'title' => 'O balcão vai ser digital',
                'summary' => 'Em tratativas para lançamento já no próximo semestre, uma plataforma de negociações inteiramente digital vai revolucionar o agronegócio.',
                'url' => $base . '5',
            ],
            [
                'title' => 'Leilão de trator CBT 8440 arrecada dinheiro para equipar duas escolas rurais',
                'summary' => 'A campanha Via Pelo Futuro promove arrecadação de doações em dinheiro, que serão destinadas à compra de equipamentos de informática para escolas rurais municipais.',
                'url' => $base . '4',
            ],
            [
                'title' => 'Quatro passos para fugir de golpes na hora da compra de máquinas usadas',
                'summary' => 'Matéria publicada na A Granja Total Agro',
                'url' => $base . '3',
            ],
            [
                'title' => 'App para falar com astronautas leva Via Máquinas à final de concurso da Nasa',
                'summary' => 'Grupo Via Máquinas se destaca em competição da NASA',
                'url' => $base . '2',
            ],
            [
                'title' => 'Grupo Via Máquinas comemora 11 anos',
                'summary' => '',
                'url' => $base . '1',
            ],
        ];
    }

    /** Fica só com os textos que vêm depois do último cabeçalho "Notícias". */
    private static function afterHeading(array $texts): array
    {
        $last = null;
        foreach ($texts as $i => $text) {
            if (mb_strtolower($text) === 'notícias') {
                $last = $i;
            }
        }

        return $last === null ? $texts : array_values(array_slice($texts, $last + 1));
    }

    /** Aceita apenas links do próprio site (evita "javascript:" e sites de terceiros). */
    private static function absoluteUrl(string $href): ?string
    {
        $href = trim($href);

        if ($href === '') {
            return null;
        }

        if (str_starts_with($href, '//')) {
            $href = 'https:' . $href;
        } elseif (str_starts_with($href, '/')) {
            $href = 'https://grupoviamaquinas.com.br' . $href;
        } elseif (! preg_match('#^https?://#i', $href)) {
            $href = 'https://grupoviamaquinas.com.br/news/' . ltrim($href, '/');
        }

        $host = parse_url($href, PHP_URL_HOST);

        return in_array($host, self::HOSTS, true) ? $href : null;
    }
}
