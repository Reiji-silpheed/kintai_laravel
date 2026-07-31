@extends('layouts.api')
@section('title','勤怠管理')
@section('active-kintai_master','active')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                検索条件
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-label">年月:</label>
                            <input type="month" class="form-control" name="searchMonth" v-model="searchMonth">
                        </div>
                        <div class="col-3">
                            <label class="form-label">ステータス:</label>
                            <select class="form-select">
                                <option value="0">一般</option>
                                <option value="1">管理者</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">社員番号:</label>
                            <input type="text" class="form-control" name="searchNumber" v-model="searchNumber">
                        </div>
                        <div class="col-3">
                            <label class="form-label">社員名:</label>
                            <input type="text" class="form-control" name="searchName" v-model="searchName">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                検索結果
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-success">承認</button>
                    <button type="button" class="btn btn-danger">差戻</button>
                    <button type="button" class="btn btn-light">Excel出力</button>
                    <button type="button" class="btn btn-light">PDF出力</button>
                </div>
                <table class="table mt-2">
                    <thead>
                        <tr class="table-dark">
                            <th>#</th>
                            <th scope="col">年月</th>
                            <th scope="col">社員番号</th>
                            <th scope="col">社員名</th>
                            <th scope="col">ステータス</th>
                            <th scope="col">確認</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
