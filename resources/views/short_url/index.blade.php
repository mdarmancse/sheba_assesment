@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Short URL Generator</h1>
        <form id="shortUrlForm" action="{{ route('short-url.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="long_url">Enter Long URL</label>
                <input type="url" class="form-control" id="long_url" name="long_url" placeholder="https://example.com" value="{{ old('long_url') }}" required>
                <div id="urlTestResult" class="mt-2"></div>
            </div>
            <button type="submit" class="btn btn-primary">Generate Short URL</button>
        </form>

        <hr class="my-4">



        @if(session('success'))
            <div class="alert alert-success mt-3">
                Short URL: <a href="{{ session('success') }}" target="_blank">{{ session('success') }}</a>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        const urlInput = document.getElementById('long_url');
        const urlTestResult = document.getElementById('urlTestResult');
        const urlPattern = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/;

        const validateURL = () => {
            if (!urlInput.value) {
                urlTestResult.innerHTML = `<div class="text-danger">URL field cannot be empty.</div>`;
                return;
            }

            if (urlPattern.test(urlInput.value)) {
                urlTestResult.innerHTML = `<div class="text-success">Valid URL!</div>`;
            } else {
                urlTestResult.innerHTML = `<div class="text-danger">Invalid URL! Please enter a correct URL.</div>`;
            }
        };


        urlInput.addEventListener('keypress', validateURL);
        urlInput.addEventListener('keydown', validateURL);
        urlInput.addEventListener('change', validateURL);
    </script>
@endsection
