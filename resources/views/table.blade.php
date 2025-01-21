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
                <form method="GET" action="{{route('table')}}">
                    @csrf
                    <tr>
                        <th scope="col"><input value="{{ old('symbol', $filters['symbol'] ?? '') }}" name="symbol"></th>
                        <th scope="col"><input value="{{ old('name', $filters['name'] ?? '') }}" name="name"></th>
                        <th scope="col">from:<input value="{{ old('price_start', $filters['price_start'] ?? '') }}" name="price_start"><br>to:<input  value="{{ old('price_end', $filters['price_end'] ?? '') }}" name="price_end"></th>
                        <th scope="col">from:<input value="{{ old('volume_start', $filters['volume_start'] ?? '') }}" name="volume_start"><br>to:<input  value="{{ old('volume_end', $filters['volume_end'] ?? '') }}" name="volume_end"></th>
                        <th colspan="3"scope="col"><button type="submit" class="btn btn-primary">Apply Filter</button></th>
                    </tr>
                </form>
                @foreach($fin_data as $item)
                <tr>
                    <th scope="col">{{$item->symbol}}</th>
                    <th scope="col">{{$item->name}}</th>
                    <th scope="col">{{$item->financial_details['price']}}</th>
                    <th scope="col">{{$item->financial_details['volume']}}</th>
                    <th scope="col">{{$item->financial_details['eps']}}</th>
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