@props([
    'id' => '',
    'wishlist' => [],
])

<style>
    .liked_one {

        position: absolute;
        z-index: 2;
    }

  .wishlistdiv  .fas {
        color: orange !important;

    }

    .far::before {
        color: orange !important;
    }
</style>
<div class="wishlistdiv">
    <i class="{{ in_array($id, $wishlist) ? '' : 'far' }}{{ in_array($id, $wishlist) ? 'fas' : '' }} {{ in_array($id, $wishlist) ? 'filled' : '' }} fa-heart  liked_one"
        id="{{ $id }}"></i>
</div>
