document.addEventListener('DOMContentLoaded', function () {

    const sortSelect = document.getElementById('sort-select');
    const categorySelect = document.getElementById('category');
    const minPriceInput = document.querySelector('.entry-price input');
    const maxPriceInput = document.querySelector('.current-bid input');
    const applyFiltersButton = document.getElementById('button[type="submit]');
    const clearFilterButton = document.getElementById('clear-filters');
    const cardsContainer = document.querySelector('.cards-container');

    async function fetchFilteredAuctions() {
        const sortBy = sortSelect.value;
        const categoryId = categorySelect.value;
        const minPrice = minPriceInput.value;
        const maxPrice = maxPriceInput.value;


        const queryParams = new URLSearchParams({
            sort_by: sortBy,
            category_id: categoryId,
            min_price: minPrice,
            max_price: maxPrice,
        });

        try {
            const response = await fetch(`/auctions/filter?${queryParams.toString()}`);
            const data = await response.json();

            if (data.status === 'success') {
                renderAuctions(data.auctions);
            }
            else {
                console.error('Failed to fetch auctions');
            }
        }
        catch (error) {
            console.error({ 'Failed to fetch auctions': error });
        }
    }

    function renderAuctions(auctions) {
        cardsContainer.innerHTML = ''; // Clear existing content

        // Populate with new auction cards
        auctions.forEach(auction => {
            const auctionCard = `
                <div class="card">
                    <h3>${auction.title}</h3>
                    <p>${auction.description}</p>
                    <p>Current Bid: $${auction.current_bid}</p>
                    <p>Ends: ${new Date(auction.end_date).toLocaleString()}</p>
                </div>
            `;
            cardsContainer.innerHTML += auctionCard;
        });
    }

    // Attach event listeners
    applyFiltersButton.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent form submission
        fetchFilteredAuctions();
    });

    clearFiltersButton.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent link navigation

        // Reset filters
        sortSelect.value = '';
        categorySelect.value = '';
        minPriceInput.value = 0;
        maxPriceInput.value = 10000;

        // Fetch all auctions
        fetchFilteredAuctions();
    });
});