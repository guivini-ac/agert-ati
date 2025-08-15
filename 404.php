<?php
/**
 * Template para página 404
 * 
 * @package AGERT
 */

get_header(); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="error-404">
                <!-- Ícone grande -->
                <div class="error-icon mb-4">
                    <i class="bi bi-exclamation-triangle-fill display-1 text-warning"></i>
                </div>
                
                <!-- Título e mensagem -->
                <h1 class="error-title display-3 mb-3 text-primary">404</h1>
                <h2 class="error-subtitle mb-4"><?php _e('Página não encontrada', 'agert'); ?></h2>
                
                <p class="error-description text-muted mb-5 lead">
                    <?php _e('Desculpe, a página que você está procurando não existe ou foi movida. Isso pode ter acontecido por diversos motivos.', 'agert'); ?>
                </p>
                
                <!-- Formulário de busca -->
                <div class="search-section mb-5">
                    <h5 class="mb-3"><?php _e('Tente buscar pelo conteúdo:', 'agert'); ?></h5>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="d-flex">
                                <input type="search" 
                                       class="form-control me-2" 
                                       placeholder="<?php _e('Digite sua pesquisa...', 'agert'); ?>" 
                                       name="s" 
                                       title="<?php _e('Buscar por:', 'agert'); ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                    <span class="d-none d-sm-inline ms-1"><?php _e('Buscar', 'agert'); ?></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Links úteis -->
                <div class="helpful-links">
                    <h5 class="mb-4"><?php _e('Ou navegue para uma dessas páginas:', 'agert'); ?></h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="bi bi-house-door fs-2 mb-2"></i>
                                <span><?php _e('Página Inicial', 'agert'); ?></span>
                            </a>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <a href="<?php echo esc_url(agert_get_page_link('sobre')); ?>" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="bi bi-info-circle fs-2 mb-2"></i>
                                <span><?php _e('Sobre a AGERT', 'agert'); ?></span>
                            </a>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="bi bi-calendar-event fs-2 mb-2"></i>
                                <span><?php _e('Reuniões', 'agert'); ?></span>
                            </a>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contato'))); ?>" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="bi bi-envelope fs-2 mb-2"></i>
                                <span><?php _e('Contato', 'agert'); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Call to action adicional -->
                <div class="mt-5 pt-4 border-top">
                    <p class="text-muted">
                        <?php _e('Se você acredita que isso é um erro, por favor', 'agert'); ?>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contato'))); ?>" class="text-decoration-none">
                            <?php _e('entre em contato conosco', 'agert'); ?>
                        </a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Seção de páginas populares (opcional) -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-star me-2"></i>
                        <?php _e('Páginas Mais Acessadas', 'agert'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        // Buscar páginas principais
                        $popular_pages = get_posts(array(
                            'post_type' => array('page', 'reuniao'),
                            'posts_per_page' => 6,
                            'orderby' => 'menu_order',
                            'order' => 'ASC',
                            'meta_query' => array(
                                'relation' => 'OR',
                                array(
                                    'key' => '_popular_page',
                                    'value' => '1',
                                    'compare' => '='
                                ),
                                array(
                                    'key' => '_popular_page',
                                    'compare' => 'NOT EXISTS'
                                )
                            )
                        ));
                        
                        if ($popular_pages) :
                            foreach ($popular_pages as $page) :
                                $post_type_icon = agert_get_post_type_icon($page->post_type);
                        ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-<?php echo esc_attr($post_type_icon); ?> text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">
                                            <a href="<?php echo get_permalink($page->ID); ?>" class="text-decoration-none">
                                                <?php echo esc_html($page->post_title); ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <?php echo get_post_type_object($page->post_type)->labels->singular_name; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php
                            endforeach;
                            wp_reset_postdata();
                        else :
                        ?>
                            <div class="col-12 text-center text-muted">
                                <p><?php _e('Nenhuma página popular encontrada.', 'agert'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adicionar um pouco de JavaScript para tracking de 404s (opcional) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Log 404 error para analytics (se houver)
    if (typeof gtag !== 'undefined') {
        gtag('event', 'page_view', {
            'page_title': '404 - Página não encontrada',
            'page_location': window.location.href
        });
    }
    
    // Sugerir busca baseada na URL
    const currentPath = window.location.pathname;
    const pathParts = currentPath.split('/').filter(part => part.length > 0);
    
    if (pathParts.length > 0) {
        const searchInput = document.querySelector('input[name="s"]');
        if (searchInput && !searchInput.value) {
            // Tentar extrair palavra-chave da URL
            const lastPart = pathParts[pathParts.length - 1];
            const cleanKeyword = lastPart.replace(/[-_]/g, ' ');
            
            if (cleanKeyword.length > 2) {
                searchInput.value = cleanKeyword;
                searchInput.placeholder = 'Sugestão baseada na URL: ' + cleanKeyword;
            }
        }
    }
});
</script>

<?php get_footer(); ?>