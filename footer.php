<?php
/**
 * Footer do tema AGERT
 * 
 * @package AGERT
 */
?>

    </main><!-- #main -->

    <footer id="colophon" class="site-footer bg-primary text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3"><?php _e('AGERT', 'agert'); ?></h5>
                    <p class="mb-3"><?php _e('Agência Reguladora de Serviços Públicos Delegados do Município de Timon', 'agert'); ?></p>
                    <p class="mb-0"><?php _e('Garantindo qualidade e eficiência dos serviços públicos com transparência.', 'agert'); ?></p>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3"><?php _e('Contato', 'agert'); ?></h6>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <span><?php _e('(99) 3212-3456', 'agert'); ?></span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <span><?php _e('contato@agert.timon.ma.gov.br', 'agert'); ?></span>
                    </div>
                    <div class="d-flex align-items-start mb-2">
                        <i class="bi bi-geo-alt me-2 mt-1"></i>
                        <span><?php _e('Rua dos Reguladores, 123 - Centro<br>Timon/MA - CEP: 65630-100', 'agert'); ?></span>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3"><?php _e('Links Úteis', 'agert'); ?></h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('sobre'))); ?>" class="text-white-50 text-decoration-none">
                                <i class="bi bi-arrow-right me-2"></i><?php _e('Sobre a AGERT', 'agert'); ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="text-white-50 text-decoration-none">
                                <i class="bi bi-arrow-right me-2"></i><?php _e('Reuniões', 'agert'); ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contato'))); ?>" class="text-white-50 text-decoration-none">
                                <i class="bi bi-arrow-right me-2"></i><?php _e('Contato', 'agert'); ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="https://www.timon.ma.gov.br" class="text-white-50 text-decoration-none" target="_blank" rel="noopener">
                                <i class="bi bi-arrow-right me-2"></i><?php _e('Prefeitura de Timon', 'agert'); ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('politica-de-privacidade'))); ?>" class="text-white-50 text-decoration-none">
                                <i class="bi bi-arrow-right me-2"></i><?php _e('Política de Privacidade', 'agert'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 border-white-50">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-white-50">
                        &copy; <?php echo date('Y'); ?> <?php _e('AGERT Timon. Todos os direitos reservados.', 'agert'); ?>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-white-50">
                        <i class="bi bi-clock me-1"></i>
                        <?php _e('Horário de funcionamento: Seg-Sex, 08:00 às 17:00', 'agert'); ?>
                    </small>
                </div>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
