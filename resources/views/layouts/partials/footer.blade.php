<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h3 class="fs-4 mb-3">TIFFANY</h3>
                <p>Crafting elegance since 1837</p>
            </div>

            <div class="col-md-2 mb-4 mb-md-0">
                <h4 class="fs-5 mb-3">Shop</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('categories.show', 'rings') }}" class="text-white-50">Rings</a>
                    </li>
                    <li class="mb-2"><a href="{{ route('categories.show', 'necklaces') }}"
                            class="text-white-50">Necklaces</a></li>
                    <li><a href="{{ route('categories.show', 'bracelets') }}" class="text-white-50">Bracelets</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-4 mb-md-0">
                <h4 class="fs-5 mb-3">Contact</h4>
                <address>
                    123 Jewelry Ave<br>
                    New York, NY 10001<br>
                    <a href="mailto:info@tiffany.com" class="text-white-50">info@tiffany.com</a>
                    <li class="list-unstyled"><a href="{{ route('contact.form') }}" class="text-white-50">Contact Us</a>
                    </li>
                </address>

            </div>

            <div class="col-md-3">
                <h4 class="fs-5 mb-3">Follow Us</h4>
                <div class="social-icons">
                    <a href="https://www.instagram.com" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.tiktok.com" class="text-white"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>

        <div class="border-top border-secondary mt-5 pt-4 text-center">
            <p class="small">&copy; {{ date('Y') }} Tiffany All rights reserved.</p>
        </div>
    </div>
</footer>


