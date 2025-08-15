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
                    <h5 class="mb-3">AGERT</h5>
                    <p class="mb-3">Agência Reguladora de Serviços Públicos Delegados do Município de Timon</p>
                    <p class="mb-0">Garantindo qualidade e eficiência dos serviços públicos com transparência.</p>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">Contato</h6>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <span>(99) 3212-3456</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <span>contato@agert.timon.ma.gov.br</span>
                    </div>
                    <div class="d-flex align-items-start mb-2">
                        <i class="bi bi-geo-alt me-2 mt-1"></i>
                        <span>Rua dos Reguladores, 123 - Centro<br>Timon/MA - CEP: 65630-100</span>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">Links Úteis</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="<?php echo esc_url(agert_get_page_link('sobre')); ?>" class="text-white-50 text-decoration-none">
                                <i class="bi bi-arrow-right me-2"></i>Sobre a AGERT
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="text-white-50 text-decoration-none">
                                <i class="bi bi-arrow-right me-2"></i>Reuniões
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contato'))); ?>" class="text-white-50 text-decoration-none">
                                <i class="bi bi-arrow-right me-2"></i>Contato
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="https://www.timon.ma.gov.br" class="text-white-50 text-decoration-none" target="_blank" rel="noopener">
                                <i class="bi bi-arrow-right me-2"></i>Prefeitura de Timon
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('politica-de-privacidade'))); ?>" class="text-white-50 text-decoration-none">
                                <i class="bi bi-arrow-right me-2"></i>Política de Privacidade
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 border-white-50">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-white-50">
                        &copy; <?php echo date('Y'); ?> AGERT Timon. Todos os direitos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-white-50">
                        <i class="bi bi-clock me-1"></i>
                        Horário de funcionamento: Seg-Sex, 08:00 às 17:00
                    </small>
                </div>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>