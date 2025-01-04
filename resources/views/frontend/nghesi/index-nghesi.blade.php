@extends('frontend.layouts.app-user')

@section('main')
<section class="categorie-area bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section-title mt-5">
                    <h2 class="text-primary">Nghệ Sĩ Nổi Tiếng</h2>
                    <p class="text-muted">Khám phá những nghệ sĩ nổi bật trong tuần này</p>
                </div>
            </div>
        </div>
        <div class="category-slider owl-carousel owl-nav-style" id="category-slider">
        </div>
    </div>
</section>

<script>
    const containerCategorySlider = document.getElementById('category-slider');
    const artisanUrl = "{{ url('artist') }}";  

    const displayArtists = (artists) => {
        containerCategorySlider.innerHTML = ''; 
        artists.forEach(artist => {
            const artistHTML = `
                <div class="category-item">
                    <div class="category-list mb-4">
                        <div class="category-thumb">
                            <a href="${artisanUrl}?id=${artist.id}">
                                <img src="${artist.image_path}" alt="${artist.name}" class="img-fluid rounded shadow-lg"/>
                            </a>
                        </div>
                        <div class="desc-listcategoreis">
                            <div class="name_categories">
                                <h4 class="text-dark"> ${artist.products_count} bài hát</h4>
                            </div>
                            <div class="name_categories">
                                <h4 class="text-dark">Nghệ sĩ ${artist.name}</h4>
                            </div>
                            <a href="${artisanUrl}?id=${artist.id}" class="btn btn-outline-primary btn-sm mt-2">Xem ngay <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            `;
            containerCategorySlider.innerHTML += artistHTML;
        });

        $('.category-slider').owlCarousel('destroy');
        $('.category-slider').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        });
    };

    function fetchFamousArtists() {
        const url = "{{ route('artists.famous') }}";
        fetch(url, {
                method: "GET",
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                displayArtists(data);
            })
            .catch(error => {
                console.error('Error fetching famous artists:', error);
            });
    }
    fetchFamousArtists();
</script>
@endsection
