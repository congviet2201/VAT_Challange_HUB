<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h3>Dashboard</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@foreach($userChallenges as $uc)

    <div class="card mb-3 p-3">

        <h5>Challenge ID: {{ $uc->challenge_id }}</h5>

        <!-- Progress -->
        <div class="progress mb-2">
            <div class="progress-bar" style="width: {{ $uc->progress }}%">
                {{ round($uc->progress) }}%
            </div>
        </div>

        <p> Streak: {{ $uc->streak }} ngày</p>
        <p> Hoàn thành: {{ $uc->completed_days }} ngày</p>

        <!-- Check-in -->
        <form method="POST" action="/checkin">
            @csrf
            <input type="hidden" name="challenge_id" value="{{ $uc->challenge_id }}">
            <button class="btn btn-success">Check-in hôm nay</button>
        </form>

    </div>

@endforeach

</body>
</html>
