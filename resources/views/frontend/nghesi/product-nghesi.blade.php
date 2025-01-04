@extends('frontend.layouts.app-user')

@section('main')
<section class="categorie-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mt-res-sx-30px mt-res-md-30px">
                    <h2>Nghệ Sĩ Nổi Tiếng</h2>
                    <p>Khám phá những nghệ sĩ nổi bật trong tuần này</p>
                </div>
            </div>
        </div>
        <div class="category-slider owl-carousel owl-nav-style" id="category-slider">
        </div>
    </div>
</section>
<script>
    const containerCategorySlider = document.getElementById('category-slider');
    const displayArtists = (artists) => {
        containerCategorySlider.innerHTML = ''; 
        artists.forEach(artist => {
            const artistHTML = `
                <div class="category-item">
                    <div class="category-list mb-30px">
                        <div class="category-thumb">
                            <a href="${artist.link}">
                                <img src="${artist.image}" alt="${artist.name}" />
                            </a>
                        </div>
                        <div class="desc-listcategoreis">
                            <div class="name_categories">
                                <h4>${artist.name}</h4>
                            </div>
                            <span class="text-dark font-weight-bold fs-5 number_product">${artist.popularity} Lượt thích</span>
                            <a href="${artist.link}"> Xem ngay <i class="ion-android-arrow-dropright-circle"></i></a>
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
        fetch('{{ route('artists.famous') }}', {
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
                displayArtists(data.artistsData);
            })
            .catch(error => {
                console.error('Error fetching famous artists:', error);
            });
    }
    fetchFamousArtists()
</script>
@endsection
