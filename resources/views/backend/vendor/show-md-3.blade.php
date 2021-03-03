<div class="card-box text-center" style="">
    
    <div class="background">
        <img src="{{$vendor->logo['proxy_url'] . '90/90' . $vendor->logo['image_path']}}" class="rounded-circle avatar-lg img-thumbnail"
            alt="profile-image">

        <h4 class="mb-0">{{ucfirst($vendor->name)}}</h4>
        <p class="text-muted">{{$vendor->address}}</p>

        <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light openEditModal"> Edit </button>
        <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light"> Block </button>
    </div>
    <div class="text-left mt-3">
        <h4 class="font-13">Description :</h4>
        <p class="text-muted font-13 mb-3">
           {{$vendor->desc}}
        </p>
        <p class="text-muted mb-2 font-13"><strong>Latitude :</strong> <span class="ml-2">{{$vendor->latitude}}</span></p>

        <p class="text-muted mb-2 font-13"><strong>Longitude :</strong><span class="ml-2">{{$vendor->longitude}}</span></p>

        <p class="text-muted mb-1 font-13"><strong>Status :</strong> <span class="ml-2">
            {{ ($vendor->status == 1) ? 'Active' : (($vendor->status == 2) ? 'Blocked' : 'Pending') }}
        </span></p>
    </div>

    <ul class="social-list list-inline mt-3 mb-0">
        <li class="list-inline-item">
            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                    class="mdi mdi-facebook"></i></a>
        </li>
        <li class="list-inline-item">
            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                    class="mdi mdi-google"></i></a>
        </li>
        <li class="list-inline-item">
            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                    class="mdi mdi-twitter"></i></a>
        </li>
        <li class="list-inline-item">
            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                    class="mdi mdi-github"></i></a>
        </li>
    </ul>
</div> <!-- end card-box -->

<div class="card-box">
    <h4 class="header-title mb-3">Users</h4>

    <div class="inbox-widget" data-simplebar style="max-height: 350px;">
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-2.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Tomaslau</p>
            <p class="inbox-item-text">I've finished it! See you so...</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Stillnotdavid</p>
            <p class="inbox-item-text">This theme is awesome!</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-4.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Kurafire</p>
            <p class="inbox-item-text">Nice to meet you</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>

        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-5.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Shahedk</p>
            <p class="inbox-item-text">Hey! there I'm available...</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-6.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Adhamdannaway</p>
            <p class="inbox-item-text">This theme is awesome!</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>

        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Stillnotdavid</p>
            <p class="inbox-item-text">This theme is awesome!</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-4.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Kurafire</p>
            <p class="inbox-item-text">Nice to meet you</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
    </div> <!-- end inbox-widget -->

</div>