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
            <a href="{{route('main')}}" class="btn btn-info">Main</a>
        </div>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col">Symbol</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Volume</th>
                    <th scope="col">Eps</th>
                    <th scope="col">Open</th>
                    <th scope="col">Previous Close</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fin_data as $item)
                <tr>
                    <th scope="col">{{$item->symbol}}</th>
                    <th scope="col">{{$item->name}}</th>
                    <th scope="col">{{$item->price}}</th>
                    <th scope="col">{{$item->volume}}</th>
                    <th scope="col">{{$item->eps}}</th>
                    <th scope="col">{{$item->open}}</th>
                    <th scope="col">{{$item->previousClose}}</th>
                </tr>
                @endforeach
                <tr>
            </tbody>
            </table>
    </div>
  </body>
</html>