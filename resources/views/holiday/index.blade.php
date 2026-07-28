@extends('layouts.api')

@section('title','祝日管理マスタ')

@section('active-master','active')
@section('active-holiday_master','active')

@section('content')
    @parent
   @if($holidays->isEmpty())
        <div class="alert alert-warning" role="alert">検索結果がありませんでした</div>
    @endif
    <div class="card">
        <div class="card-header">
            検索条件
        </div>
        <div class="card-body">
            <form action="/holiday/search" method="GET">
                @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label">日付:</label>
                            <input type="date" class="form-control" name="searchDate" >
                        </div>
                        <div class="col-4">
                            <label class="form-label">祝日名:</label>
                            <input type="text" class="form-control" name="searchName">
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <input type="submit" name="cBtn" class="btn btn-warning" value="クリア">
                        <input type="submit" name="searchBtn" class="btn btn-info" value="検索">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            検索結果
        </div>
        <div class="card-body">
            <div class="container">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <input type="button" class="btn btn-success" value="新規">
                    <input type="button" class="btn btn-primary" value="更新">
                    <input type="button" class="btn btn-danger " value="削除">
                </div>
                <table class="table mt-2">
                    <thead>
                        <tr class="table-dark">
                            <th>#</th>
                            <th scope="col">日付</th>
                            <th scope="col">祝日名</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($holidays as $holiday)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="radio" value="{{$holiday->id}}">
                                    </div>
                                </td>
                                <td>{{$holiday->yyyymmdd}}</td>
                                <td>{{$holiday->holiday_name}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
