@extends('news.layout')
@section('content')
    <div class="list-group">
        @foreach($news as $item)
            <a href="{{ $item->by_partner ? $item->url : route('news.detail', ['id' => $item->id]) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $item->title }}</h5>
                    <small></small>
                </div>
                <p class="mb-1">
                    {{ Str::limit(html_entity_decode($item->description)) }}
                </p>
                <small>Подробнее</small>
            </a>
        @endforeach
    </div>
@endsection