<x-home-layout>
    <main class="container py-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold">About All Foods</h1>
            <p class="text-muted">Discover our mission and the team behind the marketplace.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Our Mission</h5>
                        <p>We connect food lovers with authentic global flavors from local and international vendors across the UK. Our platform helps small businesses reach new customers and enables users to easily discover cuisines from around the world.</p>
                        <h5 class="fw-semibold mt-4 mb-3">What We Offer</h5>
                        <ul class="mb-0">
                            <li>Curated vendors and authentic products</li>
                            <li>Seamless shopping and fast delivery</li>
                            <li>AI assistant to help you plan meals and find products</li>
                        </ul>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('home.browse') }}" class="btn btn-primary">
                        Browse Products
                    </a>
                </div>
            </div>
        </div>
    </main>
</x-home-layout>
