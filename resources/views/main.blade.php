<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-3">
    <div class="mt-3">
        <a href="{{route('table')}}" class="btn btn-info">Table</a>
    </div>
    @if(session()->has('message'))
        <div class="alert alert-success mt-3" role="alert">
            {{ session()->get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="post" class="mt-3" action="{{route('post_request')}}">
        @csrf
        <div class="mt-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="api" value="financial_data" id="flexRadioDefault1" @if($status == "financial_data") checked @endif>
                <label class="form-check-label" for="flexRadioDefault1">
                    Financial data for every need
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="api" value="rapid" id="flexRadioDefault2" @if($status == "rapid") checked @endif>
                <label class="form-check-label" for="flexRadioDefault2">
                        Rapid
                </label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Введіть код компанії</label>
            <input class="form-control" name="name">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    </div>
  </body>
</html>