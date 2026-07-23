@extends('layouts.app')

@section('content')
<div class="vh-100 d-flex justify-content-center align-items-center">
    <div class="card w-25 position-absolute top-0 start-50 translate-middle-x">
        <form method="POST" class="needs-validation" action="/login/login" novalidate>
            @csrf
            <div class="card-header">ログイン</div>
            <div class="card-body">
                <div class="position-relative">
                    <label class="form-lavel">メールアドレス:</label>
                    <input type="text" name="loginEmail" class="form-control  @if($errors->has('loginEmail') || $errors->has('loginPassword')) is-invalid @endif" value="{{old('loginEmail')}}">

                    @if($errors->any())
                        <span class="text-danger">{{ $errors->first() }}</p>
                    @endif
                </div>

                <div>
                    <label class="form-label">Password:</label>
                    <input type="password" class="form-control" name="loginPassword" aria-describedby="validationServer01Feedback" required>
                </div>

                <div class="mt-2 d-flex justify-content-end">
                    <input class="btn btn-success" id="loginBtn" type="submit" value="ログイン">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
