<?php
// includes/footer.php
?>
    <!-- Footer -->
    <!-- Footer -->
    <footer class="footer-burgundy-gradient text-gray-300 py-16 px-6 md:px-12 border-t border-amber-500/20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            
            <!-- Column 1: Brand & Info -->
            <div class="space-y-4">
                <a href="index.php" class="flex items-center space-x-3 group">
                    <img src="assets/images/logo.png" alt="LaoFe Logo" class="w-12 h-12 object-contain rounded-full shadow-md bg-white p-1 group-hover:scale-105 transition-transform duration-300">
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold tracking-wider font-serif-lao text-white leading-none">LaoFe</span>
                        <span class="text-[10px] font-semibold text-gold-400 tracking-widest mt-1">CAFE & BAR</span>
                    </div>
                </a>
                <p class="text-sm text-gray-400 leading-relaxed font-light font-serif-lao">
                    <?php echo t('footer_desc'); ?>
                </p>
                <div class="flex items-center space-x-3 pt-2">
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/laofe_beer?stkn=OG4wNGh0YmoxNWsw&utm_source=qr" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-[12px] bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white hover:scale-110 flex items-center justify-center transition-all duration-300 shadow-md" title="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/profile.php?id=61577332485813" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[#1877F2] text-white hover:scale-110 flex items-center justify-center transition-all duration-300 shadow-md" title="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <!-- TikTok -->
                    <a href="https://www.tiktok.com/@laofecafe_sihorm11?_r=1&_t=ZS-99m4pEa9dQd" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-black text-white hover:scale-110 flex items-center justify-center transition-all duration-300 shadow-md overflow-hidden" title="TikTok">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.18 1.09 1.15 2.58 1.83 4.15 1.94v3.83c-1.74-.07-3.41-.75-4.69-1.92-.12-.11-.23-.23-.34-.35v6.52c0 1.94-.56 3.84-1.61 5.43-1.46 2.08-3.9 3.32-6.43 3.36-2.58.07-5.11-1.07-6.72-3.13-1.68-2.22-2.12-5.26-1.12-7.87 1.01-2.55 3.52-4.26 6.27-4.39 1.48-.07 2.97.35 4.19 1.2V4.9c-.83-.43-1.75-.68-2.7-.73-2.02-.12-4.04.77-5.27 2.37C2.9 8.23 2.69 10.5 3.31 12.59c.64 2.1 2.35 3.73 4.5 4.3 2.15.54 4.5-.04 6.13-1.54 1.56-1.54 2.21-3.87 1.68-6.01l-.01-9.32z" fill="#00f2fe" transform="translate(-0.8, -0.8)" />
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.18 1.09 1.15 2.58 1.83 4.15 1.94v3.83c-1.74-.07-3.41-.75-4.69-1.92-.12-.11-.23-.23-.34-.35v6.52c0 1.94-.56 3.84-1.61 5.43-1.46 2.08-3.9 3.32-6.43 3.36-2.58.07-5.11-1.07-6.72-3.13-1.68-2.22-2.12-5.26-1.12-7.87 1.01-2.55 3.52-4.26 6.27-4.39 1.48-.07 2.97.35 4.19 1.2V4.9c-.83-.43-1.75-.68-2.7-.73-2.02-.12-4.04.77-5.27 2.37C2.9 8.23 2.69 10.5 3.31 12.59c.64 2.1 2.35 3.73 4.5 4.3 2.15.54 4.5-.04 6.13-1.54 1.56-1.54 2.21-3.87 1.68-6.01l-.01-9.32z" fill="#ff0050" transform="translate(0.8, 0.8)" />
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.18 1.09 1.15 2.58 1.83 4.15 1.94v3.83c-1.74-.07-3.41-.75-4.69-1.92-.12-.11-.23-.23-.34-.35v6.52c0 1.94-.56 3.84-1.61 5.43-1.46 2.08-3.9 3.32-6.43 3.36-2.58.07-5.11-1.07-6.72-3.13-1.68-2.22-2.12-5.26-1.12-7.87 1.01-2.55 3.52-4.26 6.27-4.39 1.48-.07 2.97.35 4.19 1.2V4.9c-.83-.43-1.75-.68-2.7-.73-2.02-.12-4.04.77-5.27 2.37C2.9 8.23 2.69 10.5 3.31 12.59c.64 2.1 2.35 3.73 4.5 4.3 2.15.54 4.5-.04 6.13-1.54 1.56-1.54 2.21-3.87 1.68-6.01l-.01-9.32z" fill="#ffffff" />
                        </svg>
                    </a>
                    <!-- YouTube -->
                    <a href="https://youtube.com/channel/UChlinl63V_DeLIwNb0Kho6g?si=zzYYlPINBv9Ii7wM" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[#FF0000] text-white hover:scale-110 flex items-center justify-center transition-all duration-300 shadow-md" title="YouTube">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <!-- Telegram -->
                    <a href="https://t.me/laofe_beer" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[#26A5E4] text-white hover:scale-110 flex items-center justify-center transition-all duration-300 shadow-md" title="Telegram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm5.221 8.358c-.161.713-1.636 7.636-2.38 11.23-.316 1.521-.874 1.802-1.411 1.85-.929.083-1.635-.494-2.535-1.085-1.408-.924-2.203-1.498-3.569-2.399-1.579-1.042-.556-1.615.344-2.55.235-.245 4.316-3.957 4.394-4.293.01-.042.018-.2-.075-.282-.093-.082-.228-.054-.326-.032-.139.031-2.355 1.498-6.649 4.4-.628.432-1.197.644-1.707.633-.563-.013-1.644-.319-2.448-.58-.987-.32-1.773-.49-1.704-1.034.036-.283.428-.574 1.176-.873 4.606-2.007 7.676-3.332 9.21-3.974 4.385-1.833 5.295-2.152 5.889-2.162.131-.002.423.031.613.185.16.13.204.307.225.431.021.124.038.384.021.597z"/></svg>
                    </a>
                </div>
            </div>
            
            <!-- Column 2: Quick Links -->
            <div class="space-y-4">
                <h3 class="text-white font-semibold text-lg border-l-4 border-gold-400 pl-3 font-serif-lao">
                    <?php echo t('footer_links'); ?>
                </h3>
                <ul class="space-y-2 text-sm text-gray-400 font-serif-lao">
                    <li><a href="index.php" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('nav_home'); ?></a></li>
                    <li><a href="our-story.php" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('nav_story'); ?></a></li>
                    <li><a href="menu.php" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('nav_menu'); ?></a></li>
                    <li><a href="locations.php" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('nav_locations'); ?></a></li>
                    <li><a href="news.php" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('nav_news'); ?></a></li>
                    <li><a href="franchise.php" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('nav_franchise'); ?></a></li>
                    <li><a href="contact.php" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('nav_contact'); ?></a></li>
                </ul>
            </div>
            
            <!-- Column 3: Contact details -->
            <div class="space-y-4">
                <h3 class="text-white font-semibold text-lg border-l-4 border-gold-400 pl-3 font-serif-lao">
                    <?php echo t('footer_contact'); ?>
                </h3>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-gold-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="font-serif-lao"><?php echo t('footer_address'); ?></span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gold-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>+856 20 91 111 104</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gold-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>info@laofecafe.com</span>
                    </li>
                </ul>
            </div>
            
            <!-- Column 4: Admin Portal -->
            <div class="space-y-4">
                <h3 class="text-white font-semibold text-lg border-l-4 border-gold-400 pl-3 font-serif-lao">
                    <?php echo t('footer_admin_title'); ?>
                </h3>
                <p class="text-sm text-gray-400 font-light font-serif-lao">
                    <?php echo t('footer_admin_desc'); ?>
                </p>
                <a href="admin/login.php" class="inline-flex items-center space-x-2 text-sm text-gold-400 hover:text-amber-300 font-bold font-serif-lao transition-colors duration-200">
                    <span><?php echo t('footer_admin_link'); ?></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto border-t border-gold-400/10 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 space-y-4 md:space-y-0 font-serif-lao">
            <div class="flex space-x-6">
                <a href="#" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('footer_policy'); ?></a>
                <span>|</span>
                <a href="#" class="hover:text-gold-400 transition-colors duration-200"><?php echo t('footer_terms'); ?></a>
            </div>
            <div>
                &copy; <?php echo date("Y"); ?> <?php echo t('brand_name'); ?>. <?php echo t('footer_rights'); ?>
            </div>
        </div>
    </footer>

    <!-- Floating Mobile Action Bar (Mobile Only) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-burgundy-900/95 backdrop-blur-md border-t border-amber-500/30 px-3 py-2.5 shadow-2xl flex items-center justify-around font-serif-lao">
        <a href="menu.php" class="flex flex-col items-center space-y-1 text-gold-300 hover:text-amber-200 transition-colors">
            <div class="w-8 h-8 rounded-full bg-burgundy-700/80 flex items-center justify-center text-gold-300 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <span class="text-[11px] font-medium leading-none"><?php echo $current_lang === 'lo' ? 'ສັ່ງອອນໄລນ໌' : 'Order Now'; ?></span>
        </a>

        <a href="booking.php" class="flex flex-col items-center space-y-1 text-gold-300 hover:text-amber-200 transition-colors">
            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 flex items-center justify-center text-white shadow-md hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-[11px] font-bold text-amber-300 leading-none"><?php echo $current_lang === 'lo' ? 'ຈອງໂຕະ' : 'Book Table'; ?></span>
        </a>

        <a href="https://wa.me/8562091111104" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center space-y-1 text-gold-300 hover:text-amber-200 transition-colors">
            <div class="w-8 h-8 rounded-full bg-emerald-600/90 flex items-center justify-center text-white shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            </div>
            <span class="text-[11px] font-medium leading-none"><?php echo $current_lang === 'lo' ? 'WhatsApp' : 'WhatsApp'; ?></span>
        </a>
    </div>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>
