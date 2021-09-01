@extends('layouts.store', ['title' => 'Success'])
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store.left-sidebar')
</header>
<section class="section-b-space light-layout">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5 mb-5">
                <div class="success-text">
                	<i class="fa fa-check-circle" aria-hidden="true"></i>
                    <h2>{{__('Thank You')}}</h2>
                    <p>{{__('Payment is successfully processed')}}</p>
                    <p><a href="{{ route('user.orders') }}">{{__('View Order')}}</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection