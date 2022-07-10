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
                <button type="submit" class="btn btn-success">Import Transaction Data</button>
                <button type="button" class="btn btn-primary" onclick="DoSubmit()" >Use test data</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<script>
    function DoSubmit(){
        document.calcForm.mode.value = 'test';
        document.getElementById("calcForm").submit();
    }
</script>
</body>
</html>
