<!DOCTYPE html>
<html>
<head>
    <title>Simple Laravel Package - Transaction fee calculator - By MSM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="card bg-light mt-3">
        <div class="card-header">
            Laravel 9 simple Transaction fee calculator
        </div>
        <div class="card-body">
            <form name="calcForm" id="calcForm" action="{{ route('calc') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file" class="form-control" required>
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                            <strong>Sorry!</strong> {{$error}}.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endforeach
                @endif
                <input type="hidden" name="mode" value="live">
                <br>
                <button type="submit" class="btn btn-success">Calculate Fees</button>
            </form>
            <table class="table table-responsive-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">Client ID</th>
                    <th scope="col">Client Type</th>
                    <th scope="col">Transaction Type</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Current</th>
                    <th scope="col">Commission Fee</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $tr)
                <tr>
                    <th scope="row">{{$loop->iteration}}</th>
                    <td>{{$tr->date}}</td>
                    <td>{{$tr->client_id}}</td>
                    <td>{{$tr->client_type}}</td>
                    <td>{{$tr->type}}</td>
                    <td>{{$tr->amount}}</td>
                    <td>{{$tr->currency}}</td>
                    <td>@if($tr->currency == 'JPY') {{ceil($tr->commission)}} @else {{$tr->commission}} @endif</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>
</html>
