
const brandsByCategory = {
    cars: [
        { value: 'toyota', text: 'Toyota' },
        { value: 'porsche', text: 'Porsche' },
        { value: 'ford', text: 'Ford' }
    ],
    eletronics: [
        { value: 'Laptops', text: 'Laptops' },
        { value: 'Phones', text: 'Phones' },
        { value: 'Televisions', text: 'Televisions' },
        { value: 'Headphones', text: 'Headphones' },
        { value: 'Cameras', text: 'Cameras'}
    ],
    clothing: [
        { value: 'prada', text: 'Prada' },
        { value: 'gucci', text: 'Gucci' },
        { value: 'nike', text: 'Nike' }
    ],
    accessories: [
        { value: 'rolex', text: 'Rolex' },
        { value: 'tag_heuer', text: 'Tag Heuer' },
        { value: 'cartier', text: 'Cartier' }
    ]
};

//the purpose of this file is to update brand options on filter when category is selected
document.getElementById('category').addEventListener('change', function () {
    const selectedCategory = this.value;
    const brandSelect = document.getElementById('brand');

    brandSelect.innerHTML = '<option value="">Select Brand</option>';

    // Check if selected category has associated brands
    if (selectedCategory && brandsByCategory[selectedCategory]) {
        const brands = brandsByCategory[selectedCategory];
        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.value;
            option.textContent = brand.text;
            brandSelect.appendChild(option);
        });
    }
});



