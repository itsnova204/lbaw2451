<a href="{{ route('auction.show', $auction) }}" class="auction-card-link">
    <div class="auction-card rectangle-div">
        <div class="expire-date">
            <span>Auction expires in: {{ $auction->end_date->diffForHumans() }}</span>
        </div>
        <div class="product-img">
            <img src="https://via.placeholder.com/300" alt="{{ $auction->title }}">
        </div>
        <div class="product-info">
            <div class="product-name">
                <span>{{ $auction->title }}</span>
            </div>
            <div class="border"></div>
            <div class="description">
                <span>Description</span>
                <p>{{ $auction->description }}</p>
            </div>
            <div class="border"></div>
            <div class="prices">
                <div class="entry-price">
                    <span>Entry Price</span>
                    <span>${{ $auction->minimum_bid }}</span>
                </div>
                <div class="current-bid-price">
                    <span>Current price</span>
                    <span>${{ $auction->current_bid }}</span>
                </div>
            </div>
        </div>
    </div>
</a>