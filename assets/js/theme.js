/**
 * AGERT Theme JavaScript
 * Funcionalidades básicas em vanilla JS
 * 
 * @package AGERT
 */

(function() {
    'use strict';

    // Aguardar carregamento do DOM
    document.addEventListener('DOMContentLoaded', function() {
        
        // Inicializar funcionalidades
        initSmoothScroll();
        initFormValidation();
        initTooltips();
        initScrollToTop();
        
        // Debug mode
        if (window.location.search.includes('debug=1')) {
            console.log('AGERT Theme JS carregado');
        }
    });

    /**
     * Scroll suave para âncoras
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Validação básica de formulários
     */
    function initFormValidation() {
        const forms = document.querySelectorAll('form[data-validate="true"]');
        
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                // Remover classes de erro anteriores
                form.querySelectorAll('.is-invalid').forEach(function(field) {
                    field.classList.remove('is-invalid');
                });
                
                // Validar campos obrigatórios
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    }
                    
                    // Validação de email
                    if (field.type === 'email' && field.value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(field.value)) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Focar no primeiro campo com erro
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.focus();
                    }
                    
                    // Mostrar alerta
                    showAlert('Por favor, preencha todos os campos obrigatórios corretamente.', 'danger');
                }
            });
        });
    }

    /**
     * Inicializar tooltips do Bootstrap
     */
    function initTooltips() {
        // Verificar se Bootstrap está carregado
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }

    /**
     * Botão "Voltar ao topo"
     */
    function initScrollToTop() {
        // Criar botão se não existir
        let scrollTopBtn = document.getElementById('scroll-to-top');
        if (!scrollTopBtn) {
            scrollTopBtn = document.createElement('button');
            scrollTopBtn.id = 'scroll-to-top';
            scrollTopBtn.className = 'btn btn-primary position-fixed bottom-0 end-0 m-3 rounded-circle';
            scrollTopBtn.style.cssText = 'width: 50px; height: 50px; display: none; z-index: 1050;';
            scrollTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
            scrollTopBtn.setAttribute('title', 'Voltar ao topo');
            document.body.appendChild(scrollTopBtn);
        }
        
        // Mostrar/ocultar botão baseado no scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollTopBtn.style.display = 'block';
            } else {
                scrollTopBtn.style.display = 'none';
            }
        });
        
        // Ação do clique
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Mostrar alertas dinamicamente
     */
    function showAlert(message, type = 'info', duration = 5000) {
        const alertContainer = document.getElementById('alert-container') || createAlertContainer();
        
        const alertId = 'alert-' + Date.now();
        const alertHTML = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="bi bi-${getAlertIcon(type)} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        alertContainer.insertAdjacentHTML('beforeend', alertHTML);
        
        // Auto-remover após duração especificada
        setTimeout(function() {
            const alertElement = document.getElementById(alertId);
            if (alertElement) {
                alertElement.remove();
            }
        }, duration);
    }

    /**
     * Criar container para alertas
     */
    function createAlertContainer() {
        const container = document.createElement('div');
        container.id = 'alert-container';
        container.className = 'position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1060';
        document.body.appendChild(container);
        return container;
    }

    /**
     * Obter ícone para tipo de alerta
     */
    function getAlertIcon(type) {
        const icons = {
            'success': 'check-circle',
            'danger': 'exclamation-triangle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle',
            'primary': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    /**
     * Utilitário para requisições AJAX simples
     */
    function ajaxRequest(url, options = {}) {
        const defaults = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        };
        
        const config = Object.assign({}, defaults, options);
        
        return fetch(url, config)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.status);
                }
                return response.json();
            })
            .catch(function(error) {
                console.error('Erro AJAX:', error);
                throw error;
            });
    }

    /**
     * Manipulação de formulários AJAX
     */
    function initAjaxForms() {
        const ajaxForms = document.querySelectorAll('form[data-ajax="true"]');
        
        ajaxForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Estado de carregamento
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
                submitBtn.disabled = true;
                
                // Enviar dados
                ajaxRequest(agert_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    if (response.success) {
                        showAlert(response.data.message || 'Operação realizada com sucesso!', 'success');
                        form.reset();
                    } else {
                        showAlert(response.data.message || 'Erro ao processar solicitação.', 'danger');
                    }
                })
                .catch(function(error) {
                    showAlert('Erro de conexão. Tente novamente.', 'danger');
                })
                .finally(function() {
                    // Restaurar botão
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        });
    }

    /**
     * Confirmação para ações destrutivas
     */
    function initConfirmActions() {
        const confirmLinks = document.querySelectorAll('a[data-confirm], button[data-confirm]');
        
        confirmLinks.forEach(function(element) {
            element.addEventListener('click', function(e) {
                const message = this.getAttribute('data-confirm') || 'Tem certeza que deseja continuar?';
                
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    // Expor funções úteis globalmente
    window.AgertTheme = {
        showAlert: showAlert,
        ajaxRequest: ajaxRequest
    };

})();