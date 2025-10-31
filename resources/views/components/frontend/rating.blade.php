@props([
    "rating"=>""
])

<span class="rating-badge">
    {{ $rating}}
    <span class="star">★</span>
</span>

<style>
.rating-badge {
    display: inline-flex;
    align-items: center;
    background-color: #388e3c; /* Flipkart green */
    color: #fff;
    font-weight: 500;
    font-size: 13px;
    border-radius: 4px;
    padding: 2px 6px;
}

.rating-badge .star {
    font-size: 12px;
    margin-left: 3px;
    color: #fff; /* white star */
}
</style>
