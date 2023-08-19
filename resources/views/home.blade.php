@include('layouts.header')

<div class="container text-center mt-5">
    <h2 class="mb-4">Your Page, {{ $user->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary mb-2" id="copy-link">Copy Your Unique Link</button>
    <a href="{{ route('generate_link', ['token' => $user->access_token]) }}" class="btn btn-success mb-2">Generate New Unique Link</a>
    <a href="{{ route('deactivate_link', ['token' => $user->access_token]) }}" class="btn btn-danger mb-2">Deactivate Current Link</a>
    <a href="#" class="btn btn-warning mb-2" id="feeling-lucky">I'm Feeling Lucky</a>
    <a href="#" class="btn btn-secondary mb-2" id="view-history">History</a>

    <div id="feeling-lucky-result" class="mt-4">
        <p><em>Random Number:</em> <strong><span id="random-number"></span></strong></p>
        <p><em>Result:</em> <strong><span id="result"></span></strong></p>
        <p><em>Win Amount:</em> <strong><span id="win-amount"></span></strong></p>
    </div>
    <div id="history" class="mt-4 border p-3">
        <h4>Your History:</h4>
        <ul id="history-list"></ul>
    </div>
</div>

@section('ajax')
<script>
    $(document).ready(function () {
        $('#feeling-lucky').click(function () {
            $.ajax({
                url: "{{ route('home',  ['token' => $user->access_token]) }}",
                type: "GET",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (data) {
                    console.log(data);
                    document.getElementById('random-number').textContent = data.random_number;
                    document.getElementById('result').textContent = data.result;
                    document.getElementById('win-amount').textContent = data.win_amount.toFixed(2);
                },
                dataType: "json",
            });
        });
    });

    $('#view-history').click(function () {
        $.ajax({
            url: "{{ route('get_history', ['token' => $user->access_token]) }}",
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                var historyList = $('#history-list');
                historyList.empty();

                if (data.length > 0) {
                    data.reverse();
                    data.forEach(function (item) {
                        historyList.append('<li style="text-decoration: underline;"><em>Random Number: <strong>' +
                            item.random_number + '</strong>, Result: <strong>' +
                            item.result + '</strong>, Win Amount: <strong>' +
                            item.win_amount + '</strong></li></em>');
                    });
                } else {
                    historyList.prepend('<li>You have no history yet.</li>');
                }
            },
            dataType: "json",
        });
    });
</script>
@endsection

@include('layouts.footer')
