@extends('layouts.app')

@section('content')

<!-- Banner -->
<div class="mb-4">
    <img src="{{ asset('images/' . $challenge->image) }}"
         class="w-100 rounded" style="height:250px; object-fit:cover;">
</div>

<div class="container">
    <div class="row">

        <!-- LEFT: Progress -->
        <div class="col-md-4 text-center">
            <h5>Tiến trình</h5>

            <div class="progress-circle" id="progressCircle">
                <span id="progressText">0%</span>
            </div>
        </div>

        <!-- RIGHT: Task list -->
        <div class="col-md-8">
            <h5>Danh sách thử thách</h5>

            @foreach($challenge->tasks as $task)
                <div class="task-item card p-3 mb-2"
                     onclick="completeTask(this)">
                    {{ $task->title }}
                </div>
            @endforeach
        </div>

    </div>
</div>

@endsection
