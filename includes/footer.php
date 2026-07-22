<?php
// includes/footer.php
?>
    <!-- Footer -->
    <!-- Footer -->
    <footer class="bg-burgundy-700 text-white/80 py-16 px-6 md:px-12 border-t border-white/10">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            
            <!-- Column 1: Brand & Info -->
            <div class="space-y-4">
                <a href="index.php" class="flex items-center space-x-3 group">
                    <img src="assets/images/logo.png" alt="LaoFe Logo" class="w-12 h-12 object-contain rounded-full shadow-md bg-white p-1 group-hover:scale-105 transition-transform duration-300">
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold tracking-wider font-serif-lao text-white leading-none">LaoFe</span>
                        <span class="text-[10px] font-semibold text-white/70 tracking-widest mt-1">CAFE & BAR</span>
                    </div>
                </a>
                <p class="text-sm text-white/70 leading-relaxed font-light">
                    <?php echo t('footer_desc'); ?>
                </p>
                <div class="flex items-center space-x-4 pt-2">
                    <!-- Facebook -->
                    <a href="https://facebook.com" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white hover:text-burgundy-700 flex items-center justify-center transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                    </a>
                    <!-- Instagram -->
                    <a href="https://instagram.com" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white hover:text-burgundy-700 flex items-center justify-center transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <!-- TikTok -->
                    <a href="https://tiktok.com" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white hover:text-burgundy-700 flex items-center justify-center transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.18 1.09 1.15 2.58 1.83 4.15 1.94v3.83c-1.74-.07-3.41-.75-4.69-1.92-.12-.11-.23-.23-.34-.35v6.52c0 1.94-.56 3.84-1.61 5.43-1.46 2.08-3.9 3.32-6.43 3.36-2.58.07-5.11-1.07-6.72-3.13-1.68-2.22-2.12-5.26-1.12-7.87 1.01-2.55 3.52-4.26 6.27-4.39 1.48-.07 2.97.35 4.19 1.2V4.9c-.83-.43-1.75-.68-2.7-.73-2.02-.12-4.04.77-5.27 2.37C2.9 8.23 2.69 10.5 3.31 12.59c.64 2.1 2.35 3.73 4.5 4.3 2.15.54 4.5-.04 6.13-1.54 1.56-1.54 2.21-3.87 1.68-6.01l-.01-9.32z"/></svg>
                    </a>
                    <!-- WhatsApp -->
                    <a href="https://wa.me/8562055551111" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white hover:text-burgundy-700 flex items-center justify-center transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.488 1.459 5.416 1.46 5.72 0 10.375-4.65 10.379-10.366.002-2.77-1.077-5.373-3.037-7.338-1.958-1.965-4.563-3.048-7.34-3.049-5.73 0-10.38 4.651-10.383 10.37-.001 1.93.504 3.812 1.461 5.418l-.96 3.502 3.584-.94z"/></svg>
                    </a>
                </div>
            </div>
            
            <!-- Column 2: Quick Links -->
            <div class="space-y-4">
                <h3 class="text-white font-semibold text-lg border-l-4 border-white pl-3">
                    <?php echo t('footer_links'); ?>
                </h3>
                <ul class="space-y-2 text-sm text-white/70">
                    <li><a href="index.php" class="hover:text-white transition-colors duration-200"><?php echo t('nav_home'); ?></a></li>
                    <li><a href="our-story.php" class="hover:text-white transition-colors duration-200"><?php echo t('nav_story'); ?></a></li>
                    <li><a href="menu.php" class="hover:text-white transition-colors duration-200"><?php echo t('nav_menu'); ?></a></li>
                    <li><a href="locations.php" class="hover:text-white transition-colors duration-200"><?php echo t('nav_locations'); ?></a></li>
                    <li><a href="news.php" class="hover:text-white transition-colors duration-200"><?php echo t('nav_news'); ?></a></li>
                    <li><a href="franchise.php" class="hover:text-white transition-colors duration-200"><?php echo t('nav_franchise'); ?></a></li>
                    <!-- Hidden for Phase 1 - Uncomment to show when App launches
                    <li><a href="app.php" class="hover:text-white transition-colors duration-200"><?php echo t('nav_app'); ?></a></li>
                    -->
                    <li><a href="contact.php" class="hover:text-white transition-colors duration-200"><?php echo t('nav_contact'); ?></a></li>
                </ul>
            </div>
            
            <!-- Column 3: Contact details -->
            <div class="space-y-4">
                <h3 class="text-white font-semibold text-lg border-l-4 border-white pl-3">
                    <?php echo t('footer_contact'); ?>
                </h3>
                <ul class="space-y-3 text-sm text-white/70">
                    <li class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-white shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span><?php echo t('footer_address'); ?></span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>+856 20 95 555 094</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>info@laofecafe.com</span>
                    </li>
                </ul>
            </div>
            
            <!-- Column 4: Newsletter or Admin Portal -->
            <div class="space-y-4">
                <h3 class="text-white font-semibold text-lg border-l-4 border-white pl-3">
                    <?php echo t('footer_admin_title'); ?>
                </h3>
                <p class="text-sm text-white/70 font-light">
                    <?php echo t('footer_admin_desc'); ?>
                </p>
                <a href="admin/login.php" class="inline-flex items-center space-x-2 text-sm text-white hover:underline font-semibold transition-colors duration-200">
                    <span><?php echo t('footer_admin_link'); ?></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto border-t border-white/10 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-white/50 space-y-4 md:space-y-0">
            <div class="flex space-x-6">
                <a href="#" class="hover:text-white transition-colors duration-200"><?php echo t('footer_policy'); ?></a>
                <span>|</span>
                <a href="#" class="hover:text-white transition-colors duration-200"><?php echo t('footer_terms'); ?></a>
            </div>
            <div>
                &copy; <?php echo date("Y"); ?> <?php echo t('brand_name'); ?>. <?php echo t('footer_rights'); ?>
            </div>
        </div>
    </footer>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>
