document.getElementById('clear-filters').addEventListener('click', function(event) {
    event.preventDefault();

    const price = document.getElementById('sort-select');
    if (price) price.value = '';

    // Clear category
    const category = document.getElementById('category');
    if (category) category.value = '';

    // Clear entry price range
    const entryPriceRange = document.getElementById('entry-price');
    if (entryPriceRange) entryPriceRange.value = 0;

    // Log success
    console.log("Filters have been cleared");
});
