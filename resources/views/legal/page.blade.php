@extends('layouts.app')

@section('content')
    <section class="legal-page-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="legal-page-card shadow-sm p-4 bg-white rounded">
                        <h1 class="mb-4">{{ $pageTitle }}</h1>
                        <p class="lead text-secondary mb-4">{{ $pageHeading }}</p>

                        @foreach($pageContent as $paragraph)
                            <p class="mb-3">{{ $paragraph }}</p>
                        @endforeach

                        <div class="mt-5">
                            <a href="{{ route('shop') }}" class="btn btn-primary">
                                Continue Shopping
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-secondary ms-2">
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
