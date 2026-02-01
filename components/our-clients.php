<section class="clients-section">
    <div class="container">
        <div class="section-title">
            <h2>Our Trusted Clients</h2>
            <p>We are proud to work with these industry leaders.</p>
        </div>
        
        <div class="ticker-wrap">
            <div class="ticker-content">
                <?php
                // Client Logos Array
                $clients = [
                    ['img' => 'assets/our-clients/ambience-group.png', 'alt' => 'Ambience Group'],
                    ['img' => 'assets/our-clients/lonza-capsugel.png', 'alt' => 'Lonza Capsugel'],
                    ['img' => 'assets/our-clients/manbhavan-restaurant.png', 'alt' => 'Manbhavan Restaurant'],
                    ['img' => 'assets/our-clients/tg-minda.jpg', 'alt' => 'TG Minda'],
                ];

                // Function to output client logos
                function renderClientLogos($clients) {
                    foreach ($clients as $client) {
                        echo '<div class="client-logo">';
                        echo '<img src="' . $client['img'] . '" alt="' . $client['alt'] . '">';
                        echo '</div>';
                    }
                }

                // Output the set multiple times to ensure it fills the width and loops smoothly
                // We repeat it 4 times here to be safe on wide screens
                renderClientLogos($clients);
                renderClientLogos($clients);
                renderClientLogos($clients);
                renderClientLogos($clients);
                ?>
            </div>
        </div>
    </div>
</section>
