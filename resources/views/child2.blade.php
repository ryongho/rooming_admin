<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'child2')

@section('sidebar')
    @parent
@endsection

@section('content')
    <p>child2</p>
@endsection