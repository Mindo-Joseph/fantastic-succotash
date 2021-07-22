<div class="dashboard-left">
    <div class="collection-mobile-back">
        <span class="filter-back">
            <i class="fa fa-angle-left" aria-hidden="true"></i> back
        </span>
    </div>
    <div class="block-content">
        <ul>
            <li class="{{ (request()->is('user/profile')) ? 'active' : '' }}"><a href="{{route('user.profile')}}">{{ __('Account Info') }}</a></li>
            <li class="{{ (request()->is('user/addressBook')) ? 'active' : '' }}"><a href="{{route('user.addressBook')}}">{{ __('Address Book') }}</a></li>
            <li class="{{ (request()->is('user/orders*')) ? 'active' : '' }}"><a href="{{route('user.orders')}}">{{ __('My Orders') }}</a></li>
            <li class="{{ (request()->is('user/wishlists')) ? 'active' : '' }}"><a href="{{route('user.wishlists')}}">{{ __('My Wishlist') }}</a></li>
            <li class="{{ (request()->is('user/wallet')) ? 'active' : '' }}"><a href="{{route('user.wallet')}}">{{ __('My Wallet') }}</a></li>
            <li class="{{ (request()->is('user/subscription*')) ? 'active' : '' }}"><a href="{{route('user.subscription.plans')}}">{{ __('My Subscriptions') }}</a></li>
            <li class="{{ (request()->is('user/changePassword')) ? 'active' : '' }}"><a href="{{route('user.changePassword')}}">{{ __('Change Password') }}</a></li>
            <li class="last {{ (request()->is('user/logout')) ? 'active' : '' }}"><a href="{{route('user.logout')}}">{{ __('Log Out') }}</a></li>
        </ul>
    </div>
</div>