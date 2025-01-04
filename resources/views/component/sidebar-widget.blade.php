<div class="sidebar-widget">
    <h4 class="pro-sidebar-title">Categories</h4>
    <div class="sidebar-widget-list">
        <ul id="category-list">
           {{-- /Dữ liệu ở đây --}}
        </ul>
    </div>
</div>
<script>
    const fetchCategories = () => {
        fetch('http://localhost:8000/api/categories', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // updateCategories(data.categoriesData);
            updateCategories(fakeCategories);  
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            const fakeCategories = [
                { name: 'Electronics', productCount: 120 },
                { name: 'Fashion', productCount: 80 },
                { name: 'Home Appliances', productCount: 55 },
                { name: 'Books', productCount: 200 },
                { name: 'Toys', productCount: 45 }
            ];
            updateCategories(fakeCategories);  
        });
    }

    const updateCategories = (categories) => {
        const categoryList = document.getElementById("category-list");
        categoryList.innerHTML = "";
        categories.forEach(category => {
            const categoryHTML = `
                <li>
                    <div class="sidebar-widget-list-left">
                        <input type="checkbox" id="${category.name}" />
                        <a href="#">${category.name} <span>(${category.productCount})</span></a>
                        <span class="checkmark"></span>
                    </div>
                </li>
            `;
            categoryList.innerHTML += categoryHTML;
        });
    }

    fetchCategories();  
</script>
