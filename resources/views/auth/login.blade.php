@extends('layouts.app')

@section('title', 'Login — FalaQ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm p-4">
            <h4 class="fw-bold mb-3">🔐 Entrar</h4>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="form-control bg-dark text-white border-secondary">
                    @error('email') <div class="text-danger mt-1 fw-bold">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-secondary">Senha</label>
                    <input type="password" name="password" id="password"
                           class="form-control bg-dark text-white border-secondary">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Entrar</button>
            </form>
        </div>
    </div>
</div>
@endsection
