<!DOCTYPE html>
<html lang="{{$app->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style>
        .label-default{
            border: 1px solid #ddd;
            background: none;
            color: #333;
            min-width: 30px;
            display: inline-block;
        }
    </style>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Areas</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <ul class="nav nav-tabs p-b">
        <li class="active">
            <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Area
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="{{ empty(request()->input('name')) ? 'active' : '' }}">
                        <a href="/">Any ({{$size}})</a>
                    </li>
                    @foreach ($areas as $name => $area)
                        <li class="{{ request()->input('name') == $name ? 'active' : '' }}">
                            <a href="?name={{$name}}">{{$name}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>
        <li>
            <div style="padding: 3px; margin-left: 15px">
                <span style="vertical-align: middle">
                     <strong>Current area: </strong>
                    @if (!empty($areas[request()->input('name')]))
                        {{request()->input('name')}}
                    @else
                        not selected
                    @endif
                </span>
            </div>
        </li>
    </ul>
    <table class="table ">
        <thead>
        <tr>
            <th>Area name</th>
            @if (empty($areas[request()->input('name')]))
                <th>Latitude</th>
                <th>Longitude</th>
            @else
                <th>Distance, kms</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($sortedAreas as $name => $area)
            <tr>
                <td>{{$name}}</td>
                @if (empty($areas[request()->input('name')]))
                    <td>{{$area['lat']}}</td>
                    <td>{{$area['long']}}</td>
                @else
                    <td>{{$area['distance']}}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-8">
            <nav>{{ $sortedAreas->links()}}</nav>
        </div>
        <div class="col-sm-4 pagination-counters">
            @if ($sortedAreas->lastPage() > 1)
                {{$sortedAreas->currentPage()}} to {{ $sortedAreas->lastPage() }}  of  {{$size}}
            @else
             {{ $size }}
            @endif
        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
<html>