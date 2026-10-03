<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a class="logo logo-light" href="/">
                <img class="logo-mark" src="/assets/img/logo-plane.png" alt="">
                <span class="logo-text">Travelora</span>
            </a>
            <p>{{ t('footer.about', 'Your trusted travel companion. Find the best hotels, flights and experiences around the world, all in one place.') }}</p>
            <div class="socials">
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h4>{{ t('footer.company', 'Company') }}</h4>
            <a href="/about">{{ t('footer.aboutus', 'About Us') }}</a>
            <a href="/about">{{ t('footer.careers', 'Careers') }}</a>
            <a href="/about">{{ t('footer.press', 'Press') }}</a>
            <a href="/about">{{ t('footer.partners', 'Partners') }}</a>
            <a href="/api-status">{{ t('footer.apistatus', 'API Status') }}</a>
        </div>

        <div class="footer-col">
            <h4>{{ t('footer.explore', 'Explore') }}</h4>
            <a href="/hotels">{{ t('nav.hotels', 'Hotels') }}</a>
            <a href="/flights">{{ t('nav.flights', 'Flights') }}</a>
            <a href="/packages">{{ t('nav.packages', 'Packages') }}</a>
            <a href="/things-to-do">{{ t('nav.things', 'Things to Do') }}</a>
        </div>

        <div class="footer-col">
            <h4>{{ t('footer.support', 'Support') }}</h4>
            <a href="/contact">{{ t('footer.help', 'Help Center') }}</a>
            <a href="/terms">{{ t('footer.terms', 'Terms of Service') }}</a>
            <a href="/privacy">{{ t('footer.privacy', 'Privacy Policy') }}</a>
            <a href="/contact">{{ t('footer.contact', 'Contact Us') }}</a>
        </div>

        <div class="footer-newsletter">
            <h4>{{ t('footer.newsletter', 'Get travel deals in your inbox') }}</h4>
            <form action="/newsletter" method="post" class="newsletter-form">
                @csrf
                <input type="email" name="email" placeholder="{{ t('footer.email_ph', 'Enter your email') }}" required>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i></button>
            </form>
            <p class="small">{{ t('footer.nospam', 'No spam, unsubscribe anytime.') }}</p>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <span>&copy; {{ date('Y') }} Travelora. {{ t('footer.rights', 'All rights reserved.') }}</span>
            <span class="footer-attrib">Live data: OpenStreetMap &middot; Wikipedia &middot; Open-Meteo &middot; Wikidata</span>
        </div>
    </div>
</footer>
