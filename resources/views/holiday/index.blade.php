@extends('layouts.api')

@section('title','祝日管理マスタ')

@section('active-master','active')
@section('active-holiday_master','active')

@section('content')
    @parent
    @if($holidays->isEmpty())
        <div class="alert alert-warning" role="alert">検索結果がありませんでした。</div>
    @endif
    @if($newAlert)
        <div class="alert alert-success" role="alert">新規登録処理がされました。</div>
    @endif
    @if($updateAlert)
        <div class="alert alert-primary" role="alert">更新処理がされました。</div>
    @endif
    @if($deleteAlert)
        <div class="alert alert-danger" role="alert">削除処理がされました。</div>
    @endif
    <div class="container">
        <div class="card">
            <div class="card-header">
                検索条件
            </div>
            <div class="card-body">
                <form action="{{url('/holiday/form')}}" method="GET">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">日付:</label>
                                <input type="date" class="form-control" name="searchDate" value="{{$searchDate}}">
                            </div>
                            <div class="col-4">
                                <label class="form-label">祝日名:</label>
                                <input type="text" class="form-control" name="searchName" value="{{$searchName}}">
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-warning" name="action" value="clear">クリア</button>
                            <button type="submit" class="btn btn-info" name="action" value="search">検索</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                検索結果
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <input type="button" id="newBtn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newModal" value="新規">
                        <input type="button" id="updateBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal" value="更新" disabled>
                        <input type="button" id="deleteBtn" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" value="削除" disabled>
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
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link @if($active==1) disabled @endif" href="/holiday/page_front">前</a></li>
                            @for($i=1;$i<=$pageNumber;$i++)
                                <li class="page-item"><a class="page-link @if($active==$i) active @endif" href="{{route('holiday/page',['page'=>$i])}}">{{$i}}</a></li>
                            @endfor
                            <li class="page-item"><a class="page-link @if($active==$pageNumber || $pageNumber==0) disabled @endif" href="/holiday/page_next">次</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- 新規モーダル --}}
    @if($errors->has('newDate') || $errors->has('newName'))
        <script>
            $(function(){
                $('#newModal').modal('show');
            })
        </script>
    @endif
    <div class="modal fade modal-xl" id="newModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h1 class="modal-title fs-5 text-white">祝日登録</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <form action="/holiday/add" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    祝日情報
                                </div>
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-4">
                                                <label class="form-label">日付:</label>
                                                <input type="date" class="form-control @error('newDate') is-invalid @enderror" name="newDate" value="{{old('newDate')}}">
                                                @error('newDate')
                                                    <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">祝日名:</label>
                                                <input type="text" class="form-control @error('newName') is-invalid @enderror" name="newName" value="{{old('newName')}}">
                                                @error('newName')
                                                    <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-success">登録</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {{-- 更新モーダル --}}
    @if($errors->has('updateDate') || $errors->has('updateName'))
        <script>
            $(function(){
                $("#updateModal").modal('show');
            })
        </script>
    @endif
    <div class="modal fade modal-xl" id="updateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-info">
                <h1 class="modal-title fs-5 text-white">祝日更新</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <form action="/holiday/edit" method="POST">
                @csrf
                <div class="container">
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                祝日情報
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label">日付:</label>
                                            <input type="date" class="form-control @error('updateDate') is-invalid @enderror" id="updateDate" name="updateDate">
                                            @error('updateDate')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label">祝日名:</label>
                                            <input type="text" class="form-control @error('updateName') is-invalid @enderror" id="updateName" name="updateName">
                                            @error('updateName')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <input type="hidden" id="updateID" name="updateID">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-primary">更新</button>
                    </div><!-- /.modal-footer -->
                </div>
            </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {{-- 削除モーダル --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h1 class="modal-title fs-5 text-white">祝日削除</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <form action="/holiday/delete" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>選択した祝日は削除しますか？</p>
                        <input type="hidden" id="deleteID" name="deleteID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-danger">削除</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        $(function(){
            $(document).on("change","input[name='radio']",function(){
                if($("input[name='radio']:checked").length===0){
                    $("#updateBtn").prop("disabled",true);
                    $("#deleteBtn").prop("disabled",true);
                }
                else{
                    $("#updateBtn").prop("disabled",false);
                    $("#deleteBtn").prop("disabled",false);
                }
            })
            $(document).on("click","#updateBtn",function(){
                var select=$("input[name='radio']:checked");
                var row=select.closest("tr");
                var id=row.find("td").eq(0).find("input").val();
                $("#updateID").val(id);
                var date=row.find("td").eq(1).text();
                $("#updateDate").val(date);
                var name=row.find("td").eq(2).text();
                $("#updateName").val(name);
            })
            $(document).on("click","#deleteBtn",function(){
                var select=$("input[name='radio']:checked");
                var row=select.closest("tr");
                var id=row.find("td").eq(0).find("input").val();
                $("#deleteID").val(id);
            })
        })
    </script>
@endsection
