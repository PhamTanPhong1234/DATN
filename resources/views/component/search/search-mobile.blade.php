<div class="mobile-search-option pb-3 d-lg-none hover-style-default">
    <div class="container-fluid">
        
        <div class="header-account-list">
        
            <div class="dropdown-search">
                <form action="{{ url('search-result') }}" method="GET">
                    <input type="text" id="search_product_mobile">
                    <button type="submit"><i class="ion-ios-search-strong"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search_product_mobile');
        const suggestionsList = document.getElementById('suggestions');

        searchInput.addEventListener('input', async function(event) {
            const query = event.target.value;

            if (query.length < 3) {
                clearSuggestions();
                return;
            }
            try {
                const response = await axios.get('{{ route('search.product') }}', { params: { query } });
                const products = response.data;
                console.log("API response received:", products);
                displaySuggestions(products);
            } catch (error) {
                console.error('Error fetching search results:', error);
            }
        });


       
    });
</script>