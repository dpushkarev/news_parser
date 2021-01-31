@extends('news.layout')
@section('content')
    <h3>{{ $news->title }}</h3>
    <img src="{{ $news->image_url }}" alt="news">
    <p>{{ $news->description }}</p>
@endsection