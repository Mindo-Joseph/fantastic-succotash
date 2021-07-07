<div class="dashboard-left">
    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left"
                aria-hidden="true"></i> back</span></div>
    <div class="block-content">
        <ul>
            <li class="{{ (request()->is('user/profile')) ? 'active' : '' }}"><a href="{{route('user.profile')}}">Account Info</a></li>
            <li class="{{ (request()->is('user/addressBook')) ? 'active' : '' }}"><a href="{{route('user.addressBook')}}">Address Book</a></li>
            <li class="{{ (request()->is('user/orders*')) ? 'active' : '' }}"><a href="{{route('user.orders')}}">My Orders</a></li>
            <li class="{{ (request()->is('user/wishlists')) ? 'active' : '' }}"><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
            <li class="{{ (request()->is('user/wallet')) ? 'active' : '' }}"><a href="{{route('user.wallet')}}">My Wallet</a></li>
            <li class="{{ (request()->is('user/subscription')) ? 'active' : '' }}"><a href="{{route('user.profile')}}">My Subscription</a></li>
            <li class="{{ (request()->is('user/changePassword')) ? 'active' : '' }}"><a href="{{route('user.changePassword')}}">Change Password</a></li>
            <li class="last {{ (request()->is('user/logout')) ? 'active' : '' }}"><a href="{{route('user.logout')}}">Log Out</a></li>
        </ul>
    </div>
</div>