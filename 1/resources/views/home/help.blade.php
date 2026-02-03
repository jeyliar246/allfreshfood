<x-home-layout>
    <main class="container py-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold">Help Center</h1>
            <p class="text-muted">Find answers to common questions.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="h1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#c1">
                                How do I place an order?
                            </button>
                        </h2>
                        <div id="c1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Browse products, add to cart, and follow checkout instructions. You can track orders from your account.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="h2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">
                                What is the delivery timeline?
                            </button>
                        </h2>
                        <div id="c2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Most orders arrive within 2-5 working days depending on your location and vendor.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="h3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3">
                                How can I return an item?
                            </button>
                        </h2>
                        <div id="c3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Refer to the vendor's return policy on the product page or contact our support team for assistance.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-home-layout>
