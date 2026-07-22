<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Conferencia;
use App\Models\Evento;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class EventCatalog
{
    public const TYPE_LABELS = [
        'congreso' => 'Congreso',
        'jornada' => 'Jornada',
        'seminario' => 'Seminario',
        'conferencia' => 'Conferencia',
        'exposicion' => 'Exposición',
        'panel' => 'Panel',
        'clase' => 'Clase magistral',
        'presentacion' => 'Presentación',
        'taller' => 'Taller',
        'entrevista' => 'Entrevista',
        'otro' => 'Actividad',
    ];

    /**
     * @param  Collection<int, int>|null  $eventIds
     * @param  Collection<int, int>|null  $conferenceIds
     * @return Collection<int, array<string, mixed>>
     */
    public function all(
        ?Collection $eventIds = null,
        ?Collection $conferenceIds = null,
        bool $includeConferences = true,
    ): Collection {
        $today = now(config('app.timezone', 'America/Argentina/Buenos_Aires'))->startOfDay();
        $events = Evento::query()
            ->with('route')
            ->isPublished()
            ->when($eventIds !== null, fn ($query) => $query->whereIn('id', $eventIds))
            ->get()
            ->map(fn (Evento $event): array => $this->fromEvent($event, $today));

        $conferences = collect();
        if ($includeConferences) {
            $conferences = Conferencia::query()
                ->with('route')
                ->isPublished()
                ->when($conferenceIds !== null, fn ($query) => $query->whereIn('id', $conferenceIds))
                ->get()
                ->map(fn (Conferencia $conference): array => $this->fromConference($conference, $today));
        }

        return $events
            ->concat($conferences)
            ->unique('key')
            ->sort(function (array $left, array $right): int {
                if ($left['is_upcoming'] !== $right['is_upcoming']) {
                    return $left['is_upcoming'] ? -1 : 1;
                }

                $leftDate = $left['date'] ?? ($left['is_upcoming'] ? '9999-12-31' : '0000-00-00');
                $rightDate = $right['date'] ?? ($right['is_upcoming'] ? '9999-12-31' : '0000-00-00');

                return $left['is_upcoming']
                    ? strcmp($leftDate, $rightDate)
                    : strcmp($rightDate, $leftDate);
            })
            ->values();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $items
     * @param  array<int, string>  $types
     * @return Collection<int, array<string, mixed>>
     */
    public function filter(Collection $items, string $status = 'all', array $types = []): Collection
    {
        return $items
            ->when($status === 'upcoming', fn (Collection $records): Collection => $records->where('is_upcoming', true))
            ->when($status === 'past', fn (Collection $records): Collection => $records->where('is_upcoming', false))
            ->when($types !== [], fn (Collection $records): Collection => $records->whereIn('type', $types))
            ->values();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $items
     * @return Collection<int, array<string, mixed>>
     */
    public function featured(Collection $items, int $limit = 1): Collection
    {
        $dated = $items->filter(fn (array $item): bool => filled($item['date']));
        $upcoming = $dated->where('is_upcoming', true)->sortBy('date');

        return ($upcoming->isNotEmpty() ? $upcoming : $dated->where('is_upcoming', false)->sortByDesc('date'))
            ->take(max(1, $limit))
            ->values();
    }

    /** @param Collection<int, array<string, mixed>> $items */
    public function years(Collection $items): Collection
    {
        return $items->pluck('year')->filter()->unique()->sortDesc()->values();
    }

    /** @param Collection<int, array<string, mixed>> $items */
    public function countries(Collection $items): Collection
    {
        return $items->pluck('country')->filter()->unique()->sort(SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    /** @param Collection<int, array<string, mixed>> $items */
    public function types(Collection $items): Collection
    {
        return $items->pluck('type')->filter()->unique()->sortBy(
            fn (string $type): string => self::TYPE_LABELS[$type] ?? Str::headline($type)
        )->values();
    }

    /** @return array<string, mixed> */
    private function fromEvent(Evento $event, Carbon $today): array
    {
        return $this->item(
            key: "event:{$event->id}",
            title: $event->title,
            type: $event->tipo ?: 'otro',
            date: $event->fecha_inicio?->toDateString(),
            institution: $event->institucion,
            venue: $event->ubicacion,
            city: $event->ciudad,
            country: $event->pais,
            topic: $event->tema ?: $event->rol,
            summary: $event->descripcion,
            image: $event->imagen ?: $event->route?->image,
            url: $event->route ? $event->url : $event->enlace_inscripcion,
            external: ! $event->route && filled($event->enlace_inscripcion),
            today: $today,
        );
    }

    /** @return array<string, mixed> */
    private function fromConference(Conferencia $conference, Carbon $today): array
    {
        return $this->item(
            key: "conference:{$conference->id}",
            title: $conference->title,
            type: $conference->tipo ?: 'conferencia',
            date: $conference->fecha?->toDateString(),
            institution: $conference->institucion,
            venue: $conference->ubicacion,
            city: $conference->ciudad,
            country: $conference->pais,
            topic: $conference->tematica,
            summary: $conference->descripcion,
            image: $conference->imagen ?: $conference->route?->image,
            url: $conference->route ? $conference->url : $conference->external_url,
            external: ! $conference->route && filled($conference->external_url),
            today: $today,
        );
    }

    /** @return array<string, mixed> */
    private function item(
        string $key,
        string $title,
        string $type,
        ?string $date,
        ?string $institution,
        ?string $venue,
        ?string $city,
        ?string $country,
        ?string $topic,
        ?string $summary,
        ?string $image,
        ?string $url,
        bool $external,
        Carbon $today,
    ): array {
        $location = implode(' · ', array_filter([$venue, implode(', ', array_filter([$city, $country]))]));

        return [
            'key' => $key,
            'title' => $title,
            'type' => $type,
            'type_label' => self::TYPE_LABELS[$type] ?? Str::headline($type),
            'date' => $date,
            'year' => $date ? substr($date, 0, 4) : null,
            'institution' => $institution,
            'venue' => $venue,
            'city' => $city,
            'country' => $country,
            'location' => $location,
            'topic' => $topic,
            'summary' => Str::squish(strip_tags((string) $summary)),
            'image' => $image,
            'url' => $url,
            'external' => $external,
            'is_upcoming' => $date ? Carbon::parse($date, config('app.timezone'))->greaterThanOrEqualTo($today) : false,
        ];
    }
}
